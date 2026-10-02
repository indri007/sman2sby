<?php
/**
 * Gamifikasi Helper - SMAN 2 Surabaya
 * Sesuai PRD gamifikasi.md
 * 
 * Sistem Poin:
 * - Hadir Tepat Waktu (<= 07:00 WIB) : +10 poin
 * - Hadir Terlambat   (> 07:00 WIB)  : +5 poin
 * - Izin / Sakit                     : +0 poin (Streak TIDAK reset)
 * - Alpa / Tanpa Keterangan          : +0 poin (Streak reset)
 * 
 * Badges:
 * - Streak >= 7 hari   : 🔥 Rajin Mingguan
 * - Streak >= 30 hari  : 🏆 Konsisten Sebulan
 * - Streak >= 90 hari  : 👑 Legend Absen
 */

const JAM_MASUK_MAKSIMAL = '07:00:59'; // Batas jam masuk tepat waktu

/**
 * Hitung statistik gamifikasi seluruh siswa untuk bulan dan tahun tertentu
 * @param mysqli $mysqli
 * @param int|null $bulan (1-12)
 * @param int|null $tahun (YYYY)
 * @return array
 */
function getGamifikasiLeaderboard($mysqli, $bulan = null, $tahun = null)
{
    if ($bulan === null) $bulan = intval(date('m'));
    if ($tahun === null) $tahun = intval(date('Y'));

    $bulanStr = str_pad($bulan, 2, '0', STR_PAD_LEFT);
    $tahunStr = strval($tahun);

    // 1. Ambil data seluruh siswa (pegawai)
    $qPegawai = "
        SELECT 
            p.id, 
            p.nama, 
            p.nip, 
            p.gambar,
            j.nama AS jabatan
        FROM pegawai p
        LEFT JOIN jabatan j ON p.id_jabatan = j.id
        ORDER BY p.nama ASC
    ";
    $resPegawai = $mysqli->query($qPegawai);
    $siswaList = [];
    if ($resPegawai) {
        while ($row = $resPegawai->fetch_assoc()) {
            $siswaList[$row['id']] = [
                'id' => $row['id'],
                'nama' => $row['nama'],
                'nip' => $row['nip'],
                'gambar' => $row['gambar'],
                'jabatan' => $row['jabatan'],
                'poin_bulan_ini' => 0,
                'poin_bulan_lalu' => 0,
                'hadir_tepat' => 0,
                'hadir_terlambat' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpa' => 0,
                'total_hadir' => 0,
                'current_streak' => 0,
                'longest_streak' => 0,
                'badges' => [],
                'most_improved_score' => 0,
                'rank' => 0
            ];
        }
    }

    if (empty($siswaList)) {
        return [];
    }

    // 2. Ambil seluruh riwayat presensi sampai bulan yang dipilih (untuk menghitung streak & poin)
    $qPresensi = "
        SELECT 
            id_pegawai,
            DATE(tanggal_waktu) AS tanggal,
            MIN(TIME(tanggal_waktu)) AS jam_pertama,
            status,
            jenis
        FROM presensi_pegawai
        GROUP BY id_pegawai, DATE(tanggal_waktu), status
        ORDER BY tanggal ASC, jam_pertama ASC
    ";
    $resPresensi = $mysqli->query($qPresensi);
    $presensiPerSiswa = [];
    if ($resPresensi) {
        while ($p = $resPresensi->fetch_assoc()) {
            $presensiPerSiswa[$p['id_pegawai']][$p['tanggal']] = [
                'status' => $p['status'],
                'jam' => $p['jam_pertama']
            ];
        }
    }

    // 3. Hitung Poin Bulan Ini & Bulan Lalu per siswa
    $prevBulan = $bulan == 1 ? 12 : $bulan - 1;
    $prevTahun = $bulan == 1 ? $tahun - 1 : $tahun;
    $prevBulanStr = str_pad($prevBulan, 2, '0', STR_PAD_LEFT);

    foreach ($siswaList as $id => &$siswa) {
        $dataPresensiSiswa = $presensiPerSiswa[$id] ?? [];

        // Hitung poin & rincian bulan ini
        foreach ($dataPresensiSiswa as $tgl => $det) {
            $tglTime = strtotime($tgl);
            $tglBulan = date('m', $tglTime);
            $tglTahun = date('Y', $tglTime);

            if ($tglBulan == $bulanStr && $tglTahun == $tahunStr) {
                if ($det['status'] === 'Hadir') {
                    $siswa['total_hadir']++;
                    if ($det['jam'] <= JAM_MASUK_MAKSIMAL) {
                        $siswa['poin_bulan_ini'] += 10;
                        $siswa['hadir_tepat']++;
                    } else {
                        $siswa['poin_bulan_ini'] += 5;
                        $siswa['hadir_terlambat']++;
                    }
                } elseif ($det['status'] === 'Izin') {
                    $siswa['izin']++;
                } elseif ($det['status'] === 'Sakit') {
                    $siswa['sakit']++;
                }
            } elseif ($tglBulan == $prevBulanStr && $tglTahun == $prevTahun) {
                if ($det['status'] === 'Hadir') {
                    if ($det['jam'] <= JAM_MASUK_MAKSIMAL) {
                        $siswa['poin_bulan_lalu'] += 10;
                    } else {
                        $siswa['poin_bulan_lalu'] += 5;
                    }
                }
            }
        }

        // Hitung Most Improved Poin
        $siswa['most_improved_score'] = $siswa['poin_bulan_ini'] - $siswa['poin_bulan_lalu'];

        // 4. Hitung Streak Kehadiran (Consecutive school days up to today or month end)
        // Streak bertambah saat Hadir, Izin, atau Sakit (tidak reset).
        // Streak reset jika ada hari sekolah aktif (Senin-Jumat) yang terlewat tanpa Hadir/Izin/Sakit.
        $streak = 0;
        $maxStreak = 0;
        
        // Buat daftar tanggal sekolah yang relevan
        $allDates = array_keys($dataPresensiSiswa);
        sort($allDates);

        if (!empty($allDates)) {
            $firstDate = $allDates[0];
            $today = date('Y-m-d');
            $endDate = (strtotime($tahunStr . '-' . $bulanStr . '-01') <= strtotime(date('Y-m-01'))) ? $today : date('Y-m-t', strtotime($tahunStr . '-' . $bulanStr . '-01'));

            $currentCheck = strtotime($firstDate);
            $endCheck = strtotime($endDate);

            while ($currentCheck <= $endCheck) {
                $dayOfWeek = date('w', $currentCheck); // 0 = Minggu, 6 = Sabtu
                $currDateStr = date('Y-m-d', $currentCheck);

                // Abaikan hari Sabtu dan Minggu (hari libur)
                if ($dayOfWeek != 0 && $dayOfWeek != 6) {
                    if (isset($dataPresensiSiswa[$currDateStr])) {
                        // Siswa hadir, izin, atau sakit -> streak bertambah
                        $streak++;
                        if ($streak > $maxStreak) {
                            $maxStreak = $streak;
                        }
                    } else {
                        // Jika hari ini belum berakhir, jangan langsung reset jika belum absen hari ini
                        if ($currDateStr === $today) {
                            // Hari ini sedang berjalan, tidak langsung mereset streak hari sebelumnya
                        } else {
                            // Hari sekolah terlewat -> streak reset
                            $streak = 0;
                        }
                    }
                }
                $currentCheck = strtotime('+1 day', $currentCheck);
            }
        }

        $siswa['current_streak'] = $streak;
        $siswa['longest_streak'] = $maxStreak;

        // 5. Hitung Badges
        $badges = [];
        $activeStreakForBadge = max($streak, $maxStreak);
        if ($activeStreakForBadge >= 7) {
            $badges[] = [
                'id' => 'badge_7',
                'name' => 'Rajin Mingguan',
                'icon' => '🔥',
                'desc' => 'Streak 7 hari beruntun',
                'min_streak' => 7,
                'unlocked' => true
            ];
        }
        if ($activeStreakForBadge >= 30) {
            $badges[] = [
                'id' => 'badge_30',
                'name' => 'Konsisten Sebulan',
                'icon' => '🏆',
                'desc' => 'Streak 30 hari beruntun',
                'min_streak' => 30,
                'unlocked' => true
            ];
        }
        if ($activeStreakForBadge >= 90) {
            $badges[] = [
                'id' => 'badge_90',
                'name' => 'Legend Absen',
                'icon' => '👑',
                'desc' => 'Streak 90 hari beruntun',
                'min_streak' => 90,
                'unlocked' => true
            ];
        }
        $siswa['badges'] = $badges;
    }
    unset($siswa);

    // 6. Urutkan Leaderboard berdasarkan Total Poin DESC, lalu Streak DESC, lalu Nama ASC
    uasort($siswaList, function ($a, $b) {
        if ($b['poin_bulan_ini'] !== $a['poin_bulan_ini']) {
            return $b['poin_bulan_ini'] <=> $a['poin_bulan_ini'];
        }
        if ($b['current_streak'] !== $a['current_streak']) {
            return $b['current_streak'] <=> $a['current_streak'];
        }
        return strcmp($a['nama'], $b['nama']);
    });

    // 7. Berikan Peringkat (Rank) 1..N
    $rank = 1;
    $leaderboard = [];
    foreach ($siswaList as $s) {
        $s['rank'] = $rank++;
        $leaderboard[] = $s;
    }

    return $leaderboard;
}

