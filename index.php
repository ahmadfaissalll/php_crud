<?php

// CHECK SESSION
require_once "checkSession.php";

require_once "connProducts.php";


if (isset($_POST['logout'])) {

  // CLEAR SESSION

  $_SESSION = [];
  session_unset();
  session_destroy();

  // KICK USER TO LOGIN PAGE

  $url = '../login_system/login.php';

  header("Location: " . $url);
  exit;
}

?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <!-- Icon title -->
  <!-- <link rel="icon" type="image/x-icon" href="images/0WmCFQZo/Oppo Reno 6 Pro 5G.jpg"> -->
  <!-- <link rel="shortcut icon" type="image/x-icon" href="images/0WmCFQZo/Oppo Reno 6 Pro 5G.jpg"> -->
  <link rel="stylesheet" href="app.css">
  <style>
    html {
      scroll-behavior: smooth;
    }

    .clear {
      clear: both;
    }

    .alert-style {
      position: absolute;
      top: 20px;
      width: 92%;
      z-index: 1;
    }

    .close {
      position: absolute;
      right: 30px;
      top: 1px;
      font-size: 40px;
      font-weight: bold;
      color: #b2b0b0;
    }

    .close:hover,
    .close:focus {
      color: #f44336;
      cursor: pointer;
    }

    .profile {
      float: right;
      /* margin-right: 0px; */
    }

    .dropdown-content {
      display: none;
      position: absolute;
      top: 60px;
      right: 5px;
    }

    .profile-img {
      width: 60px;
    }

    .profile:hover,
    .profile-caption:hover {
      cursor: pointer;
    }

    .dropdown-icon {
      width: 37px;
    }

    .profile:hover .dropdown-icon {
      filter: brightness(60%);
    }

    .form {
      display: inline;
    }

    .logout-btn {
      /* display: ; */
      float: right;
      /* margin-top: 1px; */
      margin-right: 20px;
    }

    .add-btn {
      float: right;
      /* margin-right: 20px; */
      /* padding: 3px 12px; */
    }

    .add-btn:hover {
      text-decoration: none;
    }

    .add-btn:focus {
      outline: none;
    }

    .add-logo-btn {
      font-size: 30px;
    }

    .description {
      width: 250px;
    }

    #image:hover {
      cursor: pointer;
      filter: brightness(90%);
    }
  </style>
  <title>Products CRUD</title>
</head>

