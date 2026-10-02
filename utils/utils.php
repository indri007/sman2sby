<?php

const MONTH_IN_INDONESIA = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "July",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember"
];

const DAY_IN_INDONESIA = [
    "Minggu",
    "Senin",
    "Selasa",
    "Rabu",
    "Kamis",
    "Jumat",
    "Sabtu"
];

function indoensiaDateWithDay($date)
{
    $tanggal = explode('-', $date)[2];
    $bulan = explode('-', $date)[1];
    $tahun = explode('-', $date)[0];
    return DAY_IN_INDONESIA[Date("w", strtotime($date))] . ', ' . $tanggal . ' ' . MONTH_IN_INDONESIA[$bulan - 1] . ' ' . $tahun;
}

function indonesiaDate($date)
{
    $tanggal = explode('-', $date)[2];
    $bulan = explode('-', $date)[1];
    $tahun = explode('-', $date)[0];
    return $tanggal . ' ' . MONTH_IN_INDONESIA[$bulan - 1] . ' ' . $tahun;
}

// https://stackoverflow.com/questions/14185975/calculate-number-of-working-days-in-a-month
// Note about ignore parameter: 0 is sunday, ..., 6 is saturday.
function workingDays($year, $month, $ignore)
{
    $count = 0;
    $counter = mktime(0, 0, 0, $month, 1, $year);
    while (date("n", $counter) == $month) {
        if (in_array(date("w", $counter), $ignore) == false) {
            $count++;
        }
        $counter = strtotime("+1 day", $counter);
    }
    return $count;
}

function offDays($year, $month, $ignore)
{
    $count = 0;
    $counter = mktime(0, 0, 0, $month, 1, $year);
    while (date("n", $counter) == $month) {
        if (in_array(date("w", $counter), $ignore)) {
            $count++;
        }
        $counter = strtotime("+1 day", $counter);
    }
    return $count;
}

function getYearMonthDayFromCompareDateWithToday($date)
{
    $date1 = new DateTime($date);
    $date2 = new DateTime();

    $interval = $date1->diff($date2);

    return [
        'year' => $interval->y,
        'month' => $interval->m,
        'day' => $interval->d,
    ];
}

const SMAN2_LATITUDE = -7.265554;
const SMAN2_LONGITUDE = 112.750389;
const SMAN2_RADIUS_METER = 200;

function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371000; // Radius dalam meter
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return round($earthRadius * $c, 2);
}

include_once __DIR__ . "/device_helper.php";
include_once __DIR__ . "/telegram_helper.php";
include_once __DIR__ . "/gamifikasi_helper.php";


