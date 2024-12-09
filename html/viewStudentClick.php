<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Details</title>
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }


        .wrapper {
            margin-top: 20px;
        }


        .main-content {
            padding: 20px;
        }

        .go-back {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        .go-back:hover {
            background-color: #0056b3;
        }


        .student-details {
            margin-top: 20px;
        }

        .student-details h2 {
            margin-bottom: 20px;
            font-size: 22px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }


        .photo {
            text-align: center;
            margin-bottom: 20px;
        }

        .photo img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #007bff;
            padding: 5px;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #f8f8f8;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
        }

        td {
            background-color: #fafafa;
        }

        #payment-noti{
            color: red;
            font-weight:600;
            padding:1px;
        }


        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            table, th, td {
                font-size: 14px;
            }

            .photo img {
                width: 100px;
                height: 100px;
            }
        }

    </style>
</head>

<body>
    <?php
        $user_profile = $_SESSION["user_name"];
        $role = $_SESSION["role"];

        if ($user_profile == true && $role == "admin") {
        } else {
            header("location:../index.php");
        }

        // Check if student ID is passed
        if (isset($_GET['id'])) {
            $student_id = $_GET['id'];

            include("../api/connection.php");

            // Fetch student details
            $sql = "SELECT s.student_id, s.full_name, s.email, s.contact_number, s.program, s.batch, s.photo, s.father_name, s.mother_name, s.current_address, s.permanent_address, f.total_fee, f.paid_fee, f.due_fee FROM students s LEFT JOIN fee_details f ON s.student_id = f.student_id WHERE s.student_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                echo "<script>alert('Student not found!'); window.location.href = 'student.php';</script>";
            }

            $stmt->close();
            $conn->close();
        } else {
            header("location:student.php");
        }
    ?>

    <div class="container">

        <div class="wrapper">

            <main class="main-content">
                <a href="viewStudent.php"><button class="go-back">Go Back</button></a>

                <section class="student-details">
                    <h2>Details of <?php echo $row['full_name']; ?></h2>

                    <div class="photo">
                        <img src="uploads/<?php echo $row['photo']; ?>" alt="Student Photo">
                    </div>

                    <table>
                        <tr>
                            <th>ID</th>
                            <td><?php echo $row['student_id']; ?></td>
                        </tr>
                        <tr>
                            <th>Full Name</th>
                            <td><?php echo $row['full_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo $row['email']; ?></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td><?php echo $row['contact_number']; ?></td>
                        </tr>
                        <tr>
                            <th>Program</th>
                            <td><?php echo strtoupper($row['program']); ?></td>
                        </tr>
                        <tr>
                            <th>Batch</th>
                            <td><?php echo strtoupper($row['batch']); ?></td>
                        </tr>
                        <tr>
                            <th>Father's Name</th>
                            <td><?php echo $row['father_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Mother's Name</th>
                            <td><?php echo $row['mother_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Current Address</th>
                            <td><?php echo $row['current_address']; ?></td>
                        </tr>
                        <tr>
                            <th>Permanent Address</th>
                            <td><?php echo $row['permanent_address']; ?></td>
                        </tr>
                        <tr>
                            <th>Total Fee</th>
                            <td><?php echo $row['total_fee']; ?></td>
                        </tr>
                        <tr>
                            <th>Paid Fee</th>
                            <td><?php echo $row['paid_fee']; ?></td>
                        </tr>
                        <tr>
                            <th>Due Fee</th>
                            <td><?php echo $row['due_fee']; ?></td>
                        </tr>
                    </table>
                </section>
            </main>
        </div>
    </div>

<script src="../js/homepageTimeUpdate.js"></script>
</body>

</html>
