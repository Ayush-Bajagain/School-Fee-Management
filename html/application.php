<?php
session_start();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>application</title>
    <link rel="stylesheet" href="../cssStd/studentLayout.css">
    <link rel="stylesheet" href="../css/application.css">


    <style>
        .btn-accept-delete {
            text-decoration: none;
            border: 1px solid #00000044;
            padding: 5px 20px;
            margin-left: 12px;
            font-size: 14px;
            color: #fff;
            border-radius: 10px;
            opacity: 80%;
        }

        .btn-accept-delete:hover {
            opacity: 100%;
        }

        .accept {
            background-color: green;
        }

        .delete {
            background-color: #f00;
        }
    </style>


</head>

<body>

<?php
       $user_profile = $_SESSION["user_name"];
       $role = $_SESSION["role"];

       if ($user_profile == true && $role == "admin") {

       }else{
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
                        <li><a href="adminHome.php">Dashbord</a></li>
                        <li><a href="student.php">Student</a></li>
                        <li><a href="payment.php">Payment</a></li>
                        <li><a href="application.php" class="active">Application</a></li>
                        <li id="logout-bnt"><a href="../api/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>




            <main class="main-content">

               <div class="navigation">
                    <h2>Register application : </h2>
               </div>



               
               <section class="student-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Batch</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <?php
                    include("../api/connection.php");

                    // Fetch all records from the 'register' table
                    $sql = "SELECT * FROM register";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "
                <tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($row['full_name']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['batch']) . "</td>
                    <td>
                        <a href='registerAccept.php?id=" . urlencode($row['id']) . "' class='btn accept btn-accept-delete'>Accept</a>
                        <a href='registerDecline.php?id=" . urlencode($row['id']) . "' class='btn delete btn-accept-delete'>Delete</a>
                    </td>
                </tr>
              ";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No records found</td></tr>";
                    }

                    // Close the database connection
                    $conn->close();
                    ?>




                </table>
            </section>





            </main>


        </div>

    </div>
    <script src="../js/homepageTimeUpdate.js"></script>
</body>

</html>