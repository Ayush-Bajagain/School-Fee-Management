<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Student</title>



    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            display: flex;
            height: 100vh;
            flex-direction: column;
        }

        .header {
            background-color: #333;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .logo {
            width: 75px;
            height: 75px;
            overflow: hidden;
            margin-left: 50px;
            border: 1px solid #ffffff33;
            border-radius: 50%;
        }

        .logo img {
            width: 100%;
            height: auto;
        }

        .title h1 {
            font-size: 24px;
            margin-left: 20px;
        }

        .date-time {
            text-align: right;
        }

        .wrapper {
            display: flex;
            flex: 1;
        }

        .sidebar {
            background-color: #2b2b2b;
            color: white;
            padding: 20px;
            width: 200px;
            height: 100%;
            display: flex;
            position: relative;
            flex-direction: column;
        }

        .menu-icon {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
            display: none;

        }


        .sidebar .welcome {
            /* margin-bottom: 20px; */
            font-weight: bold;
            color: yellow;
        }

        .menu ul {
            list-style: none;
        }

        .menu ul li {
            margin: 30px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .menu ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            text-align: center;
        }

        .active {
            color: royalblue !important;
        }

        .menu ul li a:hover {
            color: royalblue;
        }


        #logout-bnt {

            width: 90%
        }

        #logout-btn a {
            display: block;
            color: white;
            text-decoration: none;
            background-color: #ff4d4d;

            padding: 8px 30px;
            border-radius: 5px;
        }

        #logout-btn a:hover {
            background-color: #e60000;
        }






        .main-content {
            padding: 50px;
            background-color: #fff;
            flex: 1;
        }

        .main-content section {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 40px;
        }

        .main-content section .box {
            width: 350px;
            height: 400px;
            box-shadow: 1px 1px 10px -3px #00000066;
            border-radius: 10px;
            position: relative;
            padding: 30px;
            display: flex;
            flex-direction: column;
        }

        .box .photo {
            position: relative;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 150px;
            border: 1px solid #00000034;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 50px;
        }

        .box .photo img {
            width: 100%;
        }

        .box a {
            text-decoration: none;
            color: #444;
            font-size: 24px;
            text-align: center;
            font-family: "poppins";

        }

        .box a:hover {
            color: royalblue;
            font-weight: 600;
            /*border-bottom: 2px solid #00000066;*/
        }

        /*---------------------- Responsive Design ----------------------*/

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                padding: 10px;
                display: flex;
                justify-content: center;
            }

            .menu-icon {

                display: block;

            }

            .menu ul {
                display: none;
                flex-direction: column;
                justify-content: space-around;
                align-items: center;
                width: 100%;
            }



            .main-content {
                padding: 20px;
            }

            .main-content section .box {
                width: 100%;
                height: auto;
            }

            .box .photo {
                width: 120px;
                height: 120px;
            }

            .box a {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .title h1 {
                font-size: 18px;
            }

            .logo {
                width: 50px;
                height: 50px;
            }

            .main-content {
                padding: 10px;
            }

            .main-content section .box {
                padding: 15px;
            }

            .box .photo {
                width: 100px;
                height: 100px;
            }

            .box a {
                font-size: 18px;
            }
        }
    </style>

    

</head>

<body>

    <?php
    $user_profile = $_SESSION["user_name"];
    $role = $_SESSION["role"];

    if ($user_profile == true && $role == "admin") {
    } else {
        header("location:../index.php");
    }
    ?>

    <div class="container">
        <header class="header">
            <div class="logo">
                <img src="../images/kcmit.jpg" alt="School Logo">
            </div>
            <div class="title">
                <h1>Fee Management System</h1>
            </div>
            <div class="date-time">
                <p>08/30/2024 10:23:15 AM</p>
            </div>
        </header>

        <div class="wrapper">
            <aside class="sidebar">
                <img src="../images/icon/menu.png" class="menu-icon" width="30" height="30" onclick="openNav()">
                <p class="welcome">Hello! ADMIN</p><br><br>
                <nav class="menu">
                    <ul id="nav-menu">
                        <li><a href="adminHome.php">Dashboard</a></li>
                        <li><a href="#" class="active">Student</a></li>
                        <li><a href="payment.php">Payment</a></li>
                        <li><a href="application.php">Application</a></li>
                        <li id="logout-bnt"><a href="../api/logout.php" style=" display: block;color: white;text-decoration: none;background-color: #ff4d4d;padding: 8px 30px;border-radius: 5px;">Logout</a></li>
                    </ul>
                </nav>
            </aside>

            <main class="main-content">
                <section>
                    
                    <div class="add-student box">
                        <a href="addStudent.php">

                            <section class="photo">
                                <img src="../images/addStudent.jpg" alt="Photo of enrolled student">
                            </section>
                            <a href="addStudent.php">Add Student</a>
                        </a>
                    </div>

                    <div class="view-student box">
                        <a href="viewStudent.php">
                            <section class="photo">
                                <img src="../images/user_view.jpg" alt="Photo of enrolled student">
                            </section>
                            <a href="viewStudent.php">View Student</a>
                        </a>
                    </div>

                    <div class="delete-student box">
                        <section class="photo">
                            <img src="../images/delete-user.jpg" alt="Photo of enrolled student">
                        </section>
                        <a href="#">Delete Student</a>
                    </div>
                </section>             
            </main>

          



        </div>
    </div>

    <script src="../js/homepageTimeUpdate.js"></script>

    <script>
        var a = document.getElementById('nav-menu');
        var x = 0;

        function openNav() {
            if (x == 0) {
                a.style.display = "flex";
                x = 1;
            } else {
                a.style.display = "none";
                
                x = 0;
            }
        }
    </script>
</body>

</html>