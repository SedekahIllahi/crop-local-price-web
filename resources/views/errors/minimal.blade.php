<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('code') | @yield('title')</title>
    <meta name="robots" content="noindex, nofollow">
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/admin/img/favicon.png" rel="icon">
    <link href="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/admin/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="https://bootstrapmade.com/assets/admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://bootstrapmade.com/assets/admin/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="https://bootstrapmade.com/assets/admin/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="https://bootstrapmade.com/assets/admin/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="https://bootstrapmade.com/assets/admin/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="https://bootstrapmade.com/assets/admin/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="https://bootstrapmade.com/assets/admin/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/admin/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <main>
        <div class="container">

            <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                <h1>@yield('code')</h1>
                <h2>@yield('title').</h2>
                <a class="btn" href="/">Back to home</a>
                <img src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/admin/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found">
                <div class="credits">
                    <!-- All the links in the footer should remain intact. -->
                    <!-- You can delete the links only if you purchased the pro version. -->
                    <!-- Licensing information: https://bootstrapmade.com/license/ -->
                    <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                    Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                </div>
            </section>

        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="https://bootstrapmade.com/assets/admin/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="https://bootstrapmade.com/assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://bootstrapmade.com/assets/admin/vendor/chart.js/chart.umd.js"></script>
    <script src="https://bootstrapmade.com/assets/admin/vendor/echarts/echarts.min.js"></script>
    <script src="https://bootstrapmade.com/assets/admin/vendor/quill/quill.min.js"></script>
    <script src="https://bootstrapmade.com/assets/admin/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="https://bootstrapmade.com/assets/admin/vendor/tinymce/tinymce.min.js"></script>
    <script src="https://bootstrapmade.com/assets/admin/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/admin/js/main.js"></script>

    <script async src='https://www.googletagmanager.com/gtag/js?id=G-P7JSYB1CSP'></script>
    <script>if (window.self == window.top) { window.dataLayer = window.dataLayer || []; function gtag() { dataLayer.push(arguments); } gtag('js', new Date()); gtag('config', 'G-P7JSYB1CSP'); } </script>
    <script defer
        src="https://static.cloudflareinsights.com/beacon.min.js/v84a3a4012de94ce1a686ba8c167c359c1696973893317"
        integrity="sha512-euoFGowhlaLqXsPWQ48qSkBSCFs3DPRyiwVu3FjR96cMPx+Fr+gpWRhIafcHwqwCqWS42RZhIudOvEI+Ckf6MA=="
        data-cf-beacon='{"rayId":"85d77032cba3819c","version":"2024.2.1","token":"68c5ca450bae485a842ff76066d69420"}'
        crossorigin="anonymous"></script>
</body>

</html>