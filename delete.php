<?php

// CHECK SESSION
require_once "checkSession.php";

require_once "connProducts.php";

$id = $_POST['id'];
$imagePath = $_POST['image_path'];
$requestUrl = $_POST["request_url"];

if (!$id) {
    header("Location: " . "index.php");
    exit;
}

// var_dump($conn -> query($sql));

$sql = "SELECT title FROM products WHERE id = $id";

$result = $conn->query($sql);

$products = $result->fetch_assoc();

// JIKA PRODUK TIDAK ADA MAKA KELUAR DARI PROGRAM
if ($products === null) {
    exit;
}


$sql = "DELETE FROM products WHERE id = $id;";

$conn -> query($sql);

if ($conn -> affected_rows === 1) {
    $_SESSION['deleteSuccess'] = "{$products['title']} Berhasil Dihapus";
} else {
    $_SESSION['deleteFail'] = "{$products['title']} Gagal Dihapus";
}


if ($imagePath !== "") {

    unlink($imagePath);

    rmdir(dirname($imagePath));

}

$conn -> close();

header("Location: " . $requestUrl);

?>
