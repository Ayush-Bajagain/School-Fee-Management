<?php
session_start();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>payment details</title>
    <link rel="stylesheet" href="../cssStd/studentLayout.css">
    <link rel="stylesheet" href="../cssStd/details.css">


    <style>
        .menu ul li .active {
            color: royalblue;
        }


        /* General table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 16px;
    text-align: left;
}

table thead th {
    background-color: #f4f4f4;
    color: #333;
    padding: 10px;
    border: 1px solid #ddd;
}

table tbody td {
    padding: 10px;
    border: 1px solid #ddd;
}

table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tbody tr:hover {
    background-color: #f1f1f1;
}

/* Thumbnail image styling */
table tbody td img {
    width: 100px;
    height: auto;
    border-radius: 4px;
    transition: transform 0.3s ease;
}

table tbody td img:hover {
    transform: scale(1.1);
}

/* Print button styling */
.print-btn {
    display: inline-block;
    padding: 8px 15px;
    background-color: royalblue;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.print-btn:hover {
    background-color: navy;
}

/* Popup styling */
.popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.popup-content {
    max-width: 90%;
    max-height: 90%;
}

.popup-content img {
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: 100%;
    display: block;
    border: 4px solid white;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

/* Close popup when clicking outside */
.popup:hover {
    cursor: pointer;
}

/* General body styles for a cleaner layout */
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
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
                        <li><a href="studentHome.php">Home</a></li>
                        <li><a href="notice.php">Notice</a></li>
                        <li><a href="details.php" class="active">Fees Details</a></li>
                        <li><a href="payment.php">Payment</a></li>
                        <li id="logout-bnt"><a href="../api/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>
            <main class="main-content">

                <div class="nav">
                    <h2>History Of Your Transaction</h2>
                </div>





                <?php
include("../api/connection.php");

// Fetch data from payment_history table
$sql = "SELECT * FROM payment_history where email = '$user_profile'";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<table border="1" cellspacing="0" cellpadding="10">
    <thead>
        <tr>
            <th>Transaction ID</th>
            <th>Student ID</th>
            <th>Email</th>
            <th>Batch</th>
            <th>Program</th>
            <th>Photo</th>
            <th>Remark</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
            $transaction_id = htmlspecialchars($row['transaction_id']);
            $student_id = htmlspecialchars($row['student_id']);
            $email = htmlspecialchars($row['email']);
            $batch = htmlspecialchars($row['batch']);
            $program = htmlspecialchars($row['program']);
            $photo = htmlspecialchars($row['photo']);
            $remark = htmlspecialchars($row['remark']);
            $photoPath = "../htmlStd/uploads/" . $photo; // Path to photo

            echo "
            <tr>
                <td>{$transaction_id}</td>
                <td>{$student_id}</td>
                <td>{$email}</td>
                <td>{$batch}</td>
                <td>{$program}</td>
                <td>
                    <a href='#' onclick='showPopup(\"{$photoPath}\")'>
                        <img src='{$photoPath}' alt='Photo' width='100' style='cursor: pointer;'>
                    </a>
                </td>
                <td>{$remark}</td>
                <td>
                    <a href='abc.php?transaction_id={$transaction_id}' class='print-btn'>Print</a>
                </td>
            </tr>
            ";
        }
        ?>
    </tbody>
</table>

<div id="photoPopup" class="popup" onclick="hidePopup()">
    <div class="popup-content">
        <img id="popupImage" src="" alt="Popup Photo">
    </div>
</div>

<script>
function showPopup(imageSrc) {
    const popup = document.getElementById('photoPopup');
    const popupImage = document.getElementById('popupImage');
    popupImage.src = imageSrc;
    popup.style.display = 'flex';
}

function hidePopup() {
    const popup = document.getElementById('photoPopup');
    popup.style.display = 'none';
}
</script>

<?php
$conn->close();
?>





            </main>
        </div>

    </div>
    <script src="../js/homepageTimeUpdate.js"></script>
</body>

</html>