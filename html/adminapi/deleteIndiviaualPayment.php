
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

if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Prepare the DELETE query
    $query = "DELETE FROM individual_payment WHERE payment_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $payment_id);

    // Execute the query and handle errors
    if (mysqli_stmt_execute($stmt)) {
        // Redirect to ../payment.php after successful deletion
        header("Location: ../payment.php");
        exit();
    } else {
        echo "Error: Could not delete the record. " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Error: No payment ID provided.";
}

// Close the database connection
mysqli_close($conn);
?>
