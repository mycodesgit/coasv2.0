<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class EncryptionHelper
{
    /**
     * Encrypt a value for URL
     */
    public static function encryptUrl($value)
    {
        return urlencode(Crypt::encryptString((string)$value));
    }

    /**
     * Decrypt a value from URL
     */
    public static function decryptUrl($encryptedValue)
    {
        try {
            return Crypt::decryptString(urldecode($encryptedValue));
        } catch (\Exception $e) {
            return null;
        }
    }
}