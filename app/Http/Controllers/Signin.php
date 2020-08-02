<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class Signin extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $authUser = $this->findOrCreate($user, $provider);
        Auth::login($authUser, true);
        session()->flash('login', 'Đăng nhập thành công');
        return redirect('/');
    }

    public function findOrCreate($user, $provider)
    {
        $userUnique = User::where('email', $user->email)->first();
        if ($userUnique) {
            return $userUnique;
        }
        $authUser = User::where('provider_id', $user->id)->first();

        if ($authUser) {
        return $authUser;
    }
        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'provider' => strtoupper($provider),
            'provider_id' => $user->id,
        ]);

    }
}

