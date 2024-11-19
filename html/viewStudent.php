<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/viewStudent.css">
    <title>View Student</title>
</head>

<body>
<?php
$user_profile = $_SESSION["user_name"];
$role = $_SESSION["role"];

if ($user_profile == true && $role == "admin") {
} else {
    header("location:../index.php");
}
?>

<div class="container">
    <header class="header">
        <div class="logo">
            <img src="../images/kcmit.jpg" alt="School Logo">
        </div>
        <div class="title">
            <h1>Fee Management System</h1>
        </div>
        <div class="date-time">
            <p>08/30/2024 10:23:15 AM</p>
        </div>
    </header>

    <div class="wrapper">

        <aside class="sidebar">
            <p class="welcome">Hello! ADMIN</p><br><br>
            <nav class="menu">
                <ul>
                    <li><a href="adminHome.php">Dashboard</a></li>
                    <li><a href="#" class="active">Student</a></li>
                    <li><a href="payment.php">Payment</a></li>
                    <li><a href="application.php">Application</a></li>
                    <li id="logout-bnt"><a href="../api/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">

            <a href="student.php"><button class="go-back">Go Back</button></a>

            <div class="navigation">
                <div class="search">
                    <label for="search-std">Search student by name, ID, or email: </label>
                    <input type="text" id="search-std" placeholder="Enter name, ID, or email" onkeyup="filterTable()">
                    <i class="ri-search-line"></i>
                </div>
            </div>

            <section class="student-table">
                <table id="studentTable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Program</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <?php
                    include("../api/connection.php");
                    $sql = "SELECT * FROM students";
                    $result = $conn->query($sql);

                    if (!$result) {
                        die("Invalid query: " . $conn->error);
                    }

                    while ($row = $result->fetch_assoc()) {
                        echo "
                            <tr class='studentRow'>
                                <td>" . $row['student_id'] . "</td>
                                <td>" . $row['full_name'] . "</td>
                                <td>" . $row['email'] . "</td>
                                <td>" . $row['contact_number'] . "</td>
                                <td>" . strtoupper($row['program']) . "</td>
                                <td>
                                    <button class='view-btn'>
                                        <a href='viewStudentClick.php?id=" . $row['student_id'] . "'>View</a>
                                    </button>
                                    <button class='edit-btn'>
                                        <a href='editStudent.php?id=" . $row['student_id'] . "'>Edit</a>
                                    </button>
                                </td>
                            </tr>
                        ";
                    }
                    ?>
                </table>
            </section>

        </main>
    </div>
</div>

<script src="../js/homepageTimeUpdate.js"></script>

<script>
// Function to filter the table based on the search input
function filterTable() {
    let input = document.getElementById("search-std").value.toLowerCase();
    let table = document.getElementById("studentTable");
    let rows = table.getElementsByTagName("tr");

    // Loop through all table rows (skip the header)
    for (let i = 1; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName("td");
        let match = false;

        // Loop through each cell in the row
        for (let j = 0; j < cells.length; j++) {
            if (cells[j]) {
                // Check if the cell's text content matches the search input
                if (cells[j].textContent.toLowerCase().indexOf(input) > -1) {
                    match = true;
                    break;
                }
            }
        }

        // Show or hide the row based on the match
        if (match) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}
</script>

</body>

</html>