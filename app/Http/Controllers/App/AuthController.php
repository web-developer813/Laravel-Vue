<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\LoginRequest;
//use Illuminate\Foundation\Auth\ThrottlesLogins;

class AuthController extends Controller
{
    //use ThrottlesLogins;

    # get
    public function get()
    {
        return view('app.auth.login');
    }

    # login
    public function post(LoginRequest $request)
    {

        $input = (object) $request->only('email', 'password');

        // clear sessions
        $request->session()->forget('auth-nonprofit');
        $request->session()->forget('auth-forprofit');

        $attempt = auth()->attempt([
            'email' => $input->email,
            'password' => $input->password
        ], true);

        // error
        if (!$attempt)
        {
            flash('We could not verify your email or password.', 'error');
            return redirect()->back()->withInput();
        }

        // successful + invitations
        if (session()->has('invitation'))
            session()->get('invitation')->acceptedBy(auth()->id());

        // return to dashboard
		return redirect()->intended(route('dashboard'));
    }

    # logout
    public function logout(Request $request)
    {
        // clear sessions
        $request->session()->forget('auth-nonprofit');
        $request->session()->forget('auth-forprofit');

        auth()->logout();
        return redirect()->route('login');
    }
}
