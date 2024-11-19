<?php
if (isset($_POST['registerBtn'])) {

    // Retrieve form data
    $username = $_POST['fullName'];
    $student_id = $_POST['studentId'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];
    $program = $_POST['program'];
    $role = "student";

    // Connect to the database
    $connect = mysqli_connect('localhost', 'root', '', 'student') or die('Connection error!');

    // Prepare and execute the SQL query to insert data
    $stmt = $connect->prepare("INSERT INTO register (student_name, student_id, email, contact, password, program, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $student_id, $email, $contact, $password, $program, $role);

    if ($stmt->execute()) {
        echo '<script>alert("Registration successful")</script>';
        header('location:../'); // Ensure this is the correct redirection path
    } else {
        echo '<script>alert("Registration failed")</script>';
    }

    // Close the statement and connection
    $stmt->close();
    mysqli_close($connect);
}
?>
