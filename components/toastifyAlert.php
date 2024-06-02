<?php
function showToast($message, $type = 'success')
{
    echo "<script>
            Toastify({
                text: '$message',
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: 'top',
                position: 'center',
                style: {
                    background: '$type',
                },
                stopOnFocus: true,
            }).showToast();
        </script>";
}

if (isset($_SESSION['alert_message'])) {
    $alert_message = isset($_SESSION['alert_message']) ? $_SESSION['alert_message'] : '';
    $alert_type = isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : 'info';
    $alert_pass = isset($_SESSION['alert_pass']) ? $_SESSION['alert_pass'] : '';

    $document_root = $_SERVER['DOCUMENT_ROOT'];
    $php_self = $_SERVER['PHP_SELF'];
    $web_path = str_replace($document_root, '', $php_self);

    $cleaned_alert_message = str_replace(["'", '"'], '', $alert_message);

    if ($alert_pass && ($_SERVER['SCRIPT_NAME'] == $location_admin . '/index.php' || $_SERVER['SCRIPT_NAME'] == $location_employee . '/index.php')) {
        showToast($cleaned_alert_message, $alert_type);
        unset($_SESSION['alert_message']);
        unset($_SESSION['alert_type']);
        unset($_SESSION['alert_pass']);
    } else if (!$alert_pass) {
        showToast($cleaned_alert_message, $alert_type);
        unset($_SESSION['alert_message']);
        unset($_SESSION['alert_type']);
    }
}

?>