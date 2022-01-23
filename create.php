<?php

// CHECK SESSION
require_once "checkSession.php";

require_once "connProducts.php";

?>

<?php

    if (isset($_POST["submit"])) {

        

        function randomString($length) {

            $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $str = "";

            for ($i = 0; $i < $length; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $str .= $characters[$index];
            }

            return $str;

        }

        // echo "<pre>";
        // var_dump($_FILES);
        // echo "</pre>";

        $image = $_FILES['image'] ?? null;

        if (!is_dir("./images")) {

            mkdir("images");

        }

        // INSERT THIS TO TABLE COLUMN IF USER NOT INSERT A PICTURE
        $imagePath = '';

        if ($image["error"] !== 4) {

            $imagePath = "images/" . randomString(8) . "/" . $image["name"];
            
            mkdir(dirname($imagePath));

            move_uploaded_file($image['tmp_name'], $imagePath);

        }


        $title = ctype_space(trim($_POST['title'])) ? false : trim($_POST['title']);

        if (!$title) {
            header("Location: create.php");
            exit;
        }

        $description = trim($_POST['description']);
        $price = $_POST['price'];

        // INDONESIAN TIME
        date_default_timezone_set('Asia/Jakarta');  
        $createDate = date("Y-m-d H:i:s");

        $sql = "INSERT INTO products (image, title, description, price, create_date) VALUES ('$imagePath', '$title','$description', $price, '$createDate')";

        $url = "index.php";

        if ($conn -> query($sql) === true) {

            $_SESSION['newProduct'] = "$title berhasil ditambahkan";

            header('Location: ' . $url);
            exit;
        }

    }

    $conn -> close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Products CRUD</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
    <style>

        .arrow-btn {
            font-size: 30px;
        }

        #description {
            resize: none;
        }
 
        ul {
            /* float: right; */
            list-style: none;
        }

        .title-information {
            color: red;
        }

        #description {
            resize: none;
        }

    </style>
</head>

<body>

    <nav>
        <a href="index.php" class="btn btn-secondary"><span class="arrow-btn">&#8592;</span></a>
    </nav>

    <h1>Create New Product</h1>

    <div class="clear"></div>

    <form action="" method="post" id="form" enctype="multipart/form-data">

        <div class="form-group">
            <label for="image">Product Image</label>
            <br>
            <input type="file" id="image" name="image" accept="image/png, image/jpeg">
        </div>

        <div class="form-group">
            <label for="title">Product Title <span class='title-information'>(Jika anda hanya memasukkan spasi maka anda akan dikembalikan lagi kesini)</span></label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Product Description</label>
            <textarea id="description" name="description" class="form-control" rows="5"></textarea>
        </div>

        <label for="price">Product Price</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">$</span>
            </div>
            <input type="number" class="form-control" step="1" min="0" name="price" id="price" value="<?php echo $products['price'] ?>" required aria-label="Amount (to the nearest dollar)">
        </div>

        <input type="submit" id="submitBtn" name="submit" value="Submit" class="btn btn-primary">
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
