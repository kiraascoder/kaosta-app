<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PwaSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint' => 'required',
            'keys.auth' => 'required',
            'keys.p256dh' => 'required',
        ]);

        $user = $request->user();
        if ($user) {
            $user->pwa_subscription = json_encode($request->all());
            $user->save();
            return response()->json(['message' => 'Subscription berhasil disimpan.']);
        }

        return response()->json(['message' => 'Gagal menyimpan subscription.'], 403);
    }
}