/**
 * Dapatkan statistik gamifikasi untuk siswa tertentu yang sedang login
 * @param mysqli $mysqli
 * @param int $id_pegawai
 * @param int|null $bulan
 * @param int|null $tahun
 * @return array|null
 */
function getGamifikasiSiswa($mysqli, $id_pegawai, $bulan = null, $tahun = null)
{
    $leaderboard = getGamifikasiLeaderboard($mysqli, $bulan, $tahun);
    foreach ($leaderboard as $item) {
        if ($item['id'] == $id_pegawai) {
            // Hitung progress menuju badge berikutnya
            $streak = $item['current_streak'];
            $nextBadge = null;
            $progressPercent = 0;

            if ($streak < 7) {
                $nextBadge = ['name' => 'Rajin Mingguan', 'target' => 7, 'icon' => '🔥'];
                $progressPercent = round(($streak / 7) * 100);
            } elseif ($streak < 30) {
                $nextBadge = ['name' => 'Konsisten Sebulan', 'target' => 30, 'icon' => '🏆'];
                $progressPercent = round((($streak - 7) / (30 - 7)) * 100);
            } elseif ($streak < 90) {
                $nextBadge = ['name' => 'Legend Absen', 'target' => 90, 'icon' => '👑'];
                $progressPercent = round((($streak - 30) / (90 - 30)) * 100);
            } else {
                $nextBadge = ['name' => 'Maksimal (Legend Absen)', 'target' => 90, 'icon' => '👑'];
                $progressPercent = 100;
            }

            $item['next_badge'] = $nextBadge;
            $item['progress_percent'] = min(100, max(0, $progressPercent));
            $item['total_siswa'] = count($leaderboard);

            return $item;
        }
    }
    return null;
}

