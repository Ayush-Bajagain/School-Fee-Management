<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="../newCss/layout.css">
    <link rel="stylesheet" href="../newCss/addStudent.css">
</head>
<body>
    <?php
    
        $user_profile = $_SESSION["user_name"];
        $role = $_SESSION["role"];

        if (!$user_profile || $role !== "admin") {
            header("location:../index.php");
            exit();
        }
    ?>

    <?php

            
        include("../api/connection.php");

        
        $sql_count = "SELECT COUNT(*) as total_rows FROM transaction_details";
        $result_count = $conn->query($sql_count);

        if ($result_count->num_rows > 0) {
            $row_count = $result_count->fetch_assoc();
            $no_row1 = $row_count['total_rows'] . "<br>";
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
        </header>

        <div class="wrapper">
        <aside class="sidebar">
                    <img src="../images/icon/menu.png" class="menu-icon" width="30" height="30" onclick="openNav()">
                    <p class="welcome">Hello! ADMIN</p><br><br>
                    <nav class="menu">
                        <ul id="nav-menu">
                            <li><a href="adminHome.php">Dashboard</a></li>
                            <li><a href="#" class="active">Student</a></li>
                            <li><a href="payment.php">Payment <?php 
                            if($no_row1>1){
                                echo "<sup id= 'payment-noti'>$no_row1</sup>";
                            } 
                        ?></a></li>
                            <li><a href="application.php">Application</a></li>
                            <li id="logout-bnt"><a href="../api/logout.php" style=" display: block;color: white;text-decoration: none;background-color: #ff4d4d;padding: 8px 30px;border-radius: 5px;">Logout</a></li>
                        </ul>
                    </nav>
                </aside>

            <main class="main-content">
                <a href="student.php"><button class="go-back">Go Back</button></a>

                <!-- Student Form -->
                <form action="" method="post" enctype="multipart/form-data" id="student-form">
                    <div class="photo">
                        <section id="image-field">
                            <img id="photo_preview" src="" alt="Student Photo" style="display: none; width: fit-content; max-width:200px; height: 100%; background: red;">
                        </section>
                        <input type="file" name="file" id="pp_photo" accept="image/*"  required>
                    </div>

                    <div class="std-name box">
                        <span>
                            <label for="fullName">Full Name</label>
                            <input type="text" id="fullName" name="full_name" required>
                        </span>
                        <span>
                            <label for="id">ID</label>
                            <input type="text" id="id" name="id" required>
                        </span>
                    </div>

                    <div class="contact box">
                        <span>
                            <label for="phone">Phone</label>
                            <input type="text" id="phone" name="phone_number" required>
                        </span>
                        <span>
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </span>
                    </div>

                    <div class="address box">
                        <span>
                            <label for="temp-address">Present Address</label>
                            <input type="text" id="temp-address" name="temp_address" required>
                        </span>
                        <span>
                            <label for="p-address">Permanent Address</label>
                            <input type="text" id="p-address" name="per_address" required>
                        </span>
                    </div>

                    <div class="gender">
                        Gender:
                        <span>
                            <input type="radio" name="gender" value="male" id="male" required>
                            <label for="male">Male</label>

                            <input type="radio" name="gender" value="female" id="female" required>
                            <label for="female">Female</label>

                            <input type="radio" name="gender" value="other" id="other" required>
                            <label for="other">Other</label>
                        </span>
                    </div>

                    <div class="program">
                        <span>
                            <label for="program">Program</label>
                            <select name="program" id="program" required>
                                <option value="bca">BCA</option>
                                <option value="bba">BBA</option>
                                <option value="bim">BIM</option>
                            </select>
                        </span>
                        <span>
                            <label for="batch">Batch</label>
                            <select name="batch" id="batch" required>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>

                            </select>
                        </span>
                    </div>

                    <div class="parent box">
                        <span>
                            <label for="father-name">Father's Name</label>
                            <input type="text" id="father-name" name="father_name" required>
                        </span>
                        <span>
                            <label for="mother-name">Mother's Name</label>
                            <input type="text" id="mother-name" name="mother_name" required>
                        </span>
                    </div>

                    <div class="amount-box box">
                        <span>
                            <label for="total_fee">Total Fee</label>
                            <input type="number" id="total_fee" name="total_fee" required>
                        </span>
                        <span>
                            <label for="paid-fee">Paid Fee</label>
                            <input type="number" id="paid-fee" name="paid_fee" required>
                        </span>
                    </div>

                    <input type="submit" value="Submit" name="submit" id="submit-btn">
                    <p class="error" id="error-msg" style="color: red;"></p>
                </form>

                <?php
                    if (isset($_POST['submit'])) {
                        include("../api/connection.php");

                        
                        $id = $_POST["id"];
                        $name = $_POST["full_name"];
                        $email = $_POST["email"];
                        $phone = $_POST["phone_number"];
                        $temp_address = $_POST["temp_address"];
                        $per_address = $_POST["per_address"];
                        $gender = $_POST["gender"];
                        $program = $_POST["program"];
                        $batch = $_POST["batch"];
                        $father_name = $_POST["father_name"];
                        $mother_name = $_POST["mother_name"];
                    
                        $total_fee = $_POST["total_fee"];
                        $paid_fee = $_POST["paid_fee"];
                        $due_fee = $total_fee - $paid_fee;

                        
                        $file_name = $_FILES["file"]["name"];
                        $tempName = $_FILES["file"]["tmp_name"];
                        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));


                        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                            $newfilename = uniqid() . "-" . $file_name;


                            if (move_uploaded_file($tempName, "uploads/$newfilename")) {


                                $conn->begin_transaction();

                                try {

                                    $student_query = "INSERT INTO students (student_id, full_name, contact_number, email, photo, current_address, permanent_address, program, father_name, mother_name, batch) 
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                    $stmt = $conn->prepare($student_query);
                                    $stmt->bind_param("sssssssssss", $id, $name, $phone, $email, $newfilename, $temp_address, $per_address, $program, $father_name, $mother_name, $batch);
                                    $stmt->execute();


                                    $fee_query = "INSERT INTO fee_details (student_id, total_fee, paid_fee, due_fee) VALUES (?, ?, ?, ?)";
                                    $stmt_fee = $conn->prepare($fee_query);
                                    $stmt_fee->bind_param("sddd", $id, $total_fee, $paid_fee, $due_fee);
                                    $stmt_fee->execute();


                                    $conn->commit();

                                    echo "<script>
                                            alert('Student and fee details added successfully');
                                            window.location.href = 'student.php';
                                        </script>";
                                } catch (Exception $e) {
                                    $conn->rollback();

                                    echo "<script>
                                            alert('Error occurred: " . $conn->error . "');
                                        </script>";
                                }

                             
                                $stmt->close();
                                $stmt_fee->close();
                            } else {
                                echo "<script>alert('File upload failed.');</script>";
                            }
                        } else {
                            echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed.');</script>";
                        }

                        $conn->close();
                    }
                ?>

            </main>
        </div>
    </div>

