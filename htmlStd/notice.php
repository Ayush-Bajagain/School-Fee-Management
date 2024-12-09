<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>notices</title>
    <link rel="stylesheet" href="../newCss/layout.css">
    <link rel="stylesheet" href="../studentCss/notice.css">


</head>

<body>

    <?php
    $user_profile = $_SESSION["email"];
    $role = $_SESSION["role"];


    if ($user_profile == true && $role == "student") {
    } else {
        header("location:../index.php");
    }

    ?>

    <?php
    include("../api/connection.php");  // Include the database connection

    $email = $user_profile;

    // Fetch the student's program and batch
    $query = "SELECT program, batch FROM students WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $program = $row['program'];
        $batch = $row['batch'];
    } else {
        die("Student data not found.");
    }

    $stmt->close();
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
                <p class="welcome">Hello! <?php echo $_SESSION['std_name']; ?></p><br><br>

                <nav class="menu">
                    <ul id="nav-menu">
                        <li><a href="studentHome.php">Home</a></li>
                        <li><a href="#" class="active">Fee Notice</a></li>
                        <li><a href="details.php">History</a></li>
                        <li><a href="payment.php">Payment</a></li>
                        <li id="logout-bnt"><a href="../api/logout.php" style=" display: block;color: white;text-decoration: none;background-color: #ff4d4d;padding: 8px 30px;border-radius: 5px;">Logout</a></li>
                    </ul>
                </nav>
            </aside>
            <main class="main-content">
                <table>
                    <thead>
                        <tr>
                            <th>Transaction Id</th>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Program</th>
                            <th>Batch</th>
                            <th>Message</th>
                            <th>Field</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $sql = "
                            SELECT id, title, amount, program, batch, message, payment_field FROM makepayment WHERE program = ? AND batch = ?
                            UNION ALL
                            SELECT id, title, amount, program, batch, message, payment_field FROM other_payment WHERE program = ? AND batch = ?
                        ";

                        $stmt = $conn->prepare($sql);
                        if ($stmt) {
                            $stmt->bind_param("ssss", $program, $batch, $program, $batch);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "
                                        <tr>
                                            <td>" . htmlspecialchars($row['id']) . "</td>
                                            <td>" . htmlspecialchars($row['title']) . "</td>
                                            <td>" . htmlspecialchars($row['amount']) . "</td>
                                            <td>" . htmlspecialchars($row['program']) . "</td>
                                            <td>" . htmlspecialchars($row['batch']) . "</td>
                                            <td>" . htmlspecialchars($row['message']) . "</td>
                                            <td>" . htmlspecialchars($row['payment_field']) . "</td>
                                            <td>
                                                
                                                    <a href='paymentById.php?id=" . htmlspecialchars($row['id']) . "' class='pay-btn'>Pay</a>
                                               
                                            </td>
                                        </tr>
                                    ";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No records found.</td></tr>";
                            }
                            $stmt->close();
                        } else {
                            die("Failed to prepare statement: " . $conn->error);
                        }

                        $conn->close();
                        ?>

                    </tbody>
                </table>



                <br><br><br><br><br>

                <?php

                include '../api/connection.php';





                $query = "SELECT payment_id, student_id, student_name, batch, program, amount, payment_purpose, comment 
                        FROM individual_payment 
                        WHERE student_email = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "s", $user_profile);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (!$result) {
                    die("Query Failed: " . mysqli_error($conn));
                }
                ?>


                <table>
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Student ID</th>
                            <th>Amount</th>
                            <th>Payment Purpose</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['payment_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['payment_purpose']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['comment']) . "</td>";
                                echo "<td><a href='paymentIndividual.php?payment_id=" . urlencode($row['payment_id']) . "' class='btn pay-btn'>Pay</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
    <script src="../js/homepageTimeUpdate.js"></script>
    <script src="../js/navigationRespon.js"></script>
</body>

</html>