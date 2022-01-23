<?php

require_once "connLoginSystem.php";

// START SESSION
session_start();

if (isset($_SESSION['login']) && isset($_SESSION['username'])) {


    $redirectUrl = '../products_crud/index.php';

    header("Location: " . $redirectUrl);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // $username = $_POST['username'];
    $username = $_POST['username'];
    $password = $_POST['password'];



    // exit;

    // $password = sha1($_POST['password']);

    $sql = "SELECT username, password FROM users WHERE username = '$username'";

    // echo "<pre>";
        // var_dump($password);
        // var_dump($conn -> query($sql) -> fetch_assoc());
    // echo "</pre>";
    // exit;
    $result = $conn -> query($sql);

    $users = $result->fetch_assoc();

    if ($users === null) {
        $error = 'Username Salah';
    } else if (password_verify($password, $users['password'])) {
        
        $redirectUrl = "../products_crud/index.php";

        $_SESSION['username'] = $username;
        $_SESSION['login'] = true;

        $_SESSION['newUser'] = "Selamat datang $username";

        header("Location: " . $redirectUrl);
        exit;
    } else {

        // echo $conn->error;

        $error = 'Password Salah';
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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <title>Login</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            padding: 20px;
            background-color: #f3f3f3;
        }

        .clear {
            clear: both;
        }

        .alert {
            position: relative;
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

        .container-layout {
            margin: 110px auto;
        }

        .container-style {
            padding: 20px 20px 10px 20px;
            background-color: #fff;
            width: 40%;
            border-radius: 10px;
        }

        .text {
            color: #c6c6c6;
        }

        /* FORM */
        .form-layout {
            margin-top: 30px;
        }

        /* .form-style {
            
        } */

        #togglePassword {
            margin-top: -30px;
            margin-right: 20px;
            float: right;
            cursor: pointer;
        }

        .submit-btn-layout {
            margin-top: 10px;
        }

        .submit-btn-style {
            width: 100%;
            font-size: 19px;
        }

    </style>
</head>

<body>



    <?php if (isset($error)): ?>

            <div class="alert alert-danger" id="close"><?= $error ?><span class='close'>&times;</span></div>
         <!-- CLOSE ALERT AFTER 2 SECOND -->
            <script>
                setTimeout( function() {
                    document.getElementById('close').style.display = 'none';
                }, 3000);
            </script>
    <?php  else: ?>

            <div class="" id="close"><span class='close'></span></div>
    <?php endif ?>
    

    <div class="container-layout container-style">
        <h1>Login</h1>
        <form action="" method="POST" id="form" class="form-layout form-style">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" minlength="1" maxlength="50" class="form-control" value="<?php if (isset($passwordWrong)) { echo $_POST["username"]; } ?>" id="username" placeholder="Enter Username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" minlength="8" maxlength="20" class="form-control" id="password" placeholder="Enter Your Password" required>
                <i class="bi bi-eye-slash" id="togglePassword"></i>
                <div class="clear"></div>
            </div>
            <div class="form-group">
                <small>Belum Memiliki Akun? <a href="sign_up.php">Daftar</a></small>
                <!-- <input type="reset" id="reset" value="Reset" class="btn btn-outline-danger reset-btn-layout reset-btn-style"> -->
                <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary submit-btn-layout submit-btn-style">
            </div>
        </form>
    </div>

    <script>

        // CLOSE ALERT
        let closeAlert = document.querySelector('.close');

        closeAlert.addEventListener('click', function() {

            document.querySelector('#close').style.display = 'none';

        });
        // END OF CLOSE ALERT


        // TURN OFF SPELLCHECK OF ALL INPUT FIELD
        // THERE ARE TWO DIFFERENT WAYS TO DO THAT

        let allInputField = document.getElementsByTagName("input");

        // for (let i = 0; i < allInputField.length; i++) {
        //     allInputField[i].spellcheck = false;
        // }

        for (let x in allInputField) {
            allInputField[x].spellcheck = false;
        }

        // END OF TURN OFF SPELLCHECK OF ALL INPUT FIELD


        // TOGGLE PASSWORD
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

    </script>

</body>

</html>