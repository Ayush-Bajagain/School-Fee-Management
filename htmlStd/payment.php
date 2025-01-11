<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../newCss/layout.css">
    <link rel="stylesheet" href="../studentCss/payment.css">
    <title>Advance Payment</title>
</head>

<body>

    <?php
    // Validate session for student
    $user_profile = $_SESSION["email"] ?? null;
    $role = $_SESSION["role"] ?? null;

    if ($user_profile && $role === "student") {
        // Session valid
    } else {
        header("location:../index.php");
        exit;
    }

    // Fetch student details from database
    include("../api/connection.php");

    $program1 = $batch1 = $student_id1 = null;
    $query = "SELECT program, batch, student_id FROM students WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_profile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $program1 = $row['program'];
        $batch1 = $row['batch'];
        $student_id1 = $row['student_id'];
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
                <p><?php echo date("m/d/Y h:i:s A"); ?></p>
            </div>
        </header>

        <div class="wrapper">
            <aside class="sidebar">
                <img src="../images/icon/menu.png" class="menu-icon" width="30" height="30" onclick="openNav()">
                <p class="welcome">Hello! <?php echo htmlspecialchars($_SESSION['std_name']); ?></p><br><br>
                <nav class="menu">
                    <ul id="nav-menu">
                        <li><a href="studentHome.php">Home</a></li>
                        <li><a href="notice.php">Fee Notice</a></li>
                        <li><a href="details.php">History</a></li>
                        <li><a href="payment.php" class="active">Payment</a></li>
                        <li id="logout-bnt"><a href="../api/logout.php" style=" display: block;color: white;text-decoration: none;background-color: #ff4d4d;padding: 8px 30px;border-radius: 5px;">Logout</a></li>
                    </ul>
                </nav>
            </aside>

            <main class="main-content">
                <div class="form-container">
                    <h2>Advance Payment</h2>
                    <img src="../images/esewaqr.jpg" alt="E-Sewa QR Code">

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="number" id="amount" name="amount" required>
                        </div>

                        <div class="form-group">
                            <label for="payment_purpose">Payment Purpose:</label>
                            <select id="payment_purpose" name="payment_purpose">
                                <option value="advance" selected>Advance</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks:</label>
                            <textarea id="remarks" name="remarks" rows="4"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="photo">Upload Payment Screenshot:</label>
                            <input type="file" id="photo" name="photo" accept="image/jpeg, image/png, image/jpg" required>
                        </div>

                        <div class="form-group">
                            <button type="submit">Submit</button>
                        </div>
                    </form>
                </div>

                <?php
                // Handle form submission
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    include("../api/connection.php");

                    $amount = $_POST['amount'];
                    $payment_purpose = $_POST['payment_purpose'];
                    $remarks = $_POST['remarks'];

                    // Handle file upload
                    $photo = $_FILES['photo'];
                    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                    $max_file_size = 2 * 1024 * 1024; // 2MB
                    $target_dir = "uploads/";

                    if (!is_uploaded_file($photo['tmp_name']) || !in_array($photo['type'], $allowed_types) || $photo['size'] > $max_file_size) {
                        echo "<p style='color: red;'>Invalid file upload. Please try again.</p>";
                        exit;
                    }

                    $photo_name = time() . '_' . basename($photo['name']);
                    $target_file = $target_dir . $photo_name;

                    if (!is_writable($target_dir)) {
                        echo "<p style='color: red;'>Upload directory is not writable.</p>";
                        exit;
                    }

                    if (move_uploaded_file($photo['tmp_name'], $target_file)) {
                        $query = "INSERT INTO advance_transaction_details 
                                (student_id, email, program, batch, amount, payment_purpose, photo, remark) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ssssssss", $student_id1, $user_profile, $program1, $batch1, $amount, $payment_purpose, $photo_name, $remarks);

                        if ($stmt->execute()) {
                            echo "<script>alert('Payment details submitted successfully!')</script>";
                        } else {
                            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
                        }

                        $stmt->close();
                    } else {
                        echo "<script>alert('Failed to upload photo. Please try again.')</script>";

                    }

                    $conn->close();
                }
                ?>


                <?php
                include '../api/connection.php';

                $sqlFetch = "SELECT transaction_id, amount, remark, photo, batch, student_id, email, program, payment_purpose 
                FROM advance_transaction_details";

                $result = $conn->query($sqlFetch);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $sqlInsert = "INSERT INTO transaction_details (transaction_id, amount, remark, photo, batch, student_id, email, program, payment_purpose) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                        $stmt = $conn->prepare($sqlInsert);
                        $stmt->bind_param(
                            "idsssssss",
                            $row['transaction_id'],
                            $row['amount'],
                            $row['remark'],
                            $row['photo'],
                            $row['batch'],
                            $row['student_id'],
                            $row['email'],
                            $row['program'],
                            $row['payment_purpose']
                        );

                        if ($stmt->execute()) {
                            $sqlDelete = "DELETE FROM advance_transaction_details WHERE transaction_id = ?";
                            $stmtDelete = $conn->prepare($sqlDelete);
                            $stmtDelete->bind_param("i", $row['transaction_id']);
                            $stmtDelete->execute();
                            $stmtDelete->close();
                        } else {
                            echo "Error inserting record: " . $stmt->error;
                        }

                        $stmt->close();
                    }
                   
                }


                $conn->close();
                ?>







            </main>
        </div>
    </div>


    <script src="../js/navigationRespon.js"></script>
    <script src="../js/homepageTimeUpdate.js"></script>
</body>

</html>