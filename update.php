<?php

// CHECK SESSION
require_once "checkSession.php";

require_once "connProducts.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$sql = "SELECT * FROM products WHERE id = $id";

$result = $conn -> query($sql);

$products = $result -> fetch_assoc();

if ($products === null) {
    header("Location: index.php");
    exit;
}


if (isset($_POST["submit"])) {

    $currentImagePath = $products["image"];

    // var_dump($currentImagePath);
    // exit;

    $image = $_FILES['image']["name"] !== "" ? $_FILES['image'] : $currentImagePath;

    $imagePathToMove = $currentImagePath;

    if (isset($image["name"])) {

        // KALO BELUM ADA FILE PATH GAMBAR DI DATABASE
        if ($currentImagePath === "") {

            function randomString($length)
            {

                $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $str = "";

                for ($i = 0; $i < $length; $i++) {
                    $index = rand(0, strlen($characters) - 1);
                    $str .= $characters[$index];
                }

                return $str;
            }

            $imagePathToMove = "images/" . randomString(8) . "/" . $image["name"];

            mkdir(dirname($imagePathToMove));

            move_uploaded_file($image['tmp_name'], $imagePathToMove);

        }

        // KALO UDAH ADA FILE PATH GAMBAR DI DATABASE
        else {

            unlink($currentImagePath);

            $imagePathToMove = dirname($currentImagePath);

            move_uploaded_file($image["tmp_name"], "$imagePathToMove/{$image["name"]}");

            $imagePathToMove = "$imagePathToMove/{$image["name"]}";

        }

    }


    $title = ctype_space(trim($_POST['title'])) ? false : trim($_POST['title']);

    if (!$title) {
        header("Location: update.php?id=$id");
        exit;
    }
    
    $description = $_POST['description'];
    $imageUpdate = $imagePathToMove;
    $price = $_POST['price'];


    $sql = "UPDATE products SET image = '$imageUpdate', title = '$title', description = '$description', price = $price WHERE id = $id";


    if ($conn->query($sql) === true) {


        $_SESSION['newChanges'] = "$title berhasil diubah";

        $url = "index.php";

        header('Location: ' . $url);
        exit;
    }

}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Products CRUD</title>
    <link rel="stylesheet" href="app.css">
    <style>
        
        .arrow-btn {
            font-size: 30px;
        }

        #description {
            resize: none;
        }

        .title-information {
            color: red;
        }

    </style>
</head>

<body>

    <nav>
        <a href="index.php" class="btn btn-secondary"><span class="arrow-btn">&#8592;</span></a>
    </nav>

    <h1>Update Product <strong><?php echo $products["title"] ?></strong></h1>

    <div class="clear"></div>

    <form action="" method="post" enctype="multipart/form-data">

        <?php echo "<img src='{$products['image']}' width='180px'>"; ?>

        <div class="form-group">
            <label for="image">Product Image</label>
            <br>
            <input type="file" id="image" name="image">
        </div>

        <div class="form-group">
            <label for="title">Product Title <span class='title-information'>(Jika anda hanya memasukkan spasi maka anda akan dikembalikan lagi kesini)</span></label>
            <input type="text" name="title" id="title" class="form-control" value="<?php echo $products['title'] ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Product Description</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?php echo $products['description'] ?></textarea>
        </div>

        <label for="price">Product Price</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">$</span>
            </div>
            <input type="number" class="form-control" step="1" min="0" name="price" id="price" value="<?php echo $products['price'] ?>" required aria-label="Amount (to the nearest dollar)">
        </div>

        <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
    </form>

    <script>

        // TURN OFF SPELLCHECK OF ALL INPUT FIELD
        // THERE ARE TWO DIFFERENT WAYS TO DO THAT

        let allInputField = document.getElementsByTagName("input");
        let textAreaField = document.getElementsByTagName("textarea");

        // for (let i = 0; i < allInputField.length; i++) {
        //     allInputField[i].spellcheck = false;
        // }

        for (let x in allInputField) {
            allInputField[x].spellcheck = false;
        }

        for (x in textAreaField) {
            textAreaField[x].spellcheck = false;
        }

        // END OF TURN OFF SPELLCHECK OF ALL INPUT FIELD

    </script>

</body>

</html>
