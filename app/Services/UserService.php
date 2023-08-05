<?php

namespace App\Services;

use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Str;

class UserService {

    /**
     * Fetch standard User Profile data
     * @param integer $id ID of the user profile
     */
    public static function getProfile(int $id)
    {
        $profile = User::query()->findOrFail($id);
        return $profile;
    }

    public static function getUserCookieDetails(string $token = '')
    {
        return [
            'name' => '_token',
            'value' => $token,
            'minutes' => 365*24*60,
            'path' => null,
            'domain' => null,
            'secure' => null, // for localhost
            'httponly' => true,
            'samesite' => true,
        ];
    }
}
