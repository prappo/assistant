<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $email = Auth::user()->email;
        $name = Auth::user()->name;
        return view('profile', compact('email', 'name'));
    }

    public function update(Request $re)
    {
        $name = $re->name;
        $email = $re->email;
        $oldPass = $re->oldPass;
        $newPass = $re->newPass;
        if ($newPass == "") {
            User::where('email', Auth::user()->email)->update([
                'email' => $email,
                'name' => $name
            ]);
            return "success";
        } else {
            if ($oldPass == "") {
                return "Please input old password";
            } else {
                if (Hash::check($oldPass, Auth::user()->password)) {
                    User::where('email', Auth::user()->email)->update([
                        'email' => $email,
                        'name' => $name,
                        'password' => bcrypt($newPass)
                    ]);
                    return "success";
                } else {
                    return "Old password didn't match";
                }
            }
        }
    }
}