<body>

  <!-- ALERT NOTIFICATION -->
  <?php

    if (isset($_SESSION['newUser'])) {

      echo "<div class='alert alert-success alert-style' id='close'>{$_SESSION['newUser']}</div>";
      unset($_SESSION['newUser']);

      // CLOSE ALERT AFTER 2 SECOND
      echo "<script>setTimeout(function() {document.getElementById('close').style.display = 'none';}, 2000)</script>";
    }

    if (isset($_SESSION['newProduct'])) {

      echo "<div class='alert alert-success alert-style' id='close'>{$_SESSION['newProduct']}</div>";
      unset($_SESSION['newProduct']);

      // CLOSE ALERT AFTER 2 SECOND
      echo "<script>setTimeout(function() {document.getElementById('close').style.display = 'none';}, 2000)</script>";
    }

    if (isset($_SESSION['newChanges'])) {

      echo "<div class='alert alert-success alert-style' id='close'>{$_SESSION['newChanges']}</div>";
      unset($_SESSION['newChanges']);

      // CLOSE ALERT AFTER 2 SECOND
      echo "<script>setTimeout(function() {document.getElementById('close').style.display = 'none';}, 2000)</script>";
    }
    
    if (isset($_SESSION['deleteSuccess'])) {
      // echo "<script>
      //         alert('{$_SESSION['deleteSuccess']}')
      //       </>";
      echo "<div class='alert alert-success alert-style' id='close'>{$_SESSION['deleteSuccess']}<span class='close'></span></div>";
      
      // CLOSE ALERT AFTER 2 SECOND
      echo "<script>setTimeout(function() {document.getElementById('close').style.display = 'none';}, 2000)</script>";

      unset($_SESSION['deleteSuccess']);

    } else if (isset($_SESSION['deleteFail'])) {
      echo "<div class='alert alert-danger alert-style' id='close'>{$_SESSION['deleteFail']}<span class='close'></span></div>";

      // CLOSE ALERT AFTER 2 SECOND
      echo "<script>setTimeout(function() {document.getElementById('close').style.display = 'none';}, 2000)</script>";

      unset($_SESSION['deleteFail']);

    } else {
        echo "<div id='close'><span class='close'></span></div>"; 
    }
    
  ?>

  <!-- PROFILE -->
  <div class="profile dropdown">
    <label for="profile" class="profile-caption"><?= $_SESSION['username'] ?></label>
    <img src="my_assets/user.png" alt="profile" id="profile" title="<?= $_SESSION['username'] ?>" class="profile-img">
    <img src="my_assets/dropdown-icon-down.png" alt="dropdown-icon" class="dropdown-icon">
    <div class="dropdown-content" accesskey="p">
      <a href="profile.php" class='btn btn-md btn-secondary edit-profile'>Edit Profile</a>
      <form action="" method="POST" class='form'>
        <button type="submit" name="logout" class="btn btn-md btn-danger logout-btn" onclick="return confirm('Are you sure want to exit?')">Logout</button>
      </form>
    </div>
  </div>
  <div class="clear"></div>
  <!-- </p> -->

  <h1>Products CRUD</h1>

  <p>
    <a href="create.php" class="btn-success btn-lg add-btn" accesskey="a"><span class="add-logo-btn">+</span></a>
  </p>

  <br>

  <form method="" action="" class="mt-5" id='form'>
    <div class="input-group mb-3">
      <input type="search" class="search form-control" spellcheck="false" id="search" accesskey="/" placeholder="Search for Products" name="search" value="<?php if (isset($_GET["search"])) { echo trim($_GET["search"]); } ?>">

      <div class="input-group-append">
        <span class="input-group-text">/</span>
      </div>

      <div class="input-group-append">
        <button class="btn btn-outline-danger" type="button" accesskey="r" title="ALT + r" id="refresh">Refresh</button>
      </div>

      <div class="input-group-append">
        <button class="btn btn-outline-success" type="submit" id="submit-search" accesskey="s" title="ALT + s" name="submit" value="yes" <?php if (!isset($_GET["search"]) || $_GET["search"] == "") { echo "disabled"; } ?>>Search</button>
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


      // DISPLAYING PRODUCT
      // RUN KETIKA HALAMAN PERTAMA KALI DILOAD DAN KETIKA REFRESH BUTTON DIPENCET


      // PAGINATION
      $jumlahData = 5;
      $currentPage = intval($_GET['page'] ?? 1);

      $start = ($jumlahData * $currentPage) - $jumlahData;

      $previousPage = $currentPage - 1;
      $nextPage = $currentPage + 1;

      if ((!isset($_GET["submit"]) && !isset($_GET["search"])) || isset($_GET["refresh"])) :


        $sql = "SELECT id, title, description, price, image, DATE_FORMAT(create_date, '%d/%m/%Y %H:%i:%S') FROM products ORDER BY create_date DESC LIMIT $start, $jumlahData";

      endif;


      // SEARCH for PRODUCT
      // RUN KETIKA SUBMIT BUTTON DAN SEARCH FIELD ADA DAN TIDAK NULL ATAU HANYA STRING KOSONG
      if (isset($_GET["submit"]) && isset($_GET["search"]) && $_GET["search"] != "") :


        $searchQuery = trim($_GET["search"]);

        $sql = "SELECT id, title, description, price, image, DATE_FORMAT(create_date, '%d/%m/%Y %H:%i:%S') FROM products WHERE title LIKE '$searchQuery%' ORDER BY create_date DESC LIMIT $start, $jumlahData";

      endif;

      ?>


      <!-- QUERY TO DATABASE -->
      <?php

      // UNTUK JUMLAH DATA YANG DITAMPILKAN DAN NOMER INDEX
      $no = 0;

      if (isset($sql)):

        // QUERY FOR DISPLAYING PRODUCT
        $result = $conn->query($sql);

        while ($products = $result->fetch_assoc()) :

        $no++;

      ?>

          <tr>
            <th class='row'><?= $no ?></th>
            <td><?php echo $products["title"] ?></td>
            <td class='description'><?php echo $products["description"] ?></td>

            <!-- JIKA IMAGE ADA MAKA TAMPILKAN ELSE OUTPUT TABLE COLUMN KOSONG -->
            <?php

            if ($products["image"] !== "") {

              echo "<td><a href='{$products["image"]}'>" . "<img src='{$products["image"]}' title='{$products["title"]}' id='image' width='100px'></a></td>";
            } else {
              echo "<td style='width=100px;' height='100px''></td>";
            }

            ?>

            <td>$<?php echo $products["price"] ?></td>
            <td><?php echo $products["DATE_FORMAT(create_date, '%d/%m/%Y %H:%i:%S')"] ?></td>
            <td>
              <a href='update.php?id=<?php echo $products["id"] ?>' class='btn btn-outline-primary'>Edit</a>
              <form action='delete.php' method='post' style='display: inline;'>
                <input type='hidden' name='id' value='<?php echo $products["id"] ?>'>
                <input type='hidden' name='image_path' value='<?php echo $products["image"] ?>'>
                <input type="hidden" name="request_url" value="<?php echo $_SERVER["REQUEST_URI"] ?>">
                <input type='submit' value="Delete" onclick="return confirm('Are you sure you want to delete this product?');" class='btn btn-outline-danger delete-btn' style='margin-left: 2px;'>
              </form>
            </td>
          </tr>

        <?php endwhile; ?>

      <?php endif; ?>


      <?php

        // QUERY UNTUK MENDAPATKAN JUMLAH ROW

        $sql = "SELECT id FROM products";

        $rowCount = $conn->query($sql)->num_rows;

      ?>

      <!-- MENAMPILKAN JUMLAH PRODUCT YANG DITAMPILKAN -->
      <p>
          <?php echo "$no/$rowCount Products Ditampilkan"; ?>
      </p>

      <!-- PAGINATION TOP -->
      <!-- IF USER SEARCHING PRODUCT THE PAGINATION WONT'T BE DISPLAYED -->
    <?php if (!isset($_GET['submit'])):

      // DISABLE PREVIOUS BUTTON IN THE FIRST PAGE
      $disablePreviousButton = 'disabled';

      if (isset($_GET['page'])) {
        $disablePreviousButton = intval($_GET['page']) <= 1 ? 'disabled' : null;
      }

    ?>
  

  <ul class="pagination justify-content-center">
    <li class="page-item <?= $disablePreviousButton; ?>">
      <a class="page-link" href="?page=<?= $currentPage - 1; ?>">Previous</a>
    </li>

    <?php for($jumlahPage = 1; $jumlahPage <= ceil($rowCount / 5) ;$jumlahPage++): ?>

      <li class="page-item <?= $jumlahPage == $currentPage ? 'active' : null; ?>"><a href="?page=<?= $jumlahPage; ?>" class="page-link"><?= $jumlahPage ?></a></li>

    <?php endfor; ?>

    <?php 

      // DISABLE NEXT BUTTON IN THE LAST PAGE

      $disableNextButton = null;
      
      if (isset($_GET['page'])) {
        $disableNextButton = intval($_GET['page']) >= $jumlahPage - 1 ? 'disabled' : null;
      }
    
    ?>

    <li class="page-item <?= $disableNextButton; ?>">
      <a class="page-link" href="?page=<?= $currentPage + 1; ?>">Next</a>
    </li>
  </ul>

