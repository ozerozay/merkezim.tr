<?php

namespace App\Http\Controllers;

use App\Tenant;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        $tenantId = tenant()->id; // Aktif tenant bilgisi alınır

        return Socialite::driver($provider)
            ->with(['state' => $tenantId]) // Tenant bilgisini 'state' ile ekle
            ->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        // Tenant bilgisini 'state' parametresinden al
        $tenantId = request()->get('state');
        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            abort(404, 'Tenant not found.');
        }

        // Kullanıcıyı tenant'a göre oluştur veya güncelle
        $user = $tenant->users()->updateOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
                'avatar' => $socialUser->getAvatar(),
            ]
        );

        Auth::login($user);

        return redirect('/');
    }
}
