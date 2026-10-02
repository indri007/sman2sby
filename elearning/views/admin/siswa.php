<?php
$pageTitle = "Manajemen Data Siswa - Admin";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/sidebar.php";
require __DIR__ . "/../layouts/navbar.php";
?>

<div class="card card-white">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="card-title">Daftar Siswa Kelas 10</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Kelola akun siswa, penempatan rombel kelas, dan reset password login.</p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <select class="form-control" style="width:auto;font-size:13px;" onchange="location.href='index.php?page=admin_siswa&class_id='+this.value">
                <option value="">-- Semua Rombel Kelas --</option>
                <?php foreach ($kelasList as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($classId ?? '') == $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nama_kelas']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="badge badge-emerald" style="font-size:12px;padding:6px 12px;"><?= $totalSiswa ?> Siswa Terdaftar</span>
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i data-lucide="plus-circle" style="width:16px;"></i> + Tambah Siswa
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table" style="vertical-align:middle;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN / Username</th>
                    <th>Nama Lengkap Siswa</th>
                    <th>Kelas Rombel</th>
                    <th>Tempat Lahir</th>
                    <th>Terakhir Login</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($siswaList)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px 20px;color:#64748b;">
                            Belum ada data siswa<?= $classId ? " di kelas ini" : "" ?>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = (($page - 1) * $perPage) + 1; foreach ($siswaList as $s): ?>
                        <tr>
                            <td style="font-weight:700;color:#64748b;"><?= $no++ ?></td>
                            <td><code><?= htmlspecialchars($s["nisn"]) ?></code></td>
                            <td><b style="font-size:14px;color:#0f172a;"><?= htmlspecialchars($s["nama"]) ?></b></td>
                            <td>
                                <span class="badge badge-purple"><?= htmlspecialchars($s["nama_kelas"] ?: "Belum Ada Kelas") ?></span>
                            </td>
                            <td><?= htmlspecialchars($s["tempat_lahir"] ?: "-") ?></td>
                            <td style="font-size:12px;color:#64748b;"><?= Helper::timeAgo($s["last_login"]) ?></td>
                            <td style="text-align:center;white-space:nowrap;">
                                <div style="display:inline-flex;gap:6px;">
                                    <button type="button" class="btn btn-outline btn-sm" 
                                            onclick='openEditModal(<?= json_encode($s) ?>)' 
                                            style="padding:5px 10px;font-size:12px;">
                                        <i data-lucide="edit-3" style="width:13px;vertical-align:middle;"></i> Edit
                                    </button>
                                    <form action="index.php?page=admin_siswa" method="POST" onsubmit="return confirm('Hapus akun siswa ini dari sistem?')" style="margin:0;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?= $s["id"] ?>">
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
    <?= Helper::renderPagination($totalSiswa, $perPage, $page, 'admin_siswa', ['class_id' => $classId ?? '']) ?>
</div>

<!-- Modal Tambah Siswa -->
<div class="modal-backdrop" id="modalCreate">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Tambah Akun Siswa Baru</div>
            <button type="button" onclick="closeModal('modalCreate')" style="background:none;border:none;cursor:pointer;color:#64748b;">
                <i data-lucide="x" style="width:18px;"></i>
            </button>
        </div>
        <form action="index.php?page=admin_siswa" method="POST">
            <input type="hidden" name="action" value="create">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">NISN Siswa (Username Login) <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nisn" class="form-control" placeholder="Contoh: 0103128173" required>
                    <small style="color:#64748b;font-size:11px;">Gunakan 10 digit NISN resmi siswa.</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Siswa <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Muhammad Budi Santoso" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Kelas Rombel <span style="color:#ef4444;">*</span></label>
                        <select name="class_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelasList as $c): ?>
                                <option value="<?= $c["id"] ?>"><?= htmlspecialchars($c["nama_kelas"]) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Login</label>
                        <input type="password" name="password" class="form-control" placeholder="Default sama dengan NISN">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" placeholder="Contoh: Surabaya">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon / WA</label>
                        <input type="text" name="nomor_telepon" class="form-control" placeholder="Contoh: 081987654321">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalCreate')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Siswa</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div class="modal-backdrop" id="modalEdit">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Edit Data Siswa</div>
            <button type="button" onclick="closeModal('modalEdit')" style="background:none;border:none;cursor:pointer;color:#64748b;">
                <i data-lucide="x" style="width:18px;"></i>
            </button>
        </div>
        <form action="index.php?page=admin_siswa" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="user_id" id="edit_user_id">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">NISN (Username Login)</label>
                    <input type="text" id="edit_nisn" class="form-control" readonly style="background:#f1f5f9;">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Siswa <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama" id="edit_nama" class="form-control" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Pindah Rombel Kelas</label>
                        <select name="class_id" id="edit_class_id" class="form-control">
                            <option value="">-- Tanpa Kelas --</option>
                            <?php foreach ($kelasList as $c): ?>
                                <option value="<?= $c["id"] ?>"><?= htmlspecialchars($c["nama_kelas"]) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reset Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ubah">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="edit_tempat_lahir" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="nomor_telepon" id="edit_nomor_telepon" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui Siswa</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalCreate').classList.add('open');
}
function openEditModal(s) {
    document.getElementById('edit_user_id').value = s.id;
    document.getElementById('edit_nisn').value = s.nisn || '';
    document.getElementById('edit_nama').value = s.nama || '';
    document.getElementById('edit_class_id').value = s.class_id || '';
    document.getElementById('edit_tempat_lahir').value = s.tempat_lahir || '';
    document.getElementById('edit_nomor_telepon').value = s.nomor_telepon || '';
    document.getElementById('modalEdit').classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
</script>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
