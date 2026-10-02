<?php

/**
 * Helper untuk Mendeteksi IP Address dan Informasi Perangkat/Browser
 * SMAN 2 Surabaya
 */

function getClientIP()
{
    $ip = '';
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ipList[0]);
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    if ($ip === '::1' || $ip === '127.0.0.1') {
        return '127.0.0.1 (Local)';
    }

    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : ($ip ?: 'Unknown IP');
}

function getDeviceInfo($userAgent = null)
{
    if ($userAgent === null) {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    // 1. Deteksi Sistem Operasi / Perangkat
    $os = 'Unknown OS';
    $deviceType = 'Desktop';

    if (preg_match('/android/i', $userAgent)) {
        $os = 'Android';
        $deviceType = 'Mobile';
        if (preg_match('/android\s+([\d\.]+)/i', $userAgent, $matches)) {
            $os .= ' ' . $matches[1];
        }
    } elseif (preg_match('/iphone/i', $userAgent)) {
        $os = 'iPhone (iOS)';
        $deviceType = 'Mobile';
    } elseif (preg_match('/ipad/i', $userAgent)) {
        $os = 'iPad (iPadOS)';
        $deviceType = 'Tablet';
    } elseif (preg_match('/windows nt 10\.0/i', $userAgent)) {
        $os = 'Windows 10/11';
    } elseif (preg_match('/windows nt 6\.3/i', $userAgent)) {
        $os = 'Windows 8.1';
    } elseif (preg_match('/windows nt 6\.2/i', $userAgent)) {
        $os = 'Windows 8';
    } elseif (preg_match('/windows nt 6\.1/i', $userAgent)) {
        $os = 'Windows 7';
    } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
        $os = 'macOS';
    } elseif (preg_match('/linux/i', $userAgent)) {
        $os = 'Linux';
    }

    // 2. Deteksi Browser
    $browser = 'Unknown Browser';
    if (preg_match('/edg/i', $userAgent)) {
        $browser = 'Microsoft Edge';
        if (preg_match('/edg\/([\d\.]+)/i', $userAgent, $m)) $browser .= ' ' . explode('.', $m[1])[0];
    } elseif (preg_match('/samsungbrowser/i', $userAgent)) {
        $browser = 'Samsung Browser';
    } elseif (preg_match('/chrome/i', $userAgent) && !preg_match('/opr|opera/i', $userAgent)) {
        $browser = 'Google Chrome';
        if (preg_match('/chrome\/([\d\.]+)/i', $userAgent, $m)) $browser .= ' ' . explode('.', $m[1])[0];
    } elseif (preg_match('/safari/i', $userAgent) && !preg_match('/chrome|crios/i', $userAgent)) {
        $browser = 'Safari';
    } elseif (preg_match('/firefox/i', $userAgent)) {
        $browser = 'Mozilla Firefox';
        if (preg_match('/firefox\/([\d\.]+)/i', $userAgent, $m)) $browser .= ' ' . explode('.', $m[1])[0];
    } elseif (preg_match('/opera|opr/i', $userAgent)) {
        $browser = 'Opera';
    }

    $summary = "$browser on $os";

    return [
        'summary' => $summary,
        'os' => $os,
        'browser' => $browser,
        'device_type' => $deviceType,
        'raw_user_agent' => $userAgent
    ];
}
