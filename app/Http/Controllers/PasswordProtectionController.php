<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class PasswordProtectionController extends Controller
{

    public function VerifyPasswordProtect(Request $request)
    {
        $request->validate([
            'password' => ['required','string','min:8','matches_original_password'],
        ],[
            'password.matches_original_password' => 'Please Enter Valid Password!',
        ]);

        $request->session()->put('password_entered', true);
        return response()->json(['success' => true]);
    }

}