/**
 * Ambil data Most Improved siswa (Peningkatan poin tertinggi dibanding bulan lalu)
 * @param array $leaderboard
 * @return array|null
 */
function getMostImprovedSiswa($leaderboard)
{
    $mostImproved = null;
    $maxScore = -9999;
    foreach ($leaderboard as $s) {
        if ($s['most_improved_score'] > $maxScore && $s['most_improved_score'] > 0) {
            $maxScore = $s['most_improved_score'];
            $mostImproved = $s;
        }
    }
    return $mostImproved;
}

/**
 * Data Hall of Fame (Pencapaian Top 3 dan Most Improved per periode bulan)
 * @param mysqli $mysqli
 * @return array
 */
function getHallOfFameData($mysqli)
{
    $currentYear = intval(date('Y'));
    $currentMonth = intval(date('m'));

    $hallOfFame = [];

    // Ambil histori bulan-bulan yang memiliki presensi
    $qHist = "
        SELECT DISTINCT 
            MONTH(tanggal_waktu) AS bulan,
            YEAR(tanggal_waktu) AS tahun
        FROM presensi_pegawai
        ORDER BY tahun DESC, bulan DESC
    ";
    $resHist = $mysqli->query($qHist);
    if ($resHist) {
        while ($row = $resHist->fetch_assoc()) {
            $b = intval($row['bulan']);
            $y = intval($row['tahun']);

            $lb = getGamifikasiLeaderboard($mysqli, $b, $y);
            if (!empty($lb)) {
                $top3 = array_slice($lb, 0, 3);
                $mostImproved = getMostImprovedSiswa($lb);

                $hallOfFame[] = [
                    'bulan' => $b,
                    'tahun' => $y,
                    'nama_bulan' => MONTH_IN_INDONESIA[$b - 1] . ' ' . $y,
                    'is_current' => ($b === $currentMonth && $y === $currentYear),
                    'top3' => $top3,
                    'most_improved' => $mostImproved
                ];
            }
        }
    }

    return $hallOfFame;
}
