<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Traits\ExtrnalApiConnection;
use App\Constants\MainTableConstans as mtc;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use App\Traits\MultiTenantProcess;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    use ExtrnalApiConnection, MultiTenantProcess;
    /** 
     * login page redirection
     * 
     * @return redirect system user to login page which created in blade template
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Check user authanticated or not.
     * 
     * if user not authanticated then user redirect to login page otherwise it will redirect to home page.
     * 
     * @return redirection authantication wise.
     */
    public function login(Request $request)
    {
        $request->validate([
            mtc::USER_TABLE_EMAIL => ['required', 'email', 'max:255'],
            mtc::USER_TABLE_PASSWORD => ['required', 'max:255'],
        ]);

        $data = $request->all();
        unset($data['_token']);
        $user = Auth::getProvider()->retrieveByCredentials($data);

        /** check if user credentials are not match then redirect to login page with error message. */
        if (!$user) {
            return redirect()->back()->withErrors(["message" => 'Email & Password Wrong.']);
        }

        Auth::login($user);

        return redirect('/home');
    }

    /**
     * Home page redirection.
     */
    public function home()
    {
        return view("home.index");
    }

    /**
     * Flush the session and logout authanticated user.
     * 
     * @return redirect to home page.
     */
    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect('/home');
    }

    /**
     * @return redirect to company register page
     */
    public function register()
    {
        $countries = $this->getCountry();
        return view('company_registration.registration')->with('countries', $countries);
    }

    /**
     * state and country wise get the data of city and state
     * 
     * @return state country wise and city state wise
     */
    public function changeLocation(Request $request, $name)
    {
        $data = $request->all();
        unset($data['_token']);
        if ($name == "state") {

            if (!isset($data['country'])) {
                return "Please Select Country";
            }

            $states = $this->getStatesByCountry($data['country']);
            return $states;
        } elseif ($name == "city") {

            if (!isset($data['country']) && !isset($data['state'])) {
                return "Please Select State and Country";
            }

            $cities = $this->getCityByState($data['country'], $data['state']);
            return $cities;
        }
    }

    /**
     * Registration of new company
     * 
     * Create New Database with table and store new company data in respactive table.
     */
    public function registerCompany(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $request->validate(
            [
                mtc::COMPANY_TABLE_COMPANY_NAME => ['required', 'max:100'],
                mtc::COMPANY_PROFILE_EMAIL => ['required', 'unique:email', 'max:100'],
                mtc::COMPANY_PROFILE_PASSWORD => ['required', 'max:16', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[@$!%*#?&]/'],
                mtc::COMPANY_PROFILE_WEBSITE => ['required'],
                mtc::COMPANY_PROFILE_LICENSE_NUMBER => ['required', 'max:50'],
                mtc::COMPANY_PROFILE_ADDRESS => ['required', 'max:500'],
            ]
            ,
            [
                'password.regex' => "Password must contain 1 capital letter, 1 small letter, and 1 special characters."
            ]
        );

        try {
           
            $companyName = str_replace(' ', '_', Str::lower($data[mtc::COMPANY_TABLE_COMPANY_NAME]));
            $existCompany = Company::where(mtc::COMPANY_TABLE_COMPANY_NAME, $companyName)->first();
            if ($existCompany) {
                return redirect()->back()->withErrors(['message' => 'Company Name already Exist.']);
            }
            //register company in main table
            DB::beginTransaction();
            $company = Company::create(
                [
                    mtc::COMPANY_TABLE_COMPANY_NAME => $companyName,
                    mtc::COMPANY_TABLE_STATUS => 1
                ]
            );
            DB::commit();
            $companyId = $company->id;
            //create database for register company
            $this->databaseCreate($companyName);
            //tenant table store database information
            $password = config('database.connections.tenant.password');
            $databaseDetails = [
                mtc::TENANT_TABLE_HOSTNAME => mtc::HOSTNAME_DEFAULT_VALUE,
                mtc::TENANT_TABLE_PORT => mtc::PORT_DEFAULT_VALUE,
                mtc::TENANT_TABLE_DBNAME => $companyName,
                mtc::TENANT_TABLE_DBUSERNAME => config('database.connections.tenant.username'),
                mtc::TENANT_TABLE_DBPASSWORD => isset($password) ? $password : "",
                mtc::TENANT_TABLE_COMPANY_ID => $companyId
            ];
            $this->storeDatabaseDetail($databaseDetails);
            //create table in customer database
            $this->createCustomerProfile();
            //store customer profile detaile into new table 
           
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
