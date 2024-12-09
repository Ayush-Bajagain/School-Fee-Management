<?php
session_start();
include("../api/connection.php");

if (!isset($_SESSION["user_name"]) || $_SESSION["role"] !== "admin") {
    header("location:../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];

    // Initialize a flag to track deletion status
    $deleted = false;

    // Prepare the DELETE query for transaction_details
    $query = "DELETE FROM transaction_details WHERE transaction_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $transaction_id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $deleted = true;
        }

        $stmt->close();
    }

    // If not deleted from transaction_details, try other_transaction_details
    if (!$deleted) {
        $query1 = "DELETE FROM other_transaction_details WHERE transaction_id = ?";
        if ($stmt = $conn->prepare($query1)) {
            $stmt->bind_param("s", $transaction_id);

            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $deleted = true;
            }

            $stmt->close();
        }
    }

    // Provide feedback to the user based on deletion status
    if ($deleted) {
        echo "<script>
                alert('Transaction deleted successfully.');
                window.location.href = 'payment.php';
            </script>";
    } else {
        echo "<script>
                alert('Failed to delete the transaction. Transaction ID not found.');
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
