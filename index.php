<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <link rel="stylesheet" href="app.css">
  <style>
    html {
      scroll-behavior: smooth;
    }

    .clear {
      clear: both;
    }

    .add-btn {
      float: right;
      margin-right: 70px;
    }

    .description {
      width: 250px;
    }

  </style>
  <title>Products CRUD</title>
</head>

<body>

  <h1>Products CRUD</h1>

  <p>
    <a href="create.php" class="btn btn-success btn-lg add-btn">Add</a>
  </p>

  <br>

  <form method="" action="" class="mt-5">
    <div class="input-group mb-3">
      <input type="search" class="form-control" id="search" placeholder="Search for Products" name="search" onkeyup="handleSearchBtn()" value="<?php 
      if (isset($_GET["search"])) { echo $_GET["search"]; } ?>">

      <div class="input-group-append">
        <button class="btn btn-outline-danger" type="button" id="refresh">Refresh</button>
      </div>

      <div class="input-group-append">
        <button class="btn btn-outline-success" type="submit" id="submit-search" name="submit" value="yes"
        <?php if (!isset($_GET["search"]) || $_GET["search"] === "") { echo "disabled"; } ?>>Search</button>
      </div>

    </div>
  </form>

  


  <div class="clear"></div>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">Description</th>
        <th scope="col">Image</th>
        <th scope="col">Price</th>
        <th scope="col">Create Date</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php

      require_once "conn.php";


      // DISPLAYING PRODUCT KETIKA HALAMAN PERTAMA KALI DILOAD DAN KETIKA REFRESH BUTTON DIPENCET
      if ((!isset($_GET["submit"]) && !isset($_GET["search"])) || isset($_GET["refresh"])):


        $sql = "select id, title, description, price, image, DATE_FORMAT(create_date, '%d/%m/%Y %H:%i:%S') from products order by create_date desc";

      endif;

      // DISPLAYING PRODUCT WHEN GLOBAL VAR 'search' EMPTY BUT GLOBAL VAR 'submit' NOT EMPTY
      if (isset($_GET["search"]) && $_GET["search"] !== "" && isset($_GET["submit"])):


        $sql = "select id, title, description, price, image, DATE_FORMAT(create_date, '%d/%m/%Y %H:%i:%S') from products order by create_date desc";

      endif;


      // SEARCH for PRODUCT
      if (isset($_GET["submit"]) && $_GET["search"] !== ""):


        $searchQuery = $_GET["search"];

        $sql = "select id, title, description, price, image, DATE_FORMAT(create_date, '%d/%m/%Y %H:%i:%S') from products where title like '$searchQuery%' order by create_date desc";

      endif;

      // JIKA SEARCH ADA TAPI STRING KOSONG TAPI SUBMIT SEARCH ADA DAN TIDAK NULL MAKA NOTHING IS DISPLAYED
      if (isset($_GET["search"]) && $_GET["search"] === '' && isset($_GET["submit"])) {

        $sql = "select id from products where id = 0";

      }

      ?>


      <!-- QUERY TO DATABASE -->
      <?php

        // QUERY FOR DISPLAYING PRODUCT

        $result = $conn->query($sql);

        // QUERY UNTUK JUMLAH SEMUA DATA

        $sql = "select id from products";

        $rowCount = $conn->query($sql) -> num_rows;

        // UNTUK JUMLAH DATA YANG DITAMPILKAN DAN NO
        $no = 0;

        while ($products = $result->fetch_array(MYSQLI_ASSOC)):

          $no++;

        ?>

          <tr>
          <th class='row'><?php echo $no ?></th>
          <td><?php echo $products["title"] ?></td>
          <td class='description'><?php echo $products["description"] ?></td>

        <!-- JIKA IMAGE ADA MAKA TAMPILKAN ELSE OUTPUT TABLE COLUMN KOSONG -->
         <?php
         
         if ($products["image"] !== "") {
            echo "<td><img src='{$products["image"]}' title='{$products["title"]}' width='100px'></td>";
         } else {
            echo "<td style='width=100px;' height='100px''></td>";
         }

        ?>

          <td>$<?php echo $products["price"] ?></td>
          <td><?php echo $products["DATE_FORMAT(create_date, '%d/%m/%Y %H:%i:%S')"] ?></td>
          <td>
          <a href='update.php?id=<?php echo $products["id"] ?>' class='btn btn-outline-primary'>Edit</a>
          <form action='delete.php' method='post' style='display: inline'>
            <input type='hidden' name='id' value='{$products["id"]}'>
            <input type='hidden' name='image_path' value='<?php $products["image"] ?>'>
            <input type='submit' class='btn btn-outline-danger' style='margin-left: 5px;' value='Delete'>
          </form>
          </td>
          </tr>

       <?php endwhile; ?>

        <!-- MENAMPILKAN JUMLAH PRODUCT YANG DITAMPILKAN -->
        <p>
          <?php echo "$no/$rowCount products ditampikan";?>
        </p>

      <?php

      // CLOSE CONNECTION
      $conn->close();

      ?>

    </tbody>
  </table>

  

  <script>

        // ACTIVATE and DISABLE SEARCH BUTTON
      function handleSearchBtn() {

          let searchField = document.getElementById("search");
          let searchSubmit = document.getElementById("submit-search");

          if (searchField.value === "") {
              searchSubmit.disabled = true;
          } else {
            searchSubmit.disabled = false;
          }

      }


      // WHEN REFRESH BUTTON IS PRESSED REDIRECT TO index.php AGAR URL PARAM TERHAPUS
      document.getElementById("refresh").onclick = function () {

        location = 'index.php';

      }

  </script>

</body>

</html>
