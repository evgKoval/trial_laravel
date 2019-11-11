<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $userName = $user->name;
        $userEmail = $user->email;
        
        return view('profile', compact('userName', 'userEmail'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(Auth::user()->id)
            ]
        ]);

        $user = Auth::user();
        
        if ($request->input('password') === null) {
            $password = $user->password;
        } else {
            $password = bcrypt($request->input('password'));
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $password;

        $user->save();

        return redirect()->back();
    }
}
