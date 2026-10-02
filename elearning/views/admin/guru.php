<?php
$pageTitle = "Manajemen Data Guru - Admin";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/sidebar.php";
require __DIR__ . "/../layouts/navbar.php";
?>

<div class="card card-white">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="card-title">Daftar Guru / Pegawai Pendidik</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Akun guru dapat membuat materi, soal CBT, modul ajar AI, dan menilai tugas siswa.</p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <span class="badge badge-blue" style="font-size:12px;padding:6px 12px;"><?= $totalGuru ?> Guru Terdaftar</span>
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i data-lucide="plus-circle" style="width:16px;"></i> + Tambah Guru
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table" style="vertical-align:middle;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP / Username</th>
                    <th>Nama Lengkap Guru</th>
                    <th>No. Telepon</th>
                    <th>Terakhir Login</th>
                    <th>Status Akun</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($guruList)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px 20px;color:#64748b;">
                            Belum ada akun guru terdaftar.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = (($page - 1) * $perPage) + 1; foreach ($guruList as $g): ?>
                        <tr>
                            <td style="font-weight:700;color:#64748b;"><?= $no++ ?></td>
                            <td><code><?= htmlspecialchars($g["nip"]) ?></code></td>
                            <td>
                                <b style="font-size:14px;color:#0f172a;"><?= htmlspecialchars($g["nama"]) ?></b>
                                <div style="font-size:11px;color:#64748b;">Mengajar <?= $g["total_mengajar"] ?> Kelas/Mapel</div>
                            </td>
                            <td><?= htmlspecialchars($g["nomor_telepon"] ?: "-") ?></td>
                            <td style="font-size:12px;color:#64748b;"><?= Helper::timeAgo($g["last_login"]) ?></td>
                            <td><span class="badge badge-emerald">Aktif</span></td>
                            <td style="text-align:center;white-space:nowrap;">
                                <div style="display:inline-flex;gap:6px;">
                                    <button type="button" class="btn btn-outline btn-sm" 
                                            onclick='openEditModal(<?= json_encode($g) ?>)' 
                                            style="padding:5px 10px;font-size:12px;">
                                        <i data-lucide="edit-3" style="width:13px;vertical-align:middle;"></i> Edit
                                    </button>
                                    <form action="index.php?page=admin_guru" method="POST" onsubmit="return confirm('Hapus akun guru ini beserta penugasannya?')" style="margin:0;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?= $g["id"] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:5px 9px;">
                                            <i data-lucide="trash-2" style="width:13px;vertical-align:middle;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Component -->
    <?= Helper::renderPagination($totalGuru, $perPage, $page, 'admin_guru') ?>
</div>

<!-- Modal Tambah Guru -->
<div class="modal-backdrop" id="modalCreate">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Tambah Guru / Pendidik Baru</div>
            <button type="button" onclick="closeModal('modalCreate')" style="background:none;border:none;cursor:pointer;color:#64748b;">
                <i data-lucide="x" style="width:18px;"></i>
            </button>
        </div>
        <form action="index.php?page=admin_guru" method="POST">
            <input type="hidden" name="action" value="create">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">NIP Guru (Username Login) <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nip" class="form-control" placeholder="Contoh: 197508151999031002" required>
                    <small style="color:#64748b;font-size:11px;">Gunakan 18 digit NIP resmi sekolah.</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap & Gelar <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Drs. Bambang Wijaya, M.Pd." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Login <span style="color:#ef4444;">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika default sama dengan NIP">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="nomor_telepon" class="form-control" placeholder="Contoh: 081234567890">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalCreate')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Akun Guru</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Guru -->
<div class="modal-backdrop" id="modalEdit">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Edit Data Guru</div>
            <button type="button" onclick="closeModal('modalEdit')" style="background:none;border:none;cursor:pointer;color:#64748b;">
                <i data-lucide="x" style="width:18px;"></i>
            </button>
        </div>
        <form action="index.php?page=admin_guru" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="user_id" id="edit_user_id">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">NIP (Username Login)</label>
                    <input type="text" id="edit_nip" class="form-control" readonly style="background:#f1f5f9;">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap & Gelar <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama" id="edit_nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ganti Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control" placeholder="Isi password baru untuk reset">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="nomor_telepon" id="edit_nomor_telepon" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui Guru</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalCreate').classList.add('open');
}
function openEditModal(g) {
    document.getElementById('edit_user_id').value = g.id;
    document.getElementById('edit_nip').value = g.nip || '';
    document.getElementById('edit_nama').value = g.nama || '';
    document.getElementById('edit_nomor_telepon').value = g.nomor_telepon || '';
    document.getElementById('modalEdit').classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
</script>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
