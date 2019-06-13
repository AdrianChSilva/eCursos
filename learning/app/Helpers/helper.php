<?php
namespace App\Helpers;

class Helper {
    public static function uploadFile($key, $path) {
        request()->file($key)->store($path);
        return request()->file($key)->hashName();
    }
}
