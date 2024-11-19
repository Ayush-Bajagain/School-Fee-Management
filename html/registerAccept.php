<?php

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];


    include("../api/connection.php");


    $sql = "SELECT * FROM register WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row["id"];
        $username = $row["username"];
        $email = $row["email"];
        $password = $row["password"];



        $role = "student";
        $query = "INSERT INTO users (user_id, username, email, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $id, $username, $email, $password, $role);

        if ($stmt->execute()) {

            $delete_sql = "DELETE FROM register WHERE id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("s", $student_id);

            if ($stmt->execute()) {

                echo "<script>alert('Accepted and moved to user.');</script>";
                header("Location: application.php");
                exit();
            } else {

                echo "Failed to delete from register: " . $conn->error;
            }

        } else {

            echo "Failed to accept: " . $conn->error;
        }

    } else {
        echo "Record not found";
    }

    
    $stmt->close();
    $conn->close();
} else {
    echo "No student ID provided.";
}

?>
