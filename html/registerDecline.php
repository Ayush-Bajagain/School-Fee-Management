<?php
include "../api/connection.php";


if (isset($_GET["id"])) {

    $id = intval($_GET["id"]);


    $query = "DELETE FROM register WHERE id = ?";

    if ($stmt = $conn->prepare($query)) {
        // Bind the ID parameter to the query
        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->close();
    }

    $conn->close();
}

header("Location: application.php");
exit();
?>
