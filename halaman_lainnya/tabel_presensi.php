<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">Tabel Presensi Siswa / Pegawai Bulan <?= MONTH_IN_INDONESIA[Date('m') - 1]; ?> <?= Date("Y"); ?></h1>
            </div>
        </div>
    </div>
</section>

<?php
$q = "
    SELECT 
        * 
    FROM 
        pegawai 
    ORDER BY 
        nama";
$result = $mysqli->query($q);
$pegawai = [];
while ($row = $result->fetch_assoc()) {
    $pegawai[] = array_merge($row, ['presensi' => []]);
}

$q = "
    SELECT 
        id_pegawai, 
        DAY(tanggal_waktu) tanggal,
        status 
    FROM 
        presensi_pegawai 
    WHERE 
        MONTH(tanggal_waktu)='" . Date('m') . "' 
        AND 
        YEAR(tanggal_waktu)='" . Date("Y") . "' ORDER BY id_pegawai";
$presensi_pegawai = $mysqli->query($q)->fetch_all(MYSQLI_ASSOC);
foreach ($pegawai as $index => $value_pegawai) {

    for ($i = 1; $i <= Date('t'); $i++) {
        if ((Date('Y-m-') . ($i < 10 ? ('0' . $i) : $i)) <= Date('Y-m-d')) {
            $ada = false;
            foreach ($presensi_pegawai as $value_presensi_pegawai) {
                if ($value_pegawai['id'] == $value_presensi_pegawai['id_pegawai'] && $value_presensi_pegawai['tanggal'] == $i) {
                    if ($value_presensi_pegawai['status'] == 'Hadir') {
                        $pegawai[$index]['presensi'][] = 'H';
                    } elseif ($value_presensi_pegawai['status'] == 'Izin') {
                        $pegawai[$index]['presensi'][] = 'I';
                    } elseif ($value_presensi_pegawai['status'] == 'Sakit') {
                        $pegawai[$index]['presensi'][] = 'S';
                    }
                    $ada = !$ada;
                    break;
                }
            }
            if (!$ada) {
                $pegawai[$index]['presensi'][] = '-';
            }
        } else {
            $pegawai[$index]['presensi'][] = '';
        }
    }
}
?>

<style>
    .table-responsive-presensi {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table-presensi {
        font-size: 13px;
        white-space: nowrap;
        margin-bottom: 0;
        border-collapse: collapse;
    }
    .table-presensi th, .table-presensi td {
        padding: 6px 10px !important;
        vertical-align: middle !important;
        border: 1px solid #dee2e6 !important;
    }
    .table-presensi .col-nis {
        width: 120px;
        min-width: 110px;
        text-align: center;
    }
    .table-presensi .col-nama {
        min-width: 200px;
        text-align: left;
    }
    .table-presensi .col-day {
        width: 35px;
        min-width: 35px;
        text-align: center;
        padding: 6px 2px !important;
    }
    .badge-presensi {
        display: inline-block;
        width: 22px;
        height: 22px;
        line-height: 22px;
        text-align: center;
        border-radius: 4px;
        font-weight: bold;
        font-size: 11px;
    }
    .badge-h { background-color: #28a745; color: #fff; }
    .badge-i { background-color: #17a2b8; color: #fff; }
    .badge-s { background-color: #ffc107; color: #212529; }
    .badge-a { background-color: #dc3545; color: #fff; }
</style>

<section class="content">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-2"></i> Rekapitulasi Presensi Bulan <?= MONTH_IN_INDONESIA[Date('m') - 1]; ?> <?= Date("Y"); ?></h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive-presensi">
                <table class="table table-bordered table-striped table-hover table-presensi">
                    <thead class="bg-light">
                        <tr>
                            <th class="col-nis align-middle text-center" rowspan="2">NIS / NIP</th>
                            <th class="col-nama align-middle text-center" rowspan="2">Nama Siswa</th>
                            <th class="align-middle text-center bg-secondary" colspan="<?= Date('t'); ?>">Tanggal Presensi (1 - <?= Date('t'); ?> <?= MONTH_IN_INDONESIA[Date('m') - 1]; ?>)</th>
                        </tr>
                        <tr>
                            <?php for ($i = 1; $i <= Date('t'); $i++) : ?>
                                <th class="col-day align-middle text-center bg-light font-weight-bold"><?= $i; ?></th>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pegawai as $value) : ?>
                            <tr>
                                <td class="col-nis font-weight-bold text-center"><?= htmlspecialchars($value['nip']); ?></td>
                                <td class="col-nama text-left"><?= htmlspecialchars($value['nama']); ?></td>
                                <?php foreach ($value['presensi'] as $presensi) : ?>
                                    <td class="col-day text-center">
                                        <?php if ($presensi === 'H') : ?>
                                            <span class="badge-presensi badge-h" title="Hadir">H</span>
                                        <?php elseif ($presensi === 'I') : ?>
                                            <span class="badge-presensi badge-i" title="Izin">I</span>
                                        <?php elseif ($presensi === 'S') : ?>
                                            <span class="badge-presensi badge-s" title="Sakit">S</span>
                                        <?php elseif ($presensi === '-') : ?>
                                            <span class="badge-presensi badge-a" title="Alpa / Tidak Hadir">-</span>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex flex-wrap align-items-center">
                <strong class="mr-3"><i class="fas fa-info-circle mr-1"></i> Keterangan:</strong>
                <span class="badge badge-success px-2 py-1 mr-2"><i class="fas fa-check"></i> H : Hadir</span>
                <span class="badge badge-info px-2 py-1 mr-2"><i class="fas fa-file-alt"></i> I : Izin</span>
                <span class="badge badge-warning px-2 py-1 mr-2"><i class="fas fa-notes-medical"></i> S : Sakit</span>
                <span class="badge badge-danger px-2 py-1"><i class="fas fa-times"></i> - : Alpa / Tidak Hadir</span>
            </div>
        </div>
    </div>
</section>