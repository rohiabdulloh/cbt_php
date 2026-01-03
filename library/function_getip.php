<?php
function getClientIp()
{
    $keys = [
        'HTTP_CF_CONNECTING_IP', // Cloudflare
        'HTTP_X_FORWARDED_FOR',
        'HTTP_CLIENT_IP',
        'REMOTE_ADDR'
    ];

    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = $_SERVER[$key];

            // Jika banyak IP (X-Forwarded-For)
            if (strpos($ip, ',') !== false) {
                $ip = explode(',', $ip)[0];
            }

            // Validasi IP
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }

    return null;
}
?>