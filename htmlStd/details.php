<?php
session_start();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>payment details</title>
    <link rel="stylesheet" href="../newCss/layout.css">
    <link rel="stylesheet" href="../studentCss/detailsHistory.css">
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
                <img src="../images/icon/menu.png" class="menu-icon" width="30" height="30" onclick="openNav()">
                <p class="welcome">Hello! <?php echo $_SESSION['std_name']; ?></p><br><br>

                <nav class="menu">
                    <ul id="nav-menu">
                        <li><a href="studentHome.php" >Home</a></li>
                        <li><a href="notice.php" >Fee Notice</a></li>
                        <li><a href="#" class="active">History</a></li>
                        <li><a href="payment.php">Payment</a></li>
                        <li id="logout-bnt"><a href="../api/logout.php" style=" display: block;color: white;text-decoration: none;background-color: #ff4d4d;padding: 8px 30px;border-radius: 5px;">Logout</a></li>
                    </ul>
                </nav>
            </aside>

            <main class="main-content">

                <div class="nav">
                    <h2>History Of Your Transaction</h2>
                </div>


                <?php
                    include("../api/connection.php");

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
                            <th>Payment Purpose</th>
                            <th>Amount</th>
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
                            $payment_purpose = htmlspecialchars($row['payment_purpose']);
                            $amount = htmlspecialchars($row['amount']);
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
                                <td>{$payment_purpose}</td>
                                <td>Rs.{$amount}</td>
                                <td>
                                    <a href='printHistory.php?transaction_id={$transaction_id}' class='print-btn'>Print</a>
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

                <?php
                $conn->close();
                ?>
            </main>
        </div>
    </div>
    <script src="../js/homepageTimeUpdate.js"></script>
    <script src="../js/navigationRespon.js"></script>

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
</body>

</html>