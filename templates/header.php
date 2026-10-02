<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aplikasi Siswa</title>
  <link rel="icon" type="image/x-icon" href="assets/img/logo.png">

  <!-- Google Font: Source Sans Pro -->
  <style>
    /* cyrillic-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: italic;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7qsDJT9g.woff2') format('woff2');
      unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }

    /* cyrillic */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: italic;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7jsDJT9g.woff2') format('woff2');
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }

    /* greek-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: italic;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7rsDJT9g.woff2') format('woff2');
      unicode-range: U+1F00-1FFF;
    }

    /* greek */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: italic;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7ksDJT9g.woff2') format('woff2');
      unicode-range: U+0370-03FF;
    }

    /* vietnamese */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: italic;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7osDJT9g.woff2') format('woff2');
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }

    /* latin-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: italic;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7psDJT9g.woff2') format('woff2');
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }

    /* latin */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: italic;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7nsDI.woff2') format('woff2');
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }

    /* cyrillic-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 300;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwmhduz8A.woff2') format('woff2');
      unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }

    /* cyrillic */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 300;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwkxduz8A.woff2') format('woff2');
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }

    /* greek-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 300;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwmxduz8A.woff2') format('woff2');
      unicode-range: U+1F00-1FFF;
    }

    /* greek */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 300;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwlBduz8A.woff2') format('woff2');
      unicode-range: U+0370-03FF;
    }

    /* vietnamese */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 300;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwmBduz8A.woff2') format('woff2');
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }

    /* latin-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 300;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwmRduz8A.woff2') format('woff2');
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }

    /* latin */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 300;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwlxdu.woff2') format('woff2');
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }

    /* cyrillic-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK3dSBYKcSV-LCoeQqfX1RYOo3qNa7lqDY.woff2') format('woff2');
      unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }

    /* cyrillic */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK3dSBYKcSV-LCoeQqfX1RYOo3qPK7lqDY.woff2') format('woff2');
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }

    /* greek-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK3dSBYKcSV-LCoeQqfX1RYOo3qNK7lqDY.woff2') format('woff2');
      unicode-range: U+1F00-1FFF;
    }

    /* greek */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK3dSBYKcSV-LCoeQqfX1RYOo3qO67lqDY.woff2') format('woff2');
      unicode-range: U+0370-03FF;
    }

    /* vietnamese */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK3dSBYKcSV-LCoeQqfX1RYOo3qN67lqDY.woff2') format('woff2');
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }

    /* latin-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK3dSBYKcSV-LCoeQqfX1RYOo3qNq7lqDY.woff2') format('woff2');
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }

    /* latin */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 400;
      font-display: fallback;
      src: url('assets/font/6xK3dSBYKcSV-LCoeQqfX1RYOo3qOK7l.woff2') format('woff2');
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }

    /* cyrillic-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 700;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwmhduz8A.woff2') format('woff2');
      unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }

    /* cyrillic */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 700;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwkxduz8A.woff2') format('woff2');
      unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }

    /* greek-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 700;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwmxduz8A.woff2') format('woff2');
      unicode-range: U+1F00-1FFF;
    }

    /* greek */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 700;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwlBduz8A.woff2') format('woff2');
      unicode-range: U+0370-03FF;
    }

    /* vietnamese */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 700;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwmBduz8A.woff2') format('woff2');
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }

    /* latin-ext */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 700;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwmRduz8A.woff2') format('woff2');
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }

    /* latin */
    @font-face {
      font-family: 'Source Sans Pro';
      font-style: normal;
      font-weight: 700;
      font-display: fallback;
      src: url('assets/font/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwlxdu.woff2') format('woff2');
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
  </style>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/css/adminlte.min.css">

  <!-- jQuery -->
  <script src="assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables -->
  <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="assets/plugins/jszip/jszip.min.js"></script>
  <script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

  <!-- Select2 -->
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <script src="assets/plugins/select2/js/select2.full.min.js"></script>
  <style>
    .td-fit {
      width: 1%;
      white-space: nowrap;
    }

    .btn-primary {
      background-color: #00640E !important;
      border: #00640E 1px solid;
    }

    .btn-primary:hover {
      background-color: #00500b !important;
      border: #00500b 1px solid;
    }

    .page-item.active .page-link {
      color: #fff !important;
      background-color: #00640E !important;
      border-color: #00640E !important;
    }

    .page-link {
      color: #00640E !important;
      background-color: #fff !important;
      border: 1px solid #dee2e6 !important;
    }

    .page-link:hover {
      color: #fff !important;
      background-color: #00640E !important;
      border-color: #00640E !important;
    }
  </style>

  <script src="assets/js/iframe-download.js"></script>
  <script src="utils/utils.js"></script>

  <!-- QR CODE Generator -->
  <script type="text/javascript" src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body class="hold-transition sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">