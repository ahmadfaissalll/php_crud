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

        require_once "conn.php";

        $image = $_FILES['image'] ?? null;

        if (!is_dir("./images")) {

            mkdir("images");

        }

        // echo "<pre>";
        // var_dump($image);
        // echo "</pre>";
        // exit;

        // INSERT THIS TO TABLE COLUMN IF USER NOT INSERT A PICTURE
        $imagePath = '';

        if ($image["error"] !== 4) {

            $imagePath = "images/" . randomString(8) . "/" . $image["name"];
            
            mkdir(dirname($imagePath));

            move_uploaded_file($image['tmp_name'], $imagePath);

        }

        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        // INDONESIAN TIME
        date_default_timezone_set('Asia/Jakarta');  
        $createDate = date("Y-m-d H:i:s");

        $sql = "INSERT INTO products (image, title, description, price, create_date) VALUES ('$imagePath', '$title','$description', $price, '$createDate')";

        $url = "index.php";

        if ($conn -> query($sql) === true) {
            header('Location: ' . $url);
        }

        $conn -> close();

    }

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

        .clear {
            clear: both;
        }

        #description {
            resize: none;
        }
 
        ul {
            /* float: right; */
            list-style: none;
        }

    </style>
</head>

<body>

    <nav>
        <a href="index.php" class="btn btn-lg btn-secondary">Home</a>
    </nav>

    <h1>Create New Product</h1>

    <div class="clear"></div>

    <form action="" method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label for="image">Product Image</label>
            <br>
            <input type="file" id="image" name="image">
        </div>

        <div class="form-group">
            <label for="title">Product Title</label>
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

        <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
    </form>

</body>

</html>