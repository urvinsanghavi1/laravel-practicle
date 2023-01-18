<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
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
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'max:255'],
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
        return view('company_registration.registration');
    }
}
