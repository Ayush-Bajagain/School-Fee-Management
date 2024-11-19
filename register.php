<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* CSS for styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 90%;
            max-width: 400px;
            border-radius: 8px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #000;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-size: 14px;
            text-align: left;
            color: #000;
        }
        input[type="text"], input[type="password"] {
            background-color: transparent;
            border: none;
            border-bottom: 2px solid #000;
            padding: 10px;
            font-size: 16px;
            color: #000;
            outline: none;
            transition: border-bottom 0.3s ease-in-out;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-bottom: 2px solid royalblue;
        }
        #submit-btn {
            background-color: royalblue;
            color: white;
            border: none;
            padding: 10px 0;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        #submit-btn:hover {
            background-color: #003cbf;
        }
        .login-action {
            font-size: 14px;
            padding: 20px;
        }
        .login-action a {
            margin-left: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Register</h2>

    <!-- Form with validation check in the onsubmit event -->
    <form action="#" method="post" onsubmit="return validateForm()">
        <label for="fullName">Full Name</label>
        <input type="text" id="fullName" name="fullName" required/>

        <label for="studentId">Student ID</label>
        <input type="text" id="studentId" name="id" required/>

        <label for="batch">Batch</label>
        <input type="text" id="batch" name="batch" required/>

        <label for="email">Email</label>
        <input type="text" name="email" id="email" required/>

        <label for="username">Username</label>
        <input type="text" name="username" id="username" required/>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required/>

        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" required/>

        <input type="submit" value="Submit" name="submit" id="submit-btn"/>
    </form>
    <p class="login-action">Already have an account?<a href="index.php">Login</a></p>
</div>

<?php
if (isset($_POST['submit'])) {
    // Collect and sanitize form data
    $id = $_POST['id'] ?? '';
    $name = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $batch = $_POST['batch'] ?? '';
    $username = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';



    // Check if any required field is empty
    if (empty($id) || empty($name) || empty($email) || empty($batch) || empty($username) || empty($pass)) {
        echo "<script>alert('All fields are required.');</script>";
        exit;
    }

    // Include database connection
    include 'api/connection.php';

    // Check if the entered student details match a record in the students table
    $checkQuery = "SELECT * FROM students WHERE full_name = ? AND student_id = ? AND email = ? AND batch = ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, 'ssss', $name, $id, $email, $batch);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);

    // If a matching record is found, proceed with registration
    if (mysqli_num_rows($result) > 0) {





        

$query = "INSERT INTO register (id, full_name, email, username, password, batch) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    // Bind parameters and execute the query
    mysqli_stmt_bind_param($stmt, 'ssssss', $id, $name, $email, $username, $pass, $batch);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Form submitted successfully. Wait for approval by administration.');</script>";
    } else {
        echo "<script>alert('Error: Could not register');</script>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('Error preparing statement');</script>";
}






        
    } else {
        // Display an alert if no matching student details are found
        echo "<script>alert('Student details don\'t match with the college database');</script>";
    }

    // Close statement and connection
    mysqli_stmt_close($checkStmt);
    mysqli_close($conn);
}
?>


<script>
    function validateForm() {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm-password").value;

        // Password validation: at least 8 characters, 1 uppercase, and 1 number
        const passwordRegex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (!passwordRegex.test(password)) {
            alert("Password must be at least 8 characters long and include at least one uppercase letter and one number.");
            return false;
        }

        // Confirm password validation
        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }

        return true;
    }
</script>
</body>
</html>
