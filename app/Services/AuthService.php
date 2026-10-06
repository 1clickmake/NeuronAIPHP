<?php

namespace App\Services;

class AuthService {
    public static function isMember() {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    public static function isGuest() {
        return !self::isMember();
    }

    public static function isSuperAdmin() {
        if (!self::isMember()) return false;
        $level = isset($_SESSION['user']['level']) ? (int)$_SESSION['user']['level'] : 1;
        return $level >= 10;
    }

    public static function isAdmin() {
        if (!self::isMember()) return false;
        $user = self::user();
        $level = isset($user['level']) ? (int)$user['level'] : 1;
        return ((isset($user['role']) && $user['role'] === 'admin') || $level >= 5);
    }

    public static function user() {
        return self::isMember() ? $_SESSION['user'] : [];
    }
}
