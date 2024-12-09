
<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./newCss/style.css"/>
    <title>Login</title>
</head>

<body>
    <div class="container">
        <form action="#" method="POST">
            <fieldset class="outline">
                <legend>Login</legend>

                <div class="form-group">
                    <label for="email">Email: </label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" class="email" placeholder="Enter your email" required>
                        <i class="bi bi-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" class="password" placeholder="Enter your password" required>
                        <i class="bi bi-lock"></i>
                        <!-- <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i> -->
                    </div>
                </div>

                <div class="content">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" id="login-btn" name="login">Login</button>

                <div class="register">
                    <span>Doesn't have an account? <a href="register.php">Register</a></span>
                </div>
            </fieldset>
        </form>
    </div>

    <script src="js/script.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            togglePassword.addEventListener('click', function () {
                // Toggle the password field type
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle the eye icon class
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });

    </script>
</body>

</html>



<?php

include("api/connection.php");

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "select * from users where email = '$email' && password = '$password' ";

    $data = mysqli_query($conn, $query);

    $total = mysqli_num_rows($data);   // the total stored the value of row which is retreived






    if($total == 1){
        while($row = mysqli_fetch_assoc($data)){
            $role = $row['role'];
            $name = $row['username'];


            if($role == "student"){

                $_SESSION['std_name'] = $name;
                $_SESSION['role'] = $role;
                $_SESSION['email'] = $email;
            
                


                header('location:htmlStd/studentHome.php');
            }

            if($role == "admin"){

                $_SESSION['user_name'] = $email;
                $_SESSION['role'] = $role;


                header('location:html/adminHome.php');
            }
        }
    }
    else{
        echo "<script>alert('failed to login')</script>";
    }
}


?>