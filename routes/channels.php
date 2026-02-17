<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

// Channel للمستخدمين (للموديلات)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    $authorized = (int) $user->id === (int) $id;

    Log::info('Broadcasting channel authorization', [
        'channel' => 'App.Models.User.' . $id,
        'user_id' => $user->id,
        'requested_id' => $id,
        'authorized' => $authorized
    ]);

    return $authorized;
});

// Channel مخصص للإشعارات (user.{userId})
Broadcast::channel('user.{userId}', function ($user, $userId) {
    $authorized = (int) $user->id === (int) $userId;

    Log::info('Broadcasting channel authorization', [
        'channel' => 'user.' . $userId,
        'user_id' => $user->id,
        'requested_user_id' => $userId,
        'authorized' => $authorized
    ]);

    if (!$authorized) {
        Log::warning('Broadcasting channel authorization failed', [
            'channel' => 'user.' . $userId,
            'authenticated_user_id' => $user->id,
            'requested_user_id' => $userId
        ]);
    }

    return $authorized;
});
