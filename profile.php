<?php

// CHECK SESSION
require_once "checkSession.php";

require_once "../login_system/connLoginSystem.php";

$sql = "SELECT * FROM users WHERE username = '{$_SESSION['username']}'";

$result = $conn->query($sql);

$user = $result->fetch_assoc();

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'] == '' ? $user['password'] : $_POST['new_password'];
    $newPasswordConfirm = $_POST['confirm_new_password'] == '' ? $user['password'] : $_POST['confirm_new_password'];


    // CEK JIKA USERNAME SUDAH ADA DI DATABASE
    $sql = "SELECT username FROM users WHERE username = '$username'";

    // var_dump($conn -> query($sql) -> num_rows);
    // exit;

    if ($conn -> query($sql) -> num_rows === 1) {
        $userAlreadyExist = "Username Sudah Ada";
        // exit;
    } else if ($newPassword == $newPasswordConfirm) {

        if ($newPassword != $user['password']) {

            $newPassword = password_hash($conn -> real_escape_string($newPassword), PASSWORD_DEFAULT);

            $sql = "UPDATE users set username ='$username', email = '$email', password = '$newPassword' WHERE username = '{$user['username']}'";

        } else {

            // $newPassword = password_hash($conn -> real_escape_string($newPassword), PASSWORD_DEFAULT);

            $sql = "UPDATE users set username ='$username', email = '$email' WHERE username = '{$user['username']}'";
        }

            $result = $conn->query($sql);

            if ($conn -> affected_rows > 0) {

                $successMessage = "Edit Berhasil";

                $_SESSION['username'] = $username;

                // UNTUK MENGUPDATE USERNAME DAN EMAIL DI INPUT FIELD
                $user['username'] = $username;
                $user['email'] = $email;


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

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <title>Profile</title>
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
            margin: 35px auto;
        }

        .container-style {
            padding: 20px 20px 120px 20px;
            background-color: #fff;
            width: 40%;
            border-radius: 10px;
        }
        
        .arrow-btn {
            font-size: 30px;
        }

        /* FORM */
        .form-layout {
            margin-top: 20px;
        }

        .form-style {
            height: 330px;
        }

        #toggleOldPassword,
        #toggleNewPassword,
        #toggleNewPasswordConfirm {
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

        echo '<div class="alert alert-success" id="close">' . $successMessage . "<span class='close'>&times;</span>" . '</div>';

        $redirectUrl = "index.php";

        $second = 0.5;

        header("refresh: $second;" . "url=$redirectUrl");
        // exit;

    } else if (isset($errors) && $errors != '') {

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
        <a href="index.php" class="btn btn-secondary"><span class="arrow-btn">&#8592;</span></a>
        <h1>Profile</h1>
        <form action="" method="POST" id="form" name="form" class="form-layout form-style">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" minlength="3" maxlength="50" class="form-control" data-username-value="<?= $user['username'] ?>" value="<?= $user['username'] ?>" id="username" placeholder="Enter Username" required>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" class="form-control" data-email-value="<?= $user['email'] ?>" value="<?= $user['email'] ?>" id="email" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="new_password" minlength="8" maxlength="20" class="form-control" value="" id="newPassword" placeholder="Enter a New Password">
                <i class="bi bi-eye-slash" id="toggleNewPassword"></i>
                <div class="clear"></div>
            </div>
            <div class="form-group">
                <label for="password">Confirm New Password</label>
                <input type="password" name="confirm_new_password" minlength="8" maxlength="20" class="form-control" value="" id="newPasswordConfirm" placeholder="Enter Password Again">
                <i class="bi bi-eye-slash" id="toggleNewPasswordConfirm"></i>
                <div class="clear"></div>
            </div>
            <div class="form-group">
                <input type="reset" id="reset" value="Reset" class="btn btn-outline-danger reset-btn-layout reset-btn-style">
                <input type="submit" name="submit" id="submit"  value="Submit" class="btn btn-primary submit-btn-layout submit-btn-style" disabled>
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

        // ENF OF TURN OFF SPELLCHECK OF ALL INPUT FIELD


        // TOGGLE NEW PASSWORD
        const newPassword = document.querySelector("#newPassword");
        const toggleNewPassword = document.querySelector("#toggleNewPassword");

        toggleNewPassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = newPassword.getAttribute("type") === "password" ? "text" : "password";
            newPassword.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

        // TOGGLE CONFIRMATION NEW PASSWORD
        const newPasswordConfirm = document.querySelector("#newPasswordConfirm");
        const toggleNewPasswordConfirm = document.querySelector("#toggleNewPasswordConfirm");

        toggleNewPasswordConfirm.addEventListener("click", function () {
            // toggle the type attribute
            const type = newPasswordConfirm.getAttribute("type") === "password" ? "text" : "password";
            newPasswordConfirm.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

        
        // newPassword = document.getElementById('newPassword');

        // let form = document.forms.namedItem('form');
        const submitBtn = document.getElementById('submit');
        let username = document.getElementById('username');
        let email = document.getElementById('email');

        username.addEventListener('input', function() {

            username = document.getElementById('username');
            
            if (username.value != username.getAttribute('data-username-value')) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
            
        });


        email.addEventListener('input', function() {

            email = document.getElementById('email');

            if (email.value != email.getAttribute('data-email-value')) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }

        });

        
        newPassword.onkeyup = function(keyObj) {

            if (newPassword.value != '') {
                newPasswordConfirm.required = true;
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }

        }

    </script>

</body>

</html>