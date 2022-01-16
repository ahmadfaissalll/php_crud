<?php

require_once "conn.php";

$id = $_POST['id'] ?? null;
$imagePath = $_POST['image_path'];

if (!$id) {
    header("Location: " . "index.php");
    exit;
}

$sql = "delete from products where id = $id;";

$conn -> query($sql);

if ($imagePath !== "") {

    unlink($imagePath);

    rmdir(dirname($imagePath));

}

$conn -> close();

header("Location: " . "index.php");