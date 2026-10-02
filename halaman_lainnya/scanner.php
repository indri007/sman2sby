<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12 text-center">
                <h1>Scanner</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div id="scanner" style="width: 100%; height: 100%;" class="<?= isset($_GET['id']) ? 'd-none' : ''; ?>">
        <div class="div mb-3">
            <label for="">Kamera</label>
            <select id="camera" class="form-control">
                <option value="" disabled selected>Pilih Kamera Lainnya</option>
            </select>
        </div>
        <video style="width: 100%; height: 100%;"></video>
    </div>
</section>
<script type="module">
    import QrScanner from './assets/plugins/qr-scanner/qr-scanner.min.js';

    const setResult = async (result) => {
        const data = JSON.parse(result.data);
        console.log(data);
        scanner.stop();
        const response = await fetch("halaman_tambah_data/presensi.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id_pegawai: data.id
            })
        }).then(response => response.json());
        if (response.isSuccess) {
            (new Audio('assets/audio/barcode-scanner-beep-sound.mp3')).play();
            setTimeout(() => scanner.start(), 3000);
            // alert('Penjualan Berhasil');
        }

    }

    const scanner = new QrScanner(document.querySelector('video'), result => setResult(result), {
        highlightScanRegion: true,
        highlightCodeOutline: true,
    });
    scanner.start();

    (async () => {
        const selectCamera = document.getElementById('camera');
        for (const camera of await QrScanner.listCameras(true)) {
            const option = document.createElement('option');
            option.value = camera.id;
            option.innerText = camera.label;
            selectCamera.append(option);
        }
        selectCamera.addEventListener('change', function() {
            scanner.setCamera(this.value);
        });
    })();
</script>