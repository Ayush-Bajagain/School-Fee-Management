<?php
session_start();
?>

<?php
       $user_profile = $_SESSION["user_name"];
       $role = $_SESSION["role"];

       if ($user_profile == true && $role == "admin") {

       }else{
           header("location:../index.php");
       }
    
    ?>

<?php
include '../api/connection.php';

// Check if student_id is provided in the URL
if (isset($_GET['student_id']) && !empty($_GET['student_id'])) {
    // Sanitize the student_id to prevent SQL injection
    $student_id = $conn->real_escape_string($_GET['student_id']);

    // Fetch student data from the database
    $query = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $student_id);  // Use 's' for string type
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        die("Student not found!");
    }
} else {
    die("Invalid student ID!");
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $email = $conn->real_escape_string($_POST['email']);
    $current_address = $conn->real_escape_string($_POST['current_address']);
    $permanent_address = $conn->real_escape_string($_POST['permanent_address']);
    $program = $conn->real_escape_string($_POST['program']);
    $father_name = $conn->real_escape_string($_POST['father_name']);
    $mother_name = $conn->real_escape_string($_POST['mother_name']);
    $batch = $conn->real_escape_string($_POST['batch']);

    // Update query
    $update_query = "UPDATE students SET full_name = ?, contact_number = ?, email = ?, current_address = ?, permanent_address = ?, program = ?, father_name = ?, mother_name = ?, batch = ? WHERE student_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param(
        "ssssssssss",
        $full_name,
        $contact_number,
        $email,
        $current_address,
        $permanent_address,
        $program,
        $father_name,
        $mother_name,
        $batch,
        $student_id  // Use the sanitized student_id from earlier
    );

    if ($stmt->execute()) {
        echo "<script>alert('Student information updated successfully!'); window.location.href='viewStudent.php';</script>";
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
    rel="stylesheet"
/>
    <title>Edit Student</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 500px; }
        h2 { text-align: center; margin-bottom: 20px; }
        label { margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"] { padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; width: 100%; border-radius: 4px; }
        button { padding: 10px 20px; background-color: #4CAF50; color: #fff; border: none; cursor: pointer; width: 100%; border-radius: 4px; }
        button:hover { background-color: #45a049; }

        #back-btn{
            position: absolute;
            top:10px;
            left: 10px;
            width:fit-content;
            height: fit-content;
            text-decoration: none;
        }

        #back-btn i{
            color:#333;
            font-size: 40px;
            cursor: pointer;
            transition: all ease .3s;

        }

        #back-btn i:hover{
           color: royalblue;
           font-size:50px; 

        }
    </style>
</head>
<body>
    <a href="viewStudent.php" id="back-btn"><i class="ri-arrow-left-line"></i></a>

    <div class="container">
        <h2>Edit Student Information</h2>
        <form method="POST" action="">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>">

            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>

            <label for="contact_number">Contact Number</label>
            <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($student['contact_number']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>

            <label for="current_address">Current Address</label>
            <input type="text" id="current_address" name="current_address" value="<?php echo htmlspecialchars($student['current_address']); ?>" required>

            <label for="permanent_address">Permanent Address</label>
            <input type="text" id="permanent_address" name="permanent_address" value="<?php echo htmlspecialchars($student['permanent_address']); ?>" required>

            <label for="program">Program</label>
            <input type="text" id="program" name="program" value="<?php echo htmlspecialchars($student['program']); ?>" required>

            <label for="father_name">Father's Name</label>
            <input type="text" id="father_name" name="father_name" value="<?php echo htmlspecialchars($student['father_name']); ?>" required>

            <label for="mother_name">Mother's Name</label>
            <input type="text" id="mother_name" name="mother_name" value="<?php echo htmlspecialchars($student['mother_name']); ?>" required>

            <label for="batch">Batch</label>
            <input type="text" id="batch" name="batch" value="<?php echo htmlspecialchars($student['batch']); ?>" required>

            <button type="submit">Update Student</button>
        </form>
    </div>

</body>
</html>
