<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../newCss/layout.css">
    <link rel="stylesheet" href="../newCss/student.css">
    <title>Student</title>
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
    <?php            
        include("../api/connection.php");
        $sql_count = "SELECT COUNT(*) as total_rows FROM transaction_details";
        $result_count = $conn->query($sql_count);

        if ($result_count->num_rows > 0) {
            $row_count = $result_count->fetch_assoc();
            $no_row1 = $row_count['total_rows'] . "<br>";
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
                        <li><a href="payment.php">Payment <?php 
                        if($no_row1>1){
                            echo "<sup id= 'payment-noti'>$no_row1</sup>";
                        } 
                    ?></a></li>
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

    <script src="../js/navigationRespon.js"></script>
</body>

</html>