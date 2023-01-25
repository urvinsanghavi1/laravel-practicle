<?php

namespace App\Http\Controllers;

use App\Constants\CommanConstans as commanConstans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Traits\ExtrnalApiConnection;
use App\Constants\MainTableConstans as mainTableConstans;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Traits\MultiTenantProcess;
use Illuminate\Support\Str;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    use ExtrnalApiConnection, MultiTenantProcess;

    public $tenantManager;

    public function __construct()
    {
        $this->tenantManager = app(TenantManager::class);
    }

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
            mainTableConstans::USER_TABLE_EMAIL => ['required', 'email', 'max:255'],
            mainTableConstans::USER_TABLE_PASSWORD => ['required', 'max:255'],
        ]);

        $data = $request->all();
        unset($data['_token']);

        try {
            if (Str::contains(config('app.url'), url(''))) {
                $data[mainTableConstans::USER_TABLE_PASSWORD] = $this->encrypt_decrypt("encrypt", $data[mainTableConstans::USER_TABLE_PASSWORD]);
                $user = User::where($data)->first();
                if (!$user) {
                    return redirect()->back()->withErrors(["message" => 'Email & Password Wrong.']);
                }

                Auth::login($user);
                Log::info("Super User Login Success");
                return redirect('/home')->with('success', "Login Successfully.");;
            } else {

                $data[mainTableConstans::USER_TABLE_PASSWORD] = $this->encrypt_decrypt("encrypt", $data[mainTableConstans::USER_TABLE_PASSWORD]);
                $userCheck  = DB::connection(commanConstans::TENANT_CONNECTION_NAME)->table(mainTableConstans::COMPANY_PROFILE_TABLE)->where($data)->first();
                if ($userCheck) {
                    session([
                        "company_name" => $userCheck->name
                    ]);
                    Log::info("Company Login Success");
                    return redirect('/home')->with('success', "Login Successfully.");
                } else {
                    return redirect()->back()->withErrors(["message" => 'Email & Password Wrong.']);
                }
            }
        } catch (\Exception $exception) {
            Log::error("Login Error | " . $exception->getMessage());
        }
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

        if (Str::contains(config('app.url'), url(''))) {
            Auth::logout();
        }

        return redirect('/');
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
        try {
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
        } catch (\Exception $exception) {
            Log::error("Change Location| Error | " . $exception->getMessage());
        }
    }

    /**
     * Registration of new company
     * 
     * Create New Database with table and store new company data in respactive table.
     * 
     * @return redirect to register page with message(success or error)
     */
    public function registerCompany(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $request->validate(
            [
                mainTableConstans::COMPANY_TABLE_COMPANY_NAME => ['required', 'max:100'],
                mainTableConstans::COMPANY_PROFILE_EMAIL => ['required', 'max:100'],
                mainTableConstans::COMPANY_PROFILE_PASSWORD => ['required', 'max:16', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[@$!%*#?&]/'],
                mainTableConstans::COMPANY_PROFILE_WEBSITE => ['required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
                mainTableConstans::COMPANY_PROFILE_LICENSE_NUMBER => ['required', 'max:50'],
                mainTableConstans::COMPANY_PROFILE_ADDRESS => ['required', 'max:500'],
            ],
            [
                'password.regex' => "Password must contain 1 capital letter, 1 small letter, and 1 special characters."
            ]
        );

        try {

            $companyName = trim(str_replace(' ', '_', Str::lower($data[mainTableConstans::COMPANY_TABLE_COMPANY_NAME])));
            $existCompany = Company::where(mainTableConstans::COMPANY_TABLE_COMPANY_NAME, $companyName)->first();
            if ($existCompany) {
                return redirect()->back()->withErrors(['message' => 'Company Name already Exist.']);
            }
            //register company in main table
            DB::beginTransaction();
            $company = Company::create(
                [
                    mainTableConstans::COMPANY_TABLE_COMPANY_NAME => $companyName,
                    mainTableConstans::COMPANY_TABLE_STATUS => 1
                ]
            );
            DB::commit();
            Log::info("Company Register Successfully | Company Table ");

            $companyId = $company->id;
            //create database for register company
            Log::info("Database created for register company | Multi Tenant Step - 1 ");
            $this->databaseCreate($companyName);
            //tenant table store database information
            $password = config('database.connections.tenant.password');
            $subDomain = trim(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace('_', '-', $companyName)));
            $databaseDetails = [
                mainTableConstans::TENANT_TABLE_HOSTNAME => mainTableConstans::HOSTNAME_DEFAULT_VALUE,
                mainTableConstans::TENANT_TABLE_PORT => mainTableConstans::PORT_DEFAULT_VALUE,
                mainTableConstans::TENANT_TABLE_DBNAME => $companyName,
                mainTableConstans::TENANT_TABLE_DBUSERNAME => config('database.connections.tenant.username'),
                mainTableConstans::TENANT_TABLE_DBPASSWORD => $password != "" ? $this->encrypt_decrypt("encrypt", $password) : "",
                mainTableConstans::TENANT_TABLE_COMPANY_ID => $companyId,
                mainTableConstans::TENANT_TABLE_DOMAIN_NAME => $subDomain
            ];

            Log::info("Store Information for Newly Created Database | Multi Tenant Step - 2");
            $this->storeDatabaseDetail($databaseDetails);

            //create table in customer database and Store data into new table
            $data[mainTableConstans::COMPANY_PROFILE_PASSWORD] = $this->encrypt_decrypt("encrypt", trim($data[mainTableConstans::COMPANY_PROFILE_PASSWORD]));
            $data[mainTableConstans::COMPANY_PROFILE_COMPANY_NAME] = $companyName;
            $data[mainTableConstans::COMPANY_PROFILE_CREATED_AT] = date('Y-m-d H:i:s');
            $data[mainTableConstans::COMPANY_PROFILE_UPDATED_AT] = date('Y-m-d H:i:s');
            $data[mainTableConstans::TENANT_TABLE_DOMAIN_NAME] = $subDomain;

            Log::info("Create Table for new tenant and store information about company | Multi Tenant Step - 3");
            $this->createCustomerProfile($data);

            return redirect()->back()->with('success', Str::upper($data[mainTableConstans::COMPANY_TABLE_COMPANY_NAME]) . ' Company Registred Successfully. Please Check Your Email !');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    /** 
     * Profile Edit for New Company
     * 
     * @return redirect to edit profile page of company form with company data
     */
    public function profileEdit()
    {
        try {
            $getUserDetails = DB::connection(commanConstans::TENANT_CONNECTION_NAME)->table(mainTableConstans::COMPANY_PROFILE_TABLE)
                ->where(mainTableConstans::COMPANY_PROFILE_COMPANY_NAME, session('company_name'))->first();
            $countries = $this->getCountry();
            if ($getUserDetails) {
                return view('company_registration.registration')->with(['getUserDetails' => $getUserDetails, 'countries' => $countries]);
            } else {
                Log::error(session('company_name') . " Data not Found.");
            }
        } catch (\Exception $exception) {
            Log::error("Profile Edit | Error | " . $exception->getMessage());
        }
    }

    /**
     * Edit Profile and update companies data
     * 
     * @return redirect back to home page with updated information
     */
    public function editProfile(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $request->validate([
            mainTableConstans::COMPANY_PROFILE_WEBSITE => ['required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            mainTableConstans::COMPANY_PROFILE_LICENSE_NUMBER => ['required', 'max:50'],
            mainTableConstans::COMPANY_PROFILE_ADDRESS => ['required', 'max:500'],
        ]);

        try {
            DB::beginTransaction();
            DB::table(mainTableConstans::COMPANY_PROFILE_TABLE)->update($data);
            DB::commit();
            return redirect('/home')->with('success', 'Update Profile Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Edit Profile | Error | " . $exception->getMessage());
        }
    }
}
