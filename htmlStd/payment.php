<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="../cssStd/studentLayout.css">

    <style>
        .menu ul li .active {
            color: royalblue;
        }
        body {
            font-family: Arial, sans-serif;
        }
        .form-container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #218838;
        }
    </style>
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
    </header>

    <div class="wrapper">
        <aside class="sidebar">
            <p class="welcome">Hello! <?php echo htmlspecialchars($_SESSION['std_name']); ?></p><br><br>
            <nav class="menu">
                <ul>
                    <li><a href="studentHome.php">Home</a></li>
                    <li><a href="notice.php">Notice</a></li>
                    <li><a href="details.php">Fees Details</a></li>
                    <li><a href="payment.php" class="active">Payment</a></li>
                    <li id="logout-bnt"><a href="../api/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="form-container">
                <h2>Submit Transaction Details</h2>
                <div class="photo-section">
                    <label>Pay through e-sewa:</label>
                    <img id="photo-preview" src="../images/esewaqr.jpg" alt="No Photo Uploaded" width="150" />
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="transaction_id">Transaction ID:</label>
                        <input type="text" id="transaction_id" name="transaction_id" required />
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" id="amount" name="amount" required />
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks:</label>
                        <textarea id="remarks" name="remarks" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="photo">Upload Photo of Payment:</label>
                        <input type="file" id="photo" name="photo" accept="image/jpeg, image/png, image/jpg" required />
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

                $transaction_id = $_POST['transaction_id'];
                $amount = $_POST['amount'];
                $remarks = $_POST['remarks'];

                // Handle file upload
                $photo = $_FILES['photo'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                $max_file_size = 2 * 1024 * 1024; // 2MB
                $target_dir = "uploads/";

                // Validate file upload
                if (!is_uploaded_file($photo['tmp_name'])) {
                    echo "<p style='color: red;'>Invalid file upload.</p>";
                    exit;
                }

                if (!in_array($photo['type'], $allowed_types)) {
                    echo "<p style='color: red;'>Invalid file type. Only JPG and PNG files are allowed.</p>";
                    exit;
                }

                if ($photo['size'] > $max_file_size) {
                    echo "<p style='color: red;'>File size exceeds the limit of 2MB.</p>";
                    exit;
                }

                // Generate a unique file name
                $photo_name = time() . '_' . basename($photo['name']);
                $target_file = $target_dir . $photo_name;

                // Check if the target directory is writable
                if (!is_writable($target_dir)) {
                    echo "<p style='color: red;'>Upload directory is not writable.</p>";
                    exit;
                }

                // Move the uploaded file
                if (move_uploaded_file($photo['tmp_name'], $target_file)) {
                    // Insert data into database
                    $query = "INSERT INTO transaction_details 
                              (transaction_id, amount, remark, photo, batch, student_id, email, program) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $conn->prepare($query);
                    if ($stmt) {
                        $stmt->bind_param("sdssssss", $transaction_id, $amount, $remarks, $photo_name, $batch1, $student_id1, $user_profile, $program1);

                        if ($stmt->execute()) {
                            echo "<p style='color: green;'>Transaction details submitted successfully!</p>";
                        } else {
                            echo "<p style='color: red;'>Database error: " . $stmt->error . "</p>";
                        }

                        $stmt->close();
                    } else {
                        echo "<p style='color: red;'>Database statement preparation error: " . $conn->error . "</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Failed to upload photo. Please try again.</p>";
                }

                $conn->close();
            }
            ?>
        </main>
    </div>
</div>

<script src="../js/homepageTimeUpdate.js"></script>
</body>
</html>
