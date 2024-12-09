<?php
session_start();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>application</title>
    <link rel="stylesheet" href="../newCss/layout.css">
    <link rel="stylesheet" href="../newCss/application.css">





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
                        <li><a href="student.php">Student</a></li>
                        <li><a href="payment.php">Payment <?php
                                                            if ($no_row1 > 1) {
                                                                echo "<sup id= 'payment-noti'>$no_row1</sup>";
                                                            }
                                                            ?></a>
                        </li>
                        <li><a href="#" class="active">Application</a></li>
                        <li id="logout-bnt"><a href="../api/logout.php" style=" display: block;color: white;text-decoration: none;background-color: #ff4d4d;padding: 8px 30px;border-radius: 5px;">Logout</a></li>
                    </ul>
                </nav>
            </aside>




            <main class="main-content">
                <div class="navigation">
                    <h2>Register application : </h2>
                </div>

                <section class="student-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Batch</th>
                                <th>Program</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <?php
                        include("../api/connection.php");

                        // Fetch all records from the 'register' table
                        $sql = "SELECT * FROM register";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "
                                <tr>
                                    <td>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                                    <td>" . htmlspecialchars($row['email']) . "</td>
                                    <td>" . htmlspecialchars($row['batch']) . "</td>
                                    <td>" . htmlspecialchars($row['program']) . "</td>
                                    <td>
                                        <a href='registerAccept.php?id=" . urlencode($row['id']) . "' class='btn accept btn-accept-delete'>Accept</a>
                                        <a href='registerDecline.php?id=" . urlencode($row['id']) . "' class='btn delete btn-accept-delete'>Delete</a>
                                    </td>
                                </tr>
                             ";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No records found</td></tr>";
                        }

                        // Close the database connection
                        $conn->close();
                        ?>

                    </table>
                </section>
            </main>
        </div>
    </div>

    <script src="../js/homepageTimeUpdate.js"></script>
    <script src="../js/navigationRespon.js"></script>

</body>

</html>