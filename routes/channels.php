<?php

use Illuminate\Support\Facades\Broadcast;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

Broadcast::routes(['middleware' => [InitializeTenancyByDomain::class, 'auth']]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    dump('asdasd');

    return (int) $user->id === (int) $id;
});
