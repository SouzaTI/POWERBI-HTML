<?php
session_start();
session_destroy();
if (isset($_GET['redirect']) && $_GET['redirect'] === 'login') {
    header("Location: login.php");
} else {
    header("Location: index.php");
}
exit();
?>