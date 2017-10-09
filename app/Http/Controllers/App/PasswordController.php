<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\ForgotPasswordRequest;
use App\Http\Requests\App\ResetPasswordRequest;
use App\User;
use Mail;
use Carbon\Carbon;
use DB;

class PasswordController extends Controller
{
    # forgot get
    public function forgot_get()
    {
        return view('app.auth.forgot-password');
    }
	
	# forgot post
	public function forgot_post(ForgotPasswordRequest $request)
	{
        $input = (object) $request->only('email');

        // get user
        $user = User::whereEmail($input->email)->firstOrFail();

        // create password reset table entry
        $token = Password::getRepository()->create($user);

        // mail data
        $data = [
            'name' => $user->volunteer->name,
            'email' => $user->email,
            'token' => $token
        ];

        // send email
        Mail::send('app.emails.auth.reset-password', $data, function ($m) use ($data) {
            $m->to($data['email'], $data['name']);
            $m->subject('Reset Your Tecdonor Password');
        });

        return redirect()->route('forgot-password-sent', ['email' => $input->email]);
	}

    # forgot sent
    public function forgot_sent()
    {
        return view('app.auth.forgot-password-sent');
    }

    # reset get
    public function reset_get()
    {
        return view('app.auth.reset-password');
    }

	# reset post
	public function reset_post(ResetPasswordRequest $request)
	{
        // input
        $input = (object) $request->only('email', 'resetToken', 'password');

        // verify token and email in database
        $token = DB::table('password_resets')
            ->whereEmail($input->email)
            ->whereToken($input->resetToken)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->first();

        // get user
        $user = User::whereEmail($token->email)->first();

        // update password
        $user->update(['password' => $input->password]);

        // delete token from db
        Db::table('password_resets')->whereEmail($input->email)->delete();

        // login user
        auth()->login($user);

        // flash success
        return redirect()->route('opportunities.index');
	}
}
