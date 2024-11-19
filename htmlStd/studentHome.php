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

<!--    <link rel="stylesheet" href="../cssStd/studentLayout.css"/>-->
<!--    <link rel="stylesheet" href="../cssStd/home.css"/>-->

    <title>Dashboard</title>


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }

        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            border-radius: 50%;
            overflow: hidden;
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
            width: 100%;
            flex-direction: row;
        }

        /* Sidebar */
        .sidebar {
            background-color: #2b2b2b;
            color: white;
            padding: 20px;
            width: 250px;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar .welcome {
            margin-bottom: 20px;
            font-weight: bold;
            color: yellow;
        }

        .menu ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 30px;
            align-items: center;
            flex-grow: 1;
            justify-content: center;
        }

        .menu ul li {
            text-align: center;
        }

        .menu ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .menu ul li .active {
            color: royalblue;
        }

        .menu ul li a:hover {
            color: royalblue;
        }

        #logout-btn {
            margin-top: auto;
            background-color: red;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 80%;
            border-radius: 4px;
        }
        #logout-btn a{
            padding: 16px 35px;
            color: #fff;

        }

        #logout-btn:hover {
            opacity: 100%;
        }



        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #fff;
        }


        .dtl-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            padding: 20px 0;
        }

        .card {
            height: 400px;
            border-radius: 10px;
            box-shadow: 1px 1px 10px -1px #333;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card .photo {
            border-radius: 50%;
            overflow: hidden;
            width: 130px;
            height: 130px;
        }

        .card .photo img {
            width: 100%;
        }

        .card h3 {
            font-size: 24px;
            margin: 20px 0;
        }

        .card strong {
            font-size: 28px;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }

            .sidebar {

                width: 100%;
                height: fit-content;
                position: relative;
            }

            .main-content {
                padding: 10px;
            }

            .card {
                height: auto;
            }

            .card h3, .card strong {
                font-size: 22px;
                text-align: center;
            }
        }


        @media screen and (max-width: 480px) {
            .header .title h1 {
                font-size: 18px;
            }

            .date-time p {
                font-size: 14px;
            }

            .menu ul li a {
                font-size: 16px;
            }

            .card h3, .card strong {
                font-size: 18px;
            }

            .card {
                padding: 20px;
            }
        }
    </style>

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
            <p class="welcome">Hello! <?php echo $_SESSION['std_name']; ?></p><br><br>
            <nav class="menu">
                <ul>
                    <li><a href="#" class="active">Home</a></li>
                    <li><a href="notice.php">Notice</a></li>
                    <li><a href="details.php">Fees Details</a></li>
                    <li><a href="payment.php">Payment</a></li>
                    <li id="logout-btn"><a href="../api/logout.php">Logout</a></li>
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

            <div class="dtl-section">
                <div class="card total">
                    <div class="photo">
                        <img src="../images/totalfee.jpg" alt="Total Fee">
                    </div>
                    <h3>Total Fee</h3>
                    <strong><?php echo $total_fee; ?></strong>
                </div>
                <div class="card paid">
                    <div class="photo">
                        <img src="../images/paid.jpg" alt="Paid Fee">
                    </div>
                    <h3>Paid Fee</h3>
                    <strong><?php echo $paid_fee; ?></strong>
                </div>
                <div class="card due">
                    <div class="photo">
                        <img src="../images/paymentDue.jpg" alt="Due Fee">
                    </div>
                    <h3>Due Fee</h3>
                    <strong><?php echo $due_fee; ?></strong>
                </div>
            </div>
        </main>
    </div>

</div>
<script src="../js/homepageTimeUpdate.js"></script>
</body>
</html>
