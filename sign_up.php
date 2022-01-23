<?php

require_once "connLoginSystem.php";

// START SESSION
session_start();

// if (isset($_SESSION['username']) && isset($_SESSION['password'])) {

//     // session_start();

//     $redirectUrl = '../products_crud/index.php';

//     header("Location: " . $redirectUrl);
//     exit;
// }

if (isset($_POST['submit'])) {

    $username = strtolower(stripslashes($_POST['username']));
    $email = $_POST['email'];
    // $password = sha1($_POST['password']);
    // $passwordConfirm = sha1($_POST['password_confirm']);
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password_confirm'];

    // CEK JIKA USERNAME SUDAH ADA DI DATABASE
    $sql = "SELECT username FROM users WHERE username = '$username'";

    if ($conn -> query($sql) -> num_rows > 0) {
        $userAlreadyExist = "Username Sudah Ada";
        // exit;
    } else if ($password == $passwordConfirm) {

        $password = password_hash($conn -> real_escape_string($_POST['password']), PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        $result = $conn->query($sql);

        if ($conn -> affected_rows > 0) {

            $successMessage = "Registrasi Berhasil";

            // $redirectUrl = "login.php";

            // header("Location: " . $redirectUrl);
            // exit;

         } else {

            $errors = $conn->error;
        }
    } else {

        $passwordNotMatch = 'Password Tidak Sama';
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
    <title>Index</title>
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
            margin: 25px auto;
        }

        .container-style {
            padding: 20px 20px 120px 20px;
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

        .form-style {
            height: 320px;
        }

        #togglePassword, #togglePasswordConfirm {
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



    <?php

    if (isset($successMessage)) {

        echo '<div class="alert alert-success" id="close">' . $successMessage . '</div>';
    ?>
            <script>
                setTimeout( function() {
                    document.getElementById('close').style.display = 'none';
                }, 3000);
            </script>

    <?php
        if (!isset($_SESSION['username']) && !isset($_SESSION['login'])):

            $_SESSION['username'] = $username;
            $_SESSION['login'] = true;
            $_SESSION['newUser'] = "Selamat datang $username";

            $redirectUrl = "../products_crud/index.php";

            // sleep(3);
            $second = 1;

            header("refresh: $second;" . "url=$redirectUrl");
            // exit;
        endif;

        } else if (isset($errors) && $errors !== null) {

            echo "<div class='alert alert-danger' id='close'>" . $errors . "<span class='close'>&times;</span>" . "</div>";

        } else if (isset($passwordNotMatch)) {
            echo '<div class="alert alert-danger" id="close">' . $passwordNotMatch . "<span class='close'>&times;</span>" . '</div>';
        } else if (isset($userAlreadyExist)) {
            echo '<div class="alert alert-danger" id="close">' . $userAlreadyExist . "<span class='close'>&times;</span>" . '</div>';
        } else {
            echo '<div class="" id="close">' . "<span class='close'></span>" . '</div>';
        }

    ?>
    

    <div class="container-layout container-style">
        <h1>Registrasi</h1>
        <h4 class="text">Ini Cepat dan Mudah</h4>
        <form action="" method="POST" id="form" class="form-layout form-style">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" minlength="3" maxlength="50" class="form-control" value="<?php if (isset($passwordNotMatch)) { echo $_POST["username"]; } ?>" id="username" placeholder="Enter Username" required>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" class="form-control" value="<?php if (isset($passwordNotMatch)) { echo $_POST["email"]; } ?>" id="email" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
                <label for="password">Choose Password</label>
                <input type="password" name="password" minlength="8" maxlength="20" class="form-control" value="<?php if (isset($passwordNotMatch)) { echo $_POST["password"]; } ?>" id="password" placeholder="Enter Password" required>
                <i class="bi bi-eye-slash" id="togglePassword"></i>
                <div class="clear"></div>
            </div>
            <div class="form-group">
                <label for="password">Confirm Password</label>
                <input type="password" name="password_confirm" minlength="8" maxlength="20" class="form-control"  id='passwordConfirm' placeholder="Enter Password Again" required>
                <i class="bi bi-eye-slash" id="togglePasswordConfirm"></i>
            </div>
            <div class="form-group">
                <small>Sudah Memiliki Akun? <a href="login.php">Login</a></small>
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


        // TOGGLE PASSWORD CONFIRM
        const togglePasswordConfirm = document.querySelector("#togglePasswordConfirm");
        const passwordConfirm = document.querySelector("#passwordConfirm");

        togglePasswordConfirm.addEventListener("click", function () {
            // toggle the type attribute
            const type = passwordConfirm.getAttribute("type") === "password" ? "text" : "password";
            passwordConfirm.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

    </script>

</body>

</html>