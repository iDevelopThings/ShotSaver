<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function settings()
    {
        return view('settings');
    }

    /**
     * Allow the user to change their password
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword()
    {
        $this->validate(request(), [
            'password' => 'required|confirmed',
        ]);

        $user = request()->user();

        $user->password = Hash::make(request('password'));
        $user->save();

        return back()->with('success', 'Successfully changed password.');
    }
}
