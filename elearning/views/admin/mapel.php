<?php
$pageTitle = "Manajemen Mata Pelajaran - Admin";
require __DIR__ . "/../layouts/header.php";
require __DIR__ . "/../layouts/sidebar.php";
require __DIR__ . "/../layouts/navbar.php";
?>

<div class="card card-white">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="card-title">Mata Pelajaran Kurikulum Merdeka</div>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Struktur kurikulum nasional dan muatan lokal SMAN 2 Surabaya.</p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <span class="badge badge-blue" style="font-size:12px;padding:6px 12px;"><?= count($mapelList) ?> Mapel Terdaftar</span>
            <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                <i data-lucide="plus-circle" style="width:16px;"></i> + Tambah Mapel
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table" style="vertical-align:middle;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Kelompok</th>
                    <th>Deskripsi</th>
                    <th>Materi Aktif</th>
                    <th>Ujian CBT</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mapelList)): ?>
                    <tr>
                        <td colspan="8" style="text-align:center;padding:40px 20px;color:#64748b;">
                            Belum ada data mata pelajaran. Klik <b>+ Tambah Mapel</b> untuk menambahkan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($mapelList as $m): ?>
                        <tr>
                            <td style="font-weight:700;color:#64748b;"><?= $no++ ?></td>
                            <td><code><?= htmlspecialchars($m["kode_mapel"]) ?></code></td>
                            <td><b style="font-size:14px;color:#0f172a;"><?= htmlspecialchars($m["nama_mapel"]) ?></b></td>
                            <td><span class="badge badge-secondary"><?= htmlspecialchars($m["kelompok"]) ?></span></td>
                            <td style="font-size:12px;color:#64748b;max-width:280px;"><?= htmlspecialchars($m["deskripsi"] ?: "-") ?></td>
                            <td><span class="badge badge-emerald"><?= $m["total_materi"] ?> Materi</span></td>
                            <td><span class="badge badge-purple"><?= $m["total_ujian"] ?> Ujian</span></td>
                            <td style="text-align:center;white-space:nowrap;">
                                <div style="display:inline-flex;gap:6px;">
                                    <button type="button" class="btn btn-outline btn-sm" 
                                            onclick='openEditModal(<?= json_encode($m) ?>)' 
                                            style="padding:5px 10px;font-size:12px;">
                                        <i data-lucide="edit-3" style="width:13px;vertical-align:middle;"></i> Edit
                                    </button>
                                    <form action="index.php?page=admin_mapel" method="POST" onsubmit="return confirm('Hapus mata pelajaran ini?')" style="margin:0;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $m["id"] ?>">
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

<!-- Modal Tambah Mapel -->
<div class="modal-backdrop" id="modalCreate">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Tambah Mata Pelajaran Baru</div>
            <button type="button" onclick="closeModal('modalCreate')" style="background:none;border:none;cursor:pointer;color:#64748b;">
                <i data-lucide="x" style="width:18px;"></i>
            </button>
        </div>
        <form action="index.php?page=admin_mapel" method="POST">
            <input type="hidden" name="action" value="create">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Kode Mapel <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="kode_mapel" class="form-control" placeholder="Contoh: BIND, MTK, BIO" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Mata Pelajaran <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_mapel" class="form-control" placeholder="Contoh: Bahasa Indonesia" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelompok Mapel</label>
                    <select name="kelompok" class="form-control">
                        <option value="Umum / Wajib" selected>Umum / Wajib</option>
                        <option value="MIPA">MIPA (Matematika & Sains)</option>
                        <option value="IPS">IPS (Sosial & Humaniora)</option>
                        <option value="Bahasa & Budaya">Bahasa & Budaya</option>
                        <option value="Muatan Lokal">Muatan Lokal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi / Capaian Pembelajaran</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Ringkasan ruang lingkup mata pelajaran..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalCreate')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Mapel</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Mapel -->
<div class="modal-backdrop" id="modalEdit">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Edit Mata Pelajaran</div>
            <button type="button" onclick="closeModal('modalEdit')" style="background:none;border:none;cursor:pointer;color:#64748b;">
                <i data-lucide="x" style="width:18px;"></i>
            </button>
        </div>
        <form action="index.php?page=admin_mapel" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Kode Mapel <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="kode_mapel" id="edit_kode_mapel" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Mata Pelajaran <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_mapel" id="edit_nama_mapel" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelompok Mapel</label>
                    <select name="kelompok" id="edit_kelompok" class="form-control">
                        <option value="Umum / Wajib">Umum / Wajib</option>
                        <option value="MIPA">MIPA (Matematika & Sains)</option>
                        <option value="IPS">IPS (Sosial & Humaniora)</option>
                        <option value="Bahasa & Budaya">Bahasa & Budaya</option>
                        <option value="Muatan Lokal">Muatan Lokal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui Mapel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalCreate').classList.add('open');
}
function openEditModal(m) {
    document.getElementById('edit_id').value = m.id;
    document.getElementById('edit_kode_mapel').value = m.kode_mapel || '';
    document.getElementById('edit_nama_mapel').value = m.nama_mapel || '';
    document.getElementById('edit_kelompok').value = m.kelompok || 'Umum / Wajib';
    document.getElementById('edit_deskripsi').value = m.deskripsi || '';
    document.getElementById('modalEdit').classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
</script>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
