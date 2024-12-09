<?php
session_start();
$user_profile = $_SESSION["email"] ?? null;
$role = $_SESSION["role"] ?? null;

if (!$user_profile || $role !== "student") {
    header("location:../index.php");
    exit;
}





include '../api/connection.php';

if (!isset($_GET['payment_id'])) {
    die("No payment ID provided!");
}

$payment_id = $_GET['payment_id'];

// Fetch payment details for the provided payment_id
$query = "SELECT payment_id, student_id, student_email, batch, program, amount, payment_purpose 
          FROM individual_payment 
          WHERE payment_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $payment_id);  // Change to integer parameter
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Payment record not found.");
}

$row = mysqli_fetch_assoc($result);

$program = $row['program'];
$batch = $row['batch'];
$student_id = $row['student_id'];
$student_email = $row['student_email'];


mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Now</title>
    <style>
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input,
        select,
        textarea {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        textarea {
            height: 80px;
            resize: none;
        }

        img.qr-image {
            width: 100%;
            max-width: 200px;
            display: block;
            margin-bottom: 15px;
        }

        button {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #007BFF;
            color: white;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Pay Now</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="qr-section">
                <label for="qr-photo">Scan QR Code:</label>
                <img src="../images/esewaqr.jpg" alt="QR Code" class="qr-image">
            </div>

            <label for="amount">Amount:</label>
            <input type="text" id="amount" name="amount" value="<?php echo htmlspecialchars($row['amount']); ?>" readonly>

            <label for="payment_purpose">Payment Purpose:</label>
            <input type="text" id="payment_purpose" name="payment_purpose" value="<?php echo htmlspecialchars($row['payment_purpose']); ?>" readonly>



            <label for="remark">Remark:</label>
            <textarea id="remark" name="remark" placeholder="Enter your remark" required></textarea>

            <label for="photo">Upload Payment Proof:</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>

            <button type="submit" class="btn btn-submit">Pay</button>
        </form>
    </div>
</body>

</html>




<?php
include '../api/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $amount = $_POST['amount'];
    $remark = $_POST['remark'];
    $payment_purpose = $_POST['payment_purpose'];

    $photo = $_FILES['photo'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $max_file_size = 2 * 1024 * 1024; // 2MB
    $target_dir = "uploads/";

    // Check if a file is uploaded
    if ($photo['error'] !== UPLOAD_ERR_NO_FILE) {
        // Validate file type and size
        if (!in_array($photo['type'], $allowed_types)) {
            echo "<p style='color: red;'>Invalid file type. Only JPEG, PNG, and JPG are allowed.</p>";
            exit;
        }

        if ($photo['size'] > $max_file_size) {
            echo "<p style='color: red;'>File size exceeds the maximum limit of 2MB.</p>";
            exit;
        }

        $photo_name = time() . '_' . basename($photo['name']);
        $target_file = $target_dir . $photo_name;

        // Move the file to the uploads directory
        if (move_uploaded_file($photo['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO transaction_details (transaction_id, amount, remark, photo, batch, student_id, email, program, payment_purpose) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("idsssssss", $payment_id, $amount, $remark, $photo_name, $batch, $student_id, $student_email, $program, $payment_purpose);

            if ($stmt->execute()) {
                echo "<script>
                    alert('Data successfully inserted.');
                    window.location.href = 'notice.php';
                </script>";
            } else {
                echo "<script>
                    alert('Error inserting data: " . $stmt->error . "');
                </script>";
            }
            $stmt->close();
        } else {
            echo "<script>
                alert('Error uploading file.');
            </script>";
        }
    } else {
        echo "<p style='color: red;'>No file was uploaded. Please select a file.</p>";
    }

    $conn->close();
}
?>