<?php
    session_start();
    $user_profile = $_SESSION["user_name"];
    $role = $_SESSION["role"];

    if ($user_profile == true && $role == "admin") {
    } else {
        header("location:../index.php");
        exit();  
    }

    include('../api/connection.php');


    if (isset($_GET['id'])) {
        
        $id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

        
        $query = "SELECT full_name, batch, program, email FROM students WHERE student_id = ?";

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $id);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $full_name = htmlspecialchars($row['full_name']);
                $batch = htmlspecialchars($row['batch']);
                $program = htmlspecialchars($row['program']);
                $email = htmlspecialchars($row['email']);
            }

            $stmt->close();
        } else {
            echo "Error preparing the query.";
        }
    } else {
        echo "No ID parameter provided.";
    }

    $conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <style>
        /* General Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f5ff; /* Light background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Form Container */
        .form-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        /* Form Title */
        h2 {
            color: royalblue;
            margin-bottom: 20px;
        }

        /* Form Elements */
        label {
            display: block;
            font-weight: bold;
            margin: 10px 0 5px;
            text-align: left;
            color: #333;
        }

        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid royalblue;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 15px;
            font-size: 14px;
        }

        select {
            background-color: white;
        }

        textarea {
            resize: none;
        }

        button {
            background-color: royalblue;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: darkblue;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .form-container {
                padding: 15px;
            }
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h2>Payment Form</h2>
        <form action="" method="POST"> 
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" required placeholder="Enter amount">
            
            <label for="payment_purpose">Payment Purpose:</label>
            <select id="payment_purpose" name="payment_purpose" required>
                <option value="">Select Purpose</option>
                <option value="regular">Regular</option>
                <option value="fine">Fine</option>
                <option value="exam_fee">Exam Fee</option>
                <option value="other">Other</option>
            </select>
            
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" rows="4" placeholder="Enter any comments"></textarea>
            
            <button type="submit">Submit Payment</button>
        </form>
    </div>
</body>
</html>


<?php

include('../api/connection.php'); 


$student_id = $id; 
$student_name = $full_name;  
$student_email = $email;  



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = $_POST['amount'];
    $payment_purpose = $_POST['payment_purpose'];
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    
    $query = "INSERT INTO individual_payment (student_id, student_name, student_email, batch, program, amount, payment_purpose, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    
    if ($stmt = $conn->prepare($query)) {
        
        $stmt->bind_param("sssssdss", $student_id, $student_name, $student_email, $batch, $program, $amount, $payment_purpose, $comment);
        
        
        if ($stmt->execute()) {
            
            echo "<script>
                    alert('Payment data stored successfully!');
                    window.location.href = 'viewStudent.php';
                  </script>";
        } else {

            echo "<script>
                    alert('Error: Could not store data.');
                    window.history.back();  
                  </script>";
        }
        
        $stmt->close();
    } else {
        
        echo "Error preparing the query: " . $conn->error;
    }
}

$conn->close();
?>