<script src="../js/homepageTimeUpdate.js"></script>
<script src="../js/navigationRespon.js"></script>

<script>
    document.getElementById('pp_photo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
         
            const img = document.getElementById('photo_preview');
            img.src = e.target.result;
            img.style.display = 'block'; 
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    });
</script>


<script>
        document.getElementById('student-form').addEventListener('submit', function(e) {
            const fullName = document.getElementById('fullName').value.trim();
            const id = document.getElementById('id').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const email = document.getElementById('email').value.trim();
            const tempAddress = document.getElementById('temp-address').value.trim();
            const perAddress = document.getElementById('p-address').value.trim();
            const totalFee = parseFloat(document.getElementById('total_fee').value);
            const paidFee = parseFloat(document.getElementById('paid-fee').value);

            let errorMsg = '';

            if (!fullName || !id || !phone || !email || !tempAddress || !perAddress || isNaN(totalFee) || isNaN(paidFee)) {
                errorMsg = 'All fields must be filled out.';
            }

            
            if (!/^\d{10}$/.test(phone)) {
                errorMsg = 'Phone number must be 10 digits.';
            }

            if (!/^[a-zA-Z0-9]+$/.test(id)) {
                errorMsg = 'ID must be alphanumeric.';
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                errorMsg = 'Email must be in a valid format.';
            }

            if (totalFee < 0 || paidFee < 0) {
                errorMsg = 'Total Fee and Paid Fee cannot be negative.';
            }

            if (errorMsg) {
                e.preventDefault(); 
                document.getElementById('error-msg').textContent = errorMsg;
            }
        });
    </script>
</body>
</html>
