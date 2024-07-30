<?php

// app/Http/Controllers/EnvironmentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnvironmentController extends Controller
{
    public function checkEnv()
    {
        $clientId = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');
        return [
            'PAYPAL_CLIENT_ID' => $clientId,
            'PAYPAL_CLIENT_SECRET' => $clientSecret
        ];
    }
}

