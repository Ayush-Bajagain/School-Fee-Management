<?php
include "../api/connection.php";

// Check if 'id' is present in the GET request
if (isset($_GET["id"])) {
    // Get and sanitize the ID from the GET request
    $id = intval($_GET["id"]); // Ensure $id is an integer

    // Prepare the DELETE query with a placeholder
    $query = "DELETE FROM makepayment WHERE id = ?";

    // Create a prepared statement
    if ($stmt = $conn->prepare($query)) {
        // Bind the ID parameter to the query
        $stmt->bind_param("i", $id);

        // Execute the prepared statement
        $stmt->execute();

        // Close the prepared statement
        $stmt->close();
    }

    // Close the database connection
    $conn->close();
}

// Redirect back to the previous page (if needed)
// If you don't need a redirect, you can remove the following line
header("Location: payment.php");
exit();
?>
