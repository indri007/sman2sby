<?php
$pageTitle = "Manajemen Kelas - Admin";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/sidebar.php";
require __DIR__ . "/../layouts/navbar.php";
?>

<div class="card card-white">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="card-title">Manajemen Rombel / Kelas</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Atur rombongan belajar, tahun ajaran, dan penugasan wali kelas.</p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <span class="badge badge-purple" style="font-size:12px;padding:6px 12px;"><?= count($kelasList) ?> Kelas Terdaftar</span>
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i data-lucide="plus-circle" style="width:16px;"></i> + Tambah Kelas
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table" style="vertical-align:middle;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Kelas</th>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Tahun Ajaran</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kelasList)): ?>
                    <tr>
                        <td colspan="8" style="text-align:center;padding:40px 20px;color:#64748b;">
                            Belum ada data kelas. Klik <b>+ Tambah Kelas</b> untuk membuat rombel baru.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($kelasList as $c): ?>
                        <tr>
                            <td style="font-weight:700;color:#64748b;"><?= $no++ ?></td>
                            <td><code><?= htmlspecialchars($c["kode_kelas"]) ?></code></td>
                            <td><b style="font-size:14px;color:#0f172a;"><?= htmlspecialchars($c["nama_kelas"]) ?></b></td>
                            <td><span class="badge badge-secondary">Kelas <?= htmlspecialchars($c["tingkat"]) ?></span></td>
                            <td><?= htmlspecialchars($c["tahun_ajaran"]) ?></td>
                            <td><?= htmlspecialchars($c["nama_wali"] ?: "Belum Ditentukan") ?></td>
                            <td><span class="badge badge-blue"><?= $c["total_siswa"] ?> Siswa</span></td>
                            <td style="text-align:center;white-space:nowrap;">
                                <div style="display:inline-flex;gap:6px;">
                                    <button type="button" class="btn btn-outline btn-sm" 
                                            onclick='openEditModal(<?= json_encode($c) ?>)' 
                                            style="padding:5px 10px;font-size:12px;">
                                        <i data-lucide="edit-3" style="width:13px;vertical-align:middle;"></i> Edit
                                    </button>
                                    <form action="index.php?page=admin_kelas" method="POST" onsubmit="return confirm('Hapus kelas ini? Siswa yang terhubung akan dilepaskan dari rombel ini.')" style="margin:0;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $c["id"] ?>">
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
</div>

<!-- Modal Tambah Kelas -->
<div class="modal-backdrop" id="modalCreate">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Tambah Rombel / Kelas Baru</div>
            <button type="button" onclick="closeModal('modalCreate')" style="background:none;border:none;cursor:pointer;color:#64748b;">
                <i data-lucide="x" style="width:18px;"></i>
            </button>
        </div>
        <form action="index.php?page=admin_kelas" method="POST">
            <input type="hidden" name="action" value="create">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Kode Kelas <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="kode_kelas" class="form-control" placeholder="Contoh: X-1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Kelas <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: Sepuluh 1 (X-1)" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Tingkat</label>
                        <select name="tingkat" class="form-control">
                            <option value="10" selected>Kelas 10 (Fase E)</option>
                            <option value="11">Kelas 11 (Fase F)</option>
                            <option value="12">Kelas 12 (Fase F)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control" value="2024/2025" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Wali Kelas (Opsional)</label>
                    <select name="wali_user_id" class="form-control">
                        <option value="">-- Belum Ditentukan --</option>
                        <?php foreach ($guruList as $g): ?>
                            <option value="<?= $g["id"] ?>"><?= htmlspecialchars($g["nama"]) ?> (NIP: <?= htmlspecialchars($g["nip"]) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalCreate')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kelas -->
<div class="modal-backdrop" id="modalEdit">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Edit Rombel / Kelas</div>
            <button type="button" onclick="closeModal('modalEdit')" style="background:none;border:none;cursor:pointer;color:#64748b;">
                <i data-lucide="x" style="width:18px;"></i>
            </button>
        </div>
        <form action="index.php?page=admin_kelas" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Kode Kelas <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="kode_kelas" id="edit_kode_kelas" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Kelas <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_kelas" id="edit_nama_kelas" class="form-control" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Tingkat</label>
                        <select name="tingkat" id="edit_tingkat" class="form-control">
                            <option value="10">Kelas 10 (Fase E)</option>
                            <option value="11">Kelas 11 (Fase F)</option>
                            <option value="12">Kelas 12 (Fase F)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" id="edit_tahun_ajaran" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Wali Kelas</label>
                    <select name="wali_user_id" id="edit_wali_user_id" class="form-control">
                        <option value="">-- Belum Ditentukan --</option>
                        <?php foreach ($guruList as $g): ?>
                            <option value="<?= $g["id"] ?>"><?= htmlspecialchars($g["nama"]) ?> (NIP: <?= htmlspecialchars($g["nip"]) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui Kelas</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalCreate').classList.add('open');
}
function openEditModal(c) {
    document.getElementById('edit_id').value = c.id;
    document.getElementById('edit_kode_kelas').value = c.kode_kelas || '';
    document.getElementById('edit_nama_kelas').value = c.nama_kelas || '';
    document.getElementById('edit_tingkat').value = c.tingkat || '10';
    document.getElementById('edit_tahun_ajaran').value = c.tahun_ajaran || '2024/2025';
    document.getElementById('edit_wali_user_id').value = c.wali_user_id || '';
    document.getElementById('modalEdit').classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
</script>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
