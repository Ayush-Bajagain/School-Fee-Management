<?php
session_start();


$user_profile = $_SESSION["email"] ?? null;
$role = $_SESSION["role"] ?? null;

if (!$user_profile || $role !== "student") {
    header("location:../index.php");
    exit;
}

// Fetch student details from the database
include("../api/connection.php");

// Fetch student data from students table using email
$student_query = "SELECT student_id, batch, program FROM students WHERE email = ?";
$stmt_student = $conn->prepare($student_query);
$stmt_student->bind_param("s", $user_profile);
$stmt_student->execute();
$result_student = $stmt_student->get_result();

if ($result_student->num_rows > 0) {
    $student_data = $result_student->fetch_assoc();
    $student_id = $student_data['student_id'];
    $batch = $student_data['batch'];
    $program = $student_data['program'];
} else {
    die("Student details not found.");
}

// Fetch transaction ID from URL
$transaction_id = $_GET['id'] ?? null;

// Check if transaction ID is valid
if (!$transaction_id) {
    die("Invalid transaction ID.");
}

// Fetch payment details for the selected transaction
$query = "
    SELECT id, amount, payment_field 
    FROM makepayment 
    WHERE id = ?
    UNION ALL
    SELECT id, amount, payment_field 
    FROM other_payment 
    WHERE id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $transaction_id, $transaction_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $amount = $row['amount'];
    $payment_field = $row['payment_field'];
} else {
    die("Transaction details not found.");
}

$stmt->close();
$conn->close();
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
            .form-group input[readonly] {
                background-color: #e9ecef;
                cursor: not-allowed;
            }
            .form-group select {
                width: 100%;
                padding: 8px;
            }

            .photo-section{
                width: 400px;
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
            <p><?php echo date("m/d/Y h:i:s A"); ?></p>
        </div>
    </header>

    <div class="wrapper">
        <a href="notice.php">Back</a>
        <main class="main-content">
            <div class="form-container">
                <h2>Submit Transaction Details</h2>
                
                <div class="photo-section">
                    <label>Pay through e-sewa:</label>
                    <img id="photo-preview" src="../images/esewaqr.jpg" alt="No Photo Uploaded" />
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="transaction_id">Transaction ID:</label>
                        <input type="text" id="transaction_id" name="transaction_id" value="<?php echo htmlspecialchars($transaction_id); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" id="amount" name="amount" value="<?php echo htmlspecialchars($amount); ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="field">Payment Type:</label>
                        <select id="payment-field" name="field" required>
                            <option value="regular" <?php if ($payment_field === 'regular') echo 'selected'; ?>>Regular</option>
                            <option value="examFee" <?php if ($payment_field === 'examFee') echo 'selected'; ?>>Exam Fee</option>
                            <option value="fine" <?php if ($payment_field === 'fine') echo 'selected'; ?>>Fine</option>
                            <option value="other" <?php if ($payment_field === 'other') echo 'selected'; ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks:</label>
                        <textarea id="remarks" name="remarks" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="photo">Upload Payment Proof:</label>
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
                $payment_purpose = $_POST['field'];
                $amount = $_POST['amount'];
                $remarks = $_POST['remarks'];

                // Handle file upload
                $photo = $_FILES['photo'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                $max_file_size = 2 * 1024 * 1024; // 2MB
                $target_dir = "uploads/";

                if (!is_uploaded_file($photo['tmp_name']) || !in_array($photo['type'], $allowed_types) || $photo['size'] > $max_file_size) {
                    echo "<p style='color: red;'>Invalid file upload.</p>";
                    exit;
                }

                $photo_name = time() . '_' . basename($photo['name']);
                $target_file = $target_dir . $photo_name;

                if (move_uploaded_file($photo['tmp_name'], $target_file)) {
                    $query = "INSERT INTO transaction_details (transaction_id, amount, remark, photo, batch, student_id, email, program, payment_purpose) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sdsssssss", $transaction_id, $amount, $remarks, $photo_name, $batch, $student_id, $user_profile, $program, $payment_purpose);

                    if ($stmt->execute()) {
                        echo "<p style='color: green;'>Transaction details submitted successfully!</p>";
                        header("Location: details.php");  // Redirect after success
                        exit;
                    } else {
                        echo "<p style='color: red;'>Database error: " . $stmt->error . "</p>";
                    }
                    $stmt->close();
                } else {
                    echo "<p style='color: red;'>Failed to upload photo. Please try again.</p>";
                }

                $conn->close();
            }
            ?>
        </main>
    </div>
</div>
</body>
</html>
