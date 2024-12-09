
<?php
session_start();
$user_profile = $_SESSION["user_name"];
$role = $_SESSION["role"];
if (!$user_profile === true && !$role == "admin") {
    header("location:../index.php");
}
?>


<?php

include '../../api/connection.php';

// Check if payment_id is passed
if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Fetch the data for the given payment_id
    $query = "SELECT payment_id, student_id, student_name, student_email, batch, program, amount, payment_purpose, comment 
              FROM individual_payment 
              WHERE payment_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $payment_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result); // Fetch the existing data
    } else {
        echo "Record not found.";
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    echo "No payment ID provided.";
    exit();
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $student_email = $_POST['student_email'];
    $batch = $_POST['batch'];
    $program = $_POST['program'];
    $amount = $_POST['amount'];
    $payment_purpose = $_POST['payment_purpose'];
    $comment = $_POST['comment'];

    // Update query
    $update_query = "UPDATE individual_payment 
                     SET student_id = ?, student_name = ?, student_email = ?, batch = ?, program = ?, amount = ?, payment_purpose = ?, comment = ? 
                     WHERE payment_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "sssssdsss", $student_id, $student_name, $student_email, $batch, $program, $amount, $payment_purpose, $comment, $payment_id);

    if (mysqli_stmt_execute($update_stmt)) {
        // Redirect to payment page after successful update
        header("Location: ../payment.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_stmt_close($update_stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

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
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input, textarea {
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

        button, .btn {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            margin-top: 10px;
        }

        .btn-save {
            background-color: #4CAF50;
            color: white;
        }

        .btn-save:hover {
            background-color: #45a049;
        }

        .btn-cancel {
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #f44336;
            text-align: center;
        }

        .btn-cancel:hover {
            background-color: #e53935;
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Payment Record</h1>
        <form action="" method="POST">
            <label for="student_id">Student ID:</label>
            <input type="text" id="student_id" name="student_id" value="<?php echo htmlspecialchars($row['student_id']); ?>" required>

            <label for="student_name">Student Name:</label>
            <input type="text" id="student_name" name="student_name" value="<?php echo htmlspecialchars($row['student_name']); ?>" required>

            <label for="student_email">Student Email:</label>
            <input type="email" id="student_email" name="student_email" value="<?php echo htmlspecialchars($row['student_email']); ?>" required>

            <label for="batch">Batch:</label>
            <input type="text" id="batch" name="batch" value="<?php echo htmlspecialchars($row['batch']); ?>" required>

            <label for="program">Program:</label>
            <input type="text" id="program" name="program" value="<?php echo htmlspecialchars($row['program']); ?>" required>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" value="<?php echo htmlspecialchars($row['amount']); ?>" required>

            <label for="payment_purpose">Payment Purpose:</label>
            <input type="text" id="payment_purpose" name="payment_purpose" value="<?php echo htmlspecialchars($row['payment_purpose']); ?>" required>

            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" required><?php echo htmlspecialchars($row['comment']); ?></textarea>

            <button type="submit" class="btn btn-save">Save Changes</button>
            <a href="../payment.php" class="btn btn-cancel">Cancel</a>
        </form>
    </div>
</body>
</html>
