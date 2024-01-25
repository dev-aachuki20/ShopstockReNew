<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Rules\IsActive;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    //
    public function index()
    {
        //
        return view('auth.login');
    }

    public function login(Request $request){
        $validated = $request->validate([
            'username'    => ['required','string',new IsActive],
            'password' => 'required|min:4',

        ],[
            'username.required' => 'The Username is required.',
            'password.required' => 'The Password is required.',
        ]);

        $remember_me = !is_null($request->remember_me) ? true : false;
        $credentialsOnly = [
            'username'    => $request->username,
            'password' => $request->password,
        ];
        try {

            $user = User::where('username',$request->username)->first();
            if($user){
                if (Auth::attempt($credentialsOnly, $remember_me)) {
                    // Staff Cannot Login Into Web
                   // dd(auth()->user()->getRoleNames());
                    if ((auth()->user()->hasRole(config('app.roleid.staff')))) {
                        Auth::guard('web')->logout();
                        return redirect()->route('login')->withErrors(['wrongcrendials' => trans('auth.unauthorize')])->withInput($request->only('username', 'password'));
                    }
                    //return redirect()->route('dashboard')->with('success',trans('quickadmin.qa_login_success'));
                    return redirect()->route('dashboard')->with(['success' => true,
                    'message' => trans('quickadmin.qa_login_success'),
                    'title'=> trans('quickadmin.qa_login'),
                    'alert-type'=> trans('quickadmin.alert-type.success')]);
                }

                return redirect()->route('login')->withErrors(['wrongcrendials' => trans('auth.failed')])->withInput($request->only('username', 'password'));

            }else{
                return redirect()->route('login')->withErrors(['username' => trans('quickadmin.qa_invalid_username')])->withInput($request->only('username'));
            }

        } catch (ValidationException $e) {
            return redirect()->route('login')->withErrors($validated)->withInput($request->only('username', 'password'));
        }

    }

    public function logout()
        {
            Auth::guard('web')->logout();
            // Redirect to the login page
            return redirect()->route('login');
        }

}
