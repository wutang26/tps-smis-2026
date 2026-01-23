<?php

namespace App\Helpers;

class AuditHelper
{
    public static function detectBrowser($userAgent)
    {
        if (strpos($userAgent, 'Edg/') !== false) {
            return 'Microsoft Edge';
        } elseif (strpos($userAgent, 'Chrome/') !== false) {
            return 'Google Chrome';
        } elseif (strpos($userAgent, 'Firefox/') !== false) {
            return 'Mozilla Firefox';
        } elseif (strpos($userAgent, 'Safari/') !== false && strpos($userAgent, 'Chrome/') === false) {
            return 'Safari';
        } else {
            return 'Unknown';
        }
    }
}

?>