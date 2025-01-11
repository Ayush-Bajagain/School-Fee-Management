<?php
session_start();
?>

<?php
$user_profile = $_SESSION["email"];
$role = $_SESSION["role"];

if ($user_profile == true && $role == "student") {
} else {
    header("location:../index.php");
}

include("../api/connection.php");

// Get student ID from the session
$sql = "SELECT student_id FROM students WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_profile);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_id_main =  $row['student_id'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../studentCss/homePage.css" />
    <link rel="stylesheet" href="../newCss/layout.css" />
    <style>
        #notification-text {
            text-decoration: none;
            color: #444;
            font-size: 20px;
        }

        #notification-text:hover {
            color: red;
            text-decoration: underline;
        }

        a {
            text-decoration: none;
            color: #333;
        }
    </style>
    <title>Dashboard</title>



</head>

<body>

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
                        <li><a href="#" class="active">Home</a></li>
                        <li><a href="notice.php">Fee Notice</a></li>
                        <li><a href="details.php">History</a></li>
                        <li><a href="payment.php">Payment</a></li>
                        <li id="logout-bnt"><a href="../api/logout.php" style=" display: block;color: white;text-decoration: none;background-color: #ff4d4d;padding: 8px 30px;border-radius: 5px;">Logout</a></li>
                    </ul>
                </nav>
            </aside>

            <main class="main-content">
                <?php
                include("../api/connection.php");

                // Fetch fee details for the logged-in student
                $sql = "SELECT * FROM fee_details WHERE student_id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $student_id_main);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $total_fee =  $row['total_fee'];
                    $paid_fee =  $row['paid_fee'];
                    $due_fee =  $row['due_fee'];
                } else {
                    echo "Amount details not found";
                }

                $stmt->close();
                $conn->close();
                ?>

                <?php
                include '../api/connection.php';
                $totalPaidAmount = 0;

                $sql = "SELECT SUM(paid_amount) AS total_paid_amount FROM other_fee_details WHERE student_id = ?";

                try {
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $student_id_main);

                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result && $row = $result->fetch_assoc()) {
                        $totalPaidAmount = $row['total_paid_amount'] ?? 0; // Use null coalescing operator to avoid null
                    }
                    $result->free();
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }

                $stmt->close();
                $conn->close();
                ?>



                <div class="dtl-section">
                    <a href="#" class="card total">
                        <div class="photo">
                            <img src="../images/totalfee.jpg" alt="Total Fee">
                        </div>
                        <h3>Total Fee</h3>
                        <strong><?php echo $total_fee; ?></strong>
                    </a>
                    <a href="details.php" class="card paid">
                        <div class="photo">
                            <img src="../images/paid.jpg" alt="Paid Fee">
                        </div>
                        <h3>Paid Fee</h3>
                        <strong><?php echo $paid_fee; ?></strong>
                    </a>
                    <a href="#" class="card due">
                        <div class="photo">
                            <img src="../images/paymentDue.jpg" alt="Due Fee">
                        </div>
                        <h3>Due Fee</h3>
                        <strong><?php echo $due_fee; ?></strong>
                    </a>

                    <a href="details.php" class="card other">
                        <div class="photo">
                            <img src="../images/otherfee.jpg" alt="Due Fee">
                        </div>
                        <h3>Other Paid Fee</h3>
                        <strong><?php echo $totalPaidAmount; ?></strong>
                    </a>
                </div>








                <div class="notification">






                    <?php
                    //This is for fetching the program and batch of student
                    include("../api/connection.php");
                    $email = $user_profile;

                    $query = "SELECT program, batch, student_id FROM students WHERE email = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $program = $row['program'];
                            $batch = $row['batch'];
                            $student_id = $row['batch'];
                        }
                    }
                    $stmt->close();
                    $conn->close();
                    ?>



                    <?php
                    include '../api/connection.php';


                    try {
                        // Query to count rows in individual_payment
                        $query1 = $conn->prepare("SELECT COUNT(*) AS num_rows FROM individual_payment WHERE student_id = ? AND student_email = ?");
                        if ($query1) {
                            $query1->bind_param("ss", $student_id_main, $email);
                            $query1->execute();
                            $query1->bind_result($count_individual);
                            $query1->fetch();
                            $query1->close();
                        } else {
                            throw new Exception("Query1 failed: " . $conn->error);
                        }

                        // Query to count rows in make_payment
                        $query2 = $conn->prepare("SELECT COUNT(*) AS num_rows FROM makepayment WHERE program = ? AND batch = ?");
                        if ($query2) {
                            $query2->bind_param("ss", $program, $batch);
                            $query2->execute();
                            $query2->bind_result($count_make_payment);
                            $query2->fetch();
                            $query2->close();
                        } else {
                            throw new Exception("Query2 failed: " . $conn->error);
                        }

                        // Query to count rows in other_payment
                        $query3 = $conn->prepare("SELECT COUNT(*) AS num_rows FROM other_payment WHERE program = ? AND batch = ?");
                        if ($query3) {
                            $query3->bind_param("ss", $program, $batch);
                            $query3->execute();
                            $query3->bind_result($count_other_payment);
                            $query3->fetch();
                            $query3->close();
                        } else {
                            throw new Exception("Query3 failed: " . $conn->error);
                        }

                        // Check if there is data in any of the three tables
                        if ($count_individual > 0 || $count_make_payment > 0 || $count_other_payment > 0) {
                            echo '<a href="notice.php" id="notification-text">You have new notification...</a>';
                        }
                    } catch (Exception $e) {
                        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
                    }

                    $conn->close();
                    ?>


                </div>
            </main>
        </div>


    </div>

    <script src="../js/homepageTimeUpdate.js"></script>
    <script src="../js/navigationRespon.js"></script>




</body>

</html>