<?php endif; ?>


      <?php

      // CLOSE DATABASE CONNECTION
      $conn->close();

      ?>

    </tbody>
  </table>


<!-- PAGINATION BOTTOM -->
<!-- IF USER SEARCHING PRODUCT THE PAGINATION WONT'T BE DISPLAYED -->
<?php if (!isset($_GET['submit'])): ?>
  

  <ul class="pagination justify-content-center">
    <li class="page-item <?= $disablePreviousButton; ?>">
      <a class="page-link" href="?page=<?= $currentPage - 1; ?>">Previous</a>
    </li>

    <?php for($jumlahPage = 1; $jumlahPage <= ceil($rowCount / 5) ;$jumlahPage++): ?>

      <li class="page-item <?= $jumlahPage == $currentPage ? 'active' : null; ?>"><a href="?page=<?= $jumlahPage; ?>" class="page-link"><?= $jumlahPage ?></a></li>

    <?php endfor; ?>

    <li class="page-item <?= $disableNextButton; ?>">
      <a class="page-link" href="?page=<?= $currentPage + 1; ?>">Next</a>
    </li>
  </ul>

<?php endif; ?>
<!-- END OF IF USER SEARCHING PRODUCT THE PAGINATION WONT'T BE DISPLAYED -->

  <script>
    

    // CLOSE ALERT
    let closeAlert = document.querySelector('.close');

    closeAlert.addEventListener('click', function() {

        document.querySelector('#close').style.display = 'none';

    });
    // END OF CLOSE ALERT

    let searchField = document.getElementById("search");
    let searchSubmitBtn = document.getElementById("submit-search");

    // ACTIVATE and DISABLE SEARCH BUTTON
    searchField.addEventListener('keyup', function handleSearchBtn() {

      // document.getElementById('form')['submit'].click();

      if (searchField.value.trim() != "") {
        searchSubmitBtn.disabled = false;
        // searchField.value = searchField.value.trim();
      } else {
        searchSubmitBtn.disabled = true;
      }

    })

    searchField.addEventListener('search', function handleSearchBtn() {

      // searchField.style.cursor = 'pointer';

      if (searchField.value === "") {
        searchSubmitBtn.disabled = true;
      } else {
        searchSubmitBtn.disabled = false;
      }

    })

    // FOCUS TO SEARCH FIELD WHEN USER PRESS '/'
    window.addEventListener('keyup', function(keyObj) {

      if (keyObj.key == '/') {

        document.getElementById('search').focus();

      }

    })


    // WHEN REFRESH BUTTON IS PRESSED THEN REDIRECT TO index.php AGAR URL PARAMETER TERHAPUS
    document.getElementById("refresh").onclick = function() {

      location.href = 'index.php';

    };


    // WHEN FORM IS SUBMITTED
    document.getElementById('form').addEventListener('submit', function() {

      searchField.value = searchField.value.trim();

    })


    // DROPDOWN MUNCUL KETIKA CLICK DAN MENGHILANG KETIKA DIKLIK LAGI

    $(document).ready(function(){

        let profile = document.querySelector('.profile');
        let username = document.querySelector('.profile-caption');
        let dropdownContent = document.querySelector('.dropdown-content');
        let dropdownIcon = document.querySelector('.dropdown-icon');

        // DEFAULT USERNAME STYLE
        function defaultUsernameStyle() {
            username.style.color = 'black';
            username.style.fontStyle = 'normal';
            username.style.textDecoration = 'none';
            dropdownIcon.src = 'my_assets/dropdown-icon-down.png';
        }
      
        // Show hide dropdown-content
        $(profile).click(function(){
            $(dropdownContent).toggle();


          if (dropdownContent.style.display == 'block') {
            username.style.color = 'blue';
            username.style.fontStyle = 'italic';
            username.style.textDecoration = 'underline';
            dropdownIcon.src = 'my_assets/dropdown-icon-up.png';
          } else {
            defaultUsernameStyle();
          }
          
        });

        // Hide dropdown-content when click wherever in outside
        $(document).on("click", function(event){

          let $trigger = $(profile);
          
          if($trigger !== event.target && !$trigger.has(event.target).length){
              $(dropdownContent).hide();

              defaultUsernameStyle();
          }

        });

    });

  </script>

</body>

</html>
