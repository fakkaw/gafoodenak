<?php
// Simpan data customer ke cookie selama 30 hari
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    setcookie("customer_name", $name, time() + (86400 * 30), "/");
    setcookie("customer_phone", $phone, time() + (86400 * 30), "/");
    setcookie("customer_address", $address, time() + (86400 * 30), "/");
}

header("Location: index.php");
exit();
?>