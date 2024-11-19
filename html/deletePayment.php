<?php
session_start();
include("../api/connection.php");

// Check if the user is an admin and the ID is passed
if (!isset($_SESSION["user_name"]) || $_SESSION["role"] !== "admin") {
    header("location:../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];

    // Prepare the DELETE query
    $query = "DELETE FROM transaction_details WHERE transaction_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $transaction_id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Transaction deleted successfully.');
                    window.location.href = 'payment.php';
                </script>";
        } else {
            echo "<script>
                    alert('Failed to delete the transaction: " . $stmt->error . "');
                    window.location.href = 'payment.php';
                </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Failed to prepare the delete query: " . $conn->error . "');
                window.location.href = 'payment.php';
            </script>";
    }
} else {
    echo "<script>
            alert('Invalid request. No ID provided.');
            window.location.href = 'payment.php';
        </script>";
}

$conn->close();
?>
