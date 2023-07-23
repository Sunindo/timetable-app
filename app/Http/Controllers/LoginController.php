<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Show the login dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Show the registration dashboard.
     * 
     * @return \Illuminate\Http\Response
     */
    public function registration()
    {
        return view('auth.registration');
    }

    /**
     * Validate a user's login attempt.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginPost(Request $request)
    {
        // Request rules for required fields.
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        // Attempt to validate the user's login details against the system.
        if (Auth::attempt($credentials)) {
            // If user details are valid, redirect to Home page.
            return redirect()->intended(route('home'))->with('success', 'Login Successful');
        }

        // If user details are invalid, redirect back to login page to try again.
        return redirect(route('login'))->with('error', 'Login details are invalid');
    }

    /**
     * Process a user's registration request.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registrationPost(Request $request)
    {
        // TODO:
        // Registration does not have visible display for required fields.
        // Possible need to make teacher_id a required field and validate the id exists in the database teacher table.

        // Request rules for required and unique fields.
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        // Request rules for mandatory and unique fields.
        if (!is_null($request->teacher_id)) {
            $request->validate([
                'teacher_id' => 'unique:users,teacher_id',
            ]);    
        }

        // Store the request details.
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['teacher_id'] = $request->teacher_id;

        // Attempt to create a User object using the provided details.
        $user = User::create($data);

        if ($user) {
            // If user creation was successful redirect the user to the login page.
            return redirect(route('login'))->with('success', 'Account Registration Successful');
        }

        // If user creation was unsuccessful, redirect back to account registration to try again.
        return redirect(route('registration'))->with('error', 'Account details already exist');
    }

    /**
     * End user's session.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}