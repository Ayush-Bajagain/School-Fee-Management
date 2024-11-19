<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/adminHome.css">

    <style>
    .notification {
        width: fit-content;
        height: fit-content;
        padding: 30px;
        margin-top: 60px;
        border: none;
    }

    #tableMain {
        display: none;
    }

    #show-hide-notification {
        width: 80px;
        height: 40px;
        background-color: royalblue;
        color: #fff;
        border: none;
        border-radius: 10px;
        margin-bottom: 20px;
        cursor: pointer;
    }

    .noti-to-application {
        text-decoration: none;
        font-size: 1.1rem;
        margin-left: 10px;
        color: #333;
    }

    table {
        margin-top: 20px;
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    th {
        background-color: rgba(65, 105, 225, 0.857);
        color: white;
    }

    tr:hover {
        outline: 1.1px solid #ccc;
    }
    </style>
</head>
<body>

<?php
$user_profile = $_SESSION["user_name"];
$role = $_SESSION["role"];

if (!$user_profile || $role !== "admin") {
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
            <p></p>
        </div>
    </header>

    <div class="wrapper">
        <aside class="sidebar">
            <p class="welcome">Hello! ADMIN</p><br><br>
            <nav class="menu">
                <ul>
                    <li><a href="#" class="active">Dashboard</a></li>
                    <li><a href="student.php">Student</a></li>
                    <li><a href="payment.php">Payment</a></li>
                    <li><a href="application.php">Application</a></li>
                    <li id="logout-btn"><a href="../api/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <section class="first-section">
                <div class="enrolled-box">
                    <a href="viewStudent.php">
                        <section class="photo">
                            <img src="../images/enrolled.jpg" alt="Photo of enrolled students">
                        </section>
                        <a href="viewStudent.php" style="text-decoration: none">
                            <h2>Enrolled Students</h2>
                        </a>
                        <?php
                        include("../api/connection.php");
                        $sql = "SELECT COUNT(*) AS student_count FROM students";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $student_count = $row['student_count'];
                        ?>
                        <p class='count-std'><?php echo $student_count; ?></p>
                    </a>
                </div>

                <div class="fee-collection-box">
                    <a href="viewStudent.php">
                        <section class="photo">
                            <img src="../images/payment.jpg" alt="Fee collection">
                        </section>
                        <a href="#" style="text-decoration: none">
                            <h2>Total Fee Collection</h2>
                        </a>

                        <?php
                        include("../api/connection.php");

                        $sql = "SELECT paid_fee FROM fee_details";
                        $result = $conn->query($sql);

                        $total_sum = 0;

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $total_sum += $row['paid_fee'];
                            }
                        }
                        ?>

                        <p class="count-fee"><?php echo $total_sum; ?></p>
                    </a>
                </div>

                <div class="due-box">
                    <a href="">

                        <section class="photo">
                            <img src="../images/fee_due.jpg" alt="Due fee students">
                        </section>
                        <a href="#" style="text-decoration: none">
                            <h2>Due Fee</h2>
                        </a>

                        <?php
                        // Calculate due fee
                        include("../api/connection.php");

                        $sql = "SELECT total_fee, paid_fee FROM fee_details";
                        $result = $conn->query($sql);

                        $total_due_fee = 0;

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $total_fee = $row['total_fee'];
                                $paid_fee = $row['paid_fee'];

                                // Calculate due fee as total fee minus paid fee
                                $due_fee = $total_fee - $paid_fee;
                                $total_due_fee += $due_fee;
                            }
                        }
                        ?>

                        <p class="count-due"><?php echo $total_due_fee; ?></p>
                    </a>
                </div>
            </section>

            <div class="notification">
                <?php
                include("../api/connection.php");

                // SQL query to count the rows in the table
                $sql_count = "SELECT COUNT(*) as total_rows FROM register";
                $result_count = $conn->query($sql_count);

                if ($result_count->num_rows > 0) {
                    $row_count = $result_count->fetch_assoc();
                    $no_row = $row_count['total_rows'] . "<br>";
                } else {
                    echo "No rows found.<br>";
                }
                ?>

                <b> <i class="ri-notification-line"></i> <a class="noti-to-application" href="application.php"><?php echo " Notification" . " " . $no_row ?></a></b> <br>

                <button id="show-hide-notification" onclick="showAlert()">Show</button>

                <?php
                $sql_fetch = "SELECT id, full_name, email, batch FROM register";
                $result_fetch = $conn->query($sql_fetch);

                if ($result_fetch->num_rows > 0) {
                    // Display the details
                    echo "<table id='tableMain' border='1'>";
                    echo "<tr><th>ID</th><th>Full Name</th><th>Email</th><th>Batch</th></tr>";

                    while ($row = $result_fetch->fetch_assoc()) {
                        echo "<tr><td>" . $row["id"] . "</td><td>" . $row["full_name"] . "</td><td>" . $row["email"] . "</td><td>" . $row["batch"] . "</td></tr>";
                    }

                    echo "</table>";
                } else {
                    echo "No data found.";
                }

                // Close the connection
                $conn->close();
                ?>
            </div>

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

<script>
    var tableData = document.getElementById("tableMain");
    var a = document.getElementById("show-hide-notification");
    var visible = 1;

    function showAlert() {
        if (visible == 0) {
            tableData.style.display = "none";
            a.innerHTML = "Show";
            a.style.background = "royalblue";
            visible = 1;
        } else {
            tableData.style.display = "block";
            a.innerHTML = "Hide";
            a.style.background = "#d00";
            visible = 0;
        }
    }
</script>
</body>
</html>
