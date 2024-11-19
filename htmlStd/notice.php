<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>notices</title>
    <link rel="stylesheet" href="../cssStd/studentLayout.css">
    <link rel="stylesheet" href="../cssStd/notice.css">


    <style>
        .menu ul li .active {
            color: royalblue;
        }
    </style>
    
</head>
<body>

    <?php
        $user_profile = $_SESSION["email"];
        $role = $_SESSION["role"];

        
        if ($user_profile == true && $role == "student") {
            
        }else{
            header("location:../index.php");
        }

    ?>


<?php

include("../api/connection.php");


$email = $user_profile;

$query = "SELECT program, batch FROM students WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {
        $program = $row['program'];
        $batch = $row['batch'];
        
    }
}


$stmt->close();
$conn->close();



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
                <p class="welcome">Hello! <?php echo $_SESSION['std_name'] ?></p><br><br>
                <nav class="menu">
                    <ul>
                        <li><a href="studentHome.php" >Home</a></li>
                        <li><a href="notice.php" class="active">Notice</a></li>
                        <li><a href="details.php">Fees Details</a></li>
                        <li><a href="payment.php">Payment</a></li>
                        <li id="logout-bnt"><a href="../api/logout.php">Logout</a></li>
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
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include("../api/connection.php");

        // Ensure $program and $batch are set before using them in the query
        if (isset($program, $batch) && !empty($program) && !empty($batch)) {
            $sql = "SELECT * FROM makepayment WHERE program = ? AND batch = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ss", $program, $batch);
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
                                <td>
                                    <button class='view-btn'>
                                        <a href='payment.php?id=" . htmlspecialchars($row['id']) . "'>Pay</a>
                                    </button>
                                </td>
                            </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='7'>No records found.</td></tr>";
                }

                $stmt->close();
            } else {
                die("Failed to prepare statement: " . $conn->error);
            }
        } else {
            echo "<tr><td colspan='6'>Program or Batch is not set.</td></tr>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </tbody>
</table>

            </main>
        </div>

    </div>
    <script src="../js/homepageTimeUpdate.js"></script>

   
</body>
</html>



