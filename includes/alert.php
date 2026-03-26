<?php
function swal_redirect($title, $message, $type, $redirect_url) {
    echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <style>body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #eef5ff, #dbeafe); height: 100vh; margin: 0; }</style>
</head>
<body>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '$type',
            title: '$title',
            text: '$message',
            confirmButtonColor: '#0d6efd',
            allowOutsideClick: false,
            backdrop: 'rgba(0,0,0,0.4)'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '$redirect_url';
            }
        });
    });
    </script>
</body>
</html>";
    exit();
}
?>
