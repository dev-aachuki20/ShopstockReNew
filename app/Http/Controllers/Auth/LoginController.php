<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Rules\IsActive;



class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentialsOnly = $request->validate([
            'username'    => ['required','string',new IsActive],
            'password' => ['required','string','min:8'],
        ]);

        $remember_me = !is_null($request->remember_me) ? true : false;

        try {

            $user = User::where('username',$request->username)->first();
            if($user){
                if (Auth::attempt($credentialsOnly, $remember_me)) {
                   $check_ip = checkRoleIpPermission($request->ip(), $user->roles->first()->id);
                   if($check_ip == "No" && $user->roles->first()->id != 1){
                        Auth::guard('web')->logout();
                        return redirect()->route('login')->withErrors(['wrongcrendials' => trans('auth.iperror')])->withInput($request->only('username', 'password'));
                   }

                    addToLog($request,'User','Login successfully');
                    return redirect()->route('admin.transactions.type',['sales'])->with(['success' => true,
                    'message' => trans('quickadmin.qa_login_success'),
                    'title'=> trans('quickadmin.qa_login'),
                    'alert-type'=> trans('quickadmin.alert-type.success')]);
                }

                return redirect()->route('login')->withErrors(['wrongcrendials' => trans('auth.failed')])->withInput($request->only('username', 'password'));

            }else{
                return redirect()->route('login')->withErrors(['username' => trans('quickadmin.qa_invalid_username')])->withInput($request->only('username'));
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors($e->getMessage())->withInput($request->only('username', 'password'));
        }

    }

    public function logout(Request $request)
        {
            Auth::guard('web')->logout();

            // Clear the password_entered session variable
            $request->session()->forget('password_entered');
            // Redirect to the login page
            addToLog($request,'User','Logout successfully');
            return redirect()->route('login');
        }

}
