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

      // if (isset($_GET["submit"])) {
      //   echo "<pre>";
      //   var_dump($_GET["search"]);
      //   echo "</pre>";
      // }
      // exit;


      // DISPLAYING PRODUCT

      if ((!isset($_GET["submit"]) && !isset($_GET["search"])) || isset($_GET["refresh"])) {


        $sql = "select id, title, description, price, image, DATE_FORMAT(create_date, '%d/%m/%Y %H:%i:%S') from products order by create_date desc";

        $result = $conn->query($sql);

        $no = 0;

        while ($products = $result->fetch_array(MYSQLI_ASSOC)) {

          $no++;

          echo "<tr>";
          echo "<th class='row'>{$no}</th>";
          echo "<td>{$products["title"]}</td>";
          echo "<td class='description'>{$products["description"]}</td>";

          if ($products["image"] !== "") {
            echo "<td><img src='{$products["image"]}' title='{$products["title"]}' width='100px'></td>";
          } else {
            echo "<td style='width='100px'; height='100px''></td>";
          }

          echo "<td>\${$products["price"]}</td>";
          echo "<td>{$products["DATE_FORMAT(create_date, '%d/%m/%Y %H:%i:%S')"]}</td>";
          echo "<td>";
          echo "<a href='update.php?id={$products["id"]}' class='btn btn-outline-primary'>Edit</a>";
          echo "<form action='delete.php' method='post' style='display: inline-block;'>";
            echo "<input type='hidden' name='id' value='{$products["id"]}'>";
            echo "<input type='hidden' name='image_path' value='{$products["image"]}'>";
            echo "<input type='submit' class='btn btn-outline-danger' style='margin-left: 5px;' value='Delete'>";
          echo "</form>";
          echo "</td>";
          echo "</tr>";
        }

        // MENAMPILKAN JUMLAH PRODUCT YANG DITAMPILKAN
        echo "<p>";
        echo "$no/$no products ditampikan";
        echo "</p>";

        
      }



      // SEARCH for PRODUCT

      // HARD LOGIC
      // if (isset($_GET["submit"]) && ($_GET["search"] !== "" || ($_GET["search"] === "" && !isset($_GET["submit"])))) {

      // SIMPLE LOGIC
      // isset($_GET["submit"]) && $_GET["search"] !== ""

      if (isset($_GET["submit"]) && $_GET["search"] !== "") {


        $searchQuery = $_GET["search"];

        $sql = "select id, title, description, price, image, DATE_FORMAT(create_date, '%d/%m/%Y %H:%i:%S') from products where title like '$searchQuery%' order by create_date desc";

        $result = $conn->query($sql);

        $index = 0;

        // DISPLAY SEARCH RESULT
        while ($products = $result->fetch_array(MYSQLI_ASSOC)) {

          $index++;

          echo "<tr>";
          echo "<th class='row'>{$index}</th>";
          echo "<td>{$products["title"]}</td>";
          echo "<td class='description'>{$products["description"]}</td>";

          if ($products["image"] !== "") {
            echo "<td><img src='{$products["image"]}' width='100px'></td>";
          } else {
            echo "<td style='width='100px'; height='100px''></td>";
          }

          echo "<td>\${$products["price"]}</td>";
          echo "<td>{$products["DATE_FORMAT(create_date, '%d/%m/%Y %H:%i:%S')"]}</td>";
          echo "<td>";
          echo "<a href='update.php?id={$products["id"]}' class='btn btn-outline-primary'>Edit</a>";
          echo "<form action='delete.php' method='post' style='display: inline-block;'>";
            echo "<input type='hidden' name='id' value='{$products["id"]}'>";
            echo "<input type='submit' class='btn btn-outline-danger' style='margin-left: 5px;' value='Delete'>";
          echo "</form>";
          echo "</td>";
          echo "</tr>";
        }


        // MENAMPILKAN JUMLAH DATA YANG DITEMUKAN

        $sql = "select id from products";

        $result = $conn -> query($sql) -> num_rows;

        $rowCount = strval($result);

        echo "<p>";
        echo "$index/$rowCount products ditampikan";
        echo "</p>";

      }
      


      // REFRESH PAGE

      // if (isset($_GET["refresh"])) {

      //   $current_url = $_SERVER["REQUEST_URI"];
        
      //   $current_url = explode('?', $current_url);
      //   header("Location: " . $current_url[0]);

      // }

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