<?php
session_start();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="../newCss/layout.css">
    <link rel="stylesheet" href="../newCss/payment.css">
    <title>payment</title>
</head>

<body>


    <?php
    $user_profile = $_SESSION["user_name"];
    $role = $_SESSION["role"];

    if (!$user_profile === true && !$role == "admin") {

        header("location:../index.php");
    }

    ?>

    <?php


    include("../api/connection.php");

    // SQL query to count the rows in the table
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
            <div class="date-time">
                <p>08/30/2024 10:23:15 AM</p>
            </div>
        </header>

        <div class="wrapper">

            <aside class="sidebar">
                <img src="../images/icon/menu.png" class="menu-icon" width="30" height="30" onclick="openNav()">
                <p class="welcome">Hello! ADMIN</p><br><br>
                <nav class="menu">
                    <ul id="nav-menu">
                        <li><a href="adminHome.php">Dashboard</a></li>
                        <li><a href="student.php">Student</a></li>
                        <li><a href="payment.php" class="active">Payment <?php
                                                                            if ($no_row1 > 1) {
                                                                                echo "<sup id= 'payment-noti'>$no_row1</sup>";
                                                                            }
                                                                            ?></a>
                        </li>
                        <li><a href="application.php">Application</a></li>
                        <li id="logout-bnt"><a href="../api/logout.php" style=" display: block;color: white;text-decoration: none;background-color: #ff4d4d;padding: 8px 30px;border-radius: 5px;">Logout</a></li>
                    </ul>
                </nav>
            </aside>




            <main class="main-content">


                <section class="make-payment">
                    <button id="create-payment-btn" onclick="makePayment() ">Create Fee</button>
                    <br />



                    <form id="form-main" action="" method="post">
                        <label for="title">Title</label>
                        <input type="text" placeholder="Title of payment...." id="title" name="title" />
                        <br>

                        <label for="amount">Amount</label>
                        <input type="number" placeholder="Amount..." id="amount" name="amount" />
                        <br>

                        <label for="program">Program</label>
                        <select id="program" name="program">
                            <option value="">Select Program</option>
                            <option value="bca">BCA</option>
                            <option value="bba">BBA</option>
                            <option value="bim">BIM</option>
                        </select>
                        <br>

                        <label for="batch">Batch</label>
                        <select id="batch" name="batch">
                            <option value="">Select Batch</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                        </select>
                        <br>

                        <label for="field">Payment</label>
                        <select id="field" name="field">
                            <option value="">Select Fee</option>
                            <option value="regular">Reguler</option>
                            <option value="examFee">Exam Fee</option>
                            <option value="fine">Fine</option>
                            <option value="other">Other</option>
                        </select>
                        <br>

                        <label for="comment">Comment</label>
                        <input type="text" placeholder="Write message.." id="comment" name="comment">
                        <br>

                        <input type="submit" value="Create" id="submit-btn" name="createPayment" />
                        <p class="error" id="error-msg" style="color: red;"></p>
                    </form>

                </section>





                <?php
                include("../api/connection.php");

                if (isset($_POST["createPayment"])) {
                    // Get and sanitize input values
                    $title = $_POST["title"];
                    $amount = $_POST["amount"];
                    $program = $_POST["program"];
                    $batch = $_POST["batch"];
                    $field = $_POST["field"];
                    $comment = $_POST["comment"];

                    if ($field == "regular") {
                        $query = "INSERT INTO makepayment (title, amount, program, batch, message, payment_field) VALUES (?, ?, ?, ?, ?, ?)";


                        if ($stmt = $conn->prepare($query)) {

                            $stmt->bind_param("ssssss", $title, $amount, $program, $batch, $comment, $field);


                            if ($stmt->execute()) {

                                echo "<script>alert('Payment created successfully');</script>";
                            } else {
                                echo "Failed to record payment: " . $stmt->error;
                            }

                            $stmt->close();
                        } else {
                            echo "Failed to prepare the SQL statement: " . $conn->error;
                        }
                    } else {

                        $query = "INSERT INTO other_payment (title, amount, program, batch, message, payment_field) VALUES (?, ?, ?, ?, ?, ?)";


                        if ($stmt = $conn->prepare($query)) {

                            $stmt->bind_param("ssssss", $title, $amount, $program, $batch, $comment, $field);


                            if ($stmt->execute()) {

                                echo "<script>alert('Payment recorded successfully');</script>";
                            } else {
                                echo "Failed to record payment: " . $stmt->error;
                            }

                            $stmt->close();
                        } else {
                            echo "Failed to prepare the SQL statement: " . $conn->error;
                        }
                    }
                }


                $conn->close();
                ?>

                <br>
                <br>
                <br>
                <hr>

                <section class="made-payment">
                    <h1>Created Payment</h1>

                    <table>
                        <thead>

                            <tr>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Program</th>
                                <th>Batch</th>
                                <th>Field</th>
                                <th>Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <?php
                        include("../api/connection.php");


                        $sql1 = "SELECT * FROM other_payment";
                        $sql = "SELECT * FROM makepayment";
                        $result = $conn->query($sql);
                        $result1 = $conn->query($sql1);


                        if (!$result) {
                            die("Invalid query: " . $conn->error);
                        }


                        while ($row = $result->fetch_assoc()) {
                            echo "
                                    <tr>
                                        <td>" . htmlspecialchars($row['title']) . "</td>
                                        <td>" . htmlspecialchars($row['amount']) . "</td>
                                        <td>" . htmlspecialchars($row['program']) . "</td>
                                        <td>" . htmlspecialchars($row['batch']) . "</td>
                                        <td>" . htmlspecialchars($row['payment_field']) . "</td>
                                        <td>" . htmlspecialchars($row['message']) . "</td>
                                        <td>
                                            <button class='view-btn'>
                                                <a href='deleteCreatedPayment.php?id=" . htmlspecialchars($row['id']) . "'>Delete</a>
                                                
                                            </button>

                                            <button class='edit-btn'>
                                                <a href='editCreatedPayment.php?id=" . htmlspecialchars($row['id']) . "'>Edit</a>
                                                
                                            </button>
                                        </td>
                                    </tr>
                                ";
                        }


                        while ($row = $result1->fetch_assoc()) {
                            echo "
                                    <tr>
                                        <td>" . htmlspecialchars($row['title']) . "</td>
                                        <td>" . htmlspecialchars($row['amount']) . "</td>
                                        <td>" . htmlspecialchars($row['program']) . "</td>
                                        <td>" . htmlspecialchars($row['batch']) . "</td>
                                        <td>" . htmlspecialchars($row['payment_field']) . "</td>
                                        <td>" . htmlspecialchars($row['message']) . "</td>
                                        <td>
                                            <button class='view-btn'>
                                                <a href='deleteCreatedPayment.php?id=" . htmlspecialchars($row['id']) . "'>Delete</a>
                                                
                                            </button>

                                            <button class='edit-btn'>
                                                <a href='editCreatedPayment.php?id=" . htmlspecialchars($row['id']) . "'>Edit</a>
                                                
                                            </button>
                                        </td>
                                    </tr>
                                ";
                        }


                        $conn->close();
                        ?>





                    </table>


                </section>

                <div class="table-container">

                    <style>
                        .table-container {
                            width: 100%;
                            background: #fff;
                            border-radius: 8px;
                        }

                        .individual-heading {
                            text-align: center;
                            margin-bottom: 20px;
                            font-size: 24px;
                            color: #444;
                        }

                        .table-wrapper {
                            overflow-x: auto;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 10px;
                            min-width: 900px;
                        }

                        table thead tr {
                            background-color: royalblue;
                            color: white;
                        }

                        table th,
                        table td {
                            border: 1px solid #ddd;
                            padding: 10px;
                            text-align: center;
                        }

                        table tr:nth-child(even) {
                            background-color: #f2f2f2;
                        }

                        table tr:hover {

                            opacity: 90%;
                        }

                        table th {
                            padding-top: 12px;
                            padding-bottom: 12px;
                        }



                        .table-wrapper::-webkit-scrollbar {
                            height: 8px;
                        }

                        .table-wrapper::-webkit-scrollbar-thumb {
                            background-color: #888;
                            border-radius: 10px;
                        }

                        .table-wrapper::-webkit-scrollbar-thumb:hover {
                            background-color: #555;
                        }

                        .individual-btn a {
                            color: #fff;
                            background-color: royalblue;
                            padding: 5px 10px;
                            border-radius: 5px;
                            text-decoration: none;

                        }

                        .individual-btn a:hover {
                            backround-color: #fff;
                        }


                        @media (max-width: 768px) {
                            table {
                                min-width: 600px;
                            }
                        }
                    </style>

                    <?php

                    // Include database connection
                    include '../api/connection.php';


                    $query = "SELECT payment_id, student_id, student_name, student_email, batch, program, amount, payment_purpose, comment FROM individual_payment";
                    $result = mysqli_query($conn, $query);

                    if (!$result) {
                        die("Query Failed: " . mysqli_error($conn));
                    }
                    ?>
                    <div class="table-container">
                        <h1 class="individual-heading">Individual Payment Records</h1>
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Student Email</th>
                                        <th>Batch</th>
                                        <th>Program</th>
                                        <th>Amount</th>
                                        <th>Payment Purpose</th>
                                        <th>Comment</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                <td>" . htmlspecialchars($row['payment_id']) . "</td>
                                <td>" . htmlspecialchars($row['student_id']) . "</td>
                                <td>" . htmlspecialchars($row['student_name']) . "</td>
                                <td>" . htmlspecialchars($row['student_email']) . "</td>
                                <td>" . htmlspecialchars($row['batch']) . "</td>
                                <td>" . htmlspecialchars($row['program']) . "</td>
                                <td>" . htmlspecialchars($row['amount']) . "</td>
                                <td>" . htmlspecialchars($row['payment_purpose']) . "</td>
                                <td>" . htmlspecialchars($row['comment']) . "</td>
                                <td class='individual-btn'>
                                    <a href='./adminapi/editIndividualPayment.php?payment_id=" . urlencode($row['payment_id']) . "'>Edit</a>
                                    |
                                    <a href='./adminapi/deleteIndiviaualPayment.php?payment_id=" . urlencode($row['payment_id']) . "' onclick=\"return confirm('Are you sure you want to delete this item?');\">Delete</a>
                                </td>
                              </tr>";
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <section class="made-payment">


                        <?php
                        include("../api/connection.php");


                        $sql = "
                        SELECT transaction_id, amount, remark, photo, batch, student_id, email, payment_purpose FROM transaction_details
                        UNION ALL
                        SELECT transaction_id, amount, remark, photo, batch, student_id, email, payment_purpose FROM other_transaction_details
                    ";

                        $result = $conn->query($sql);

                        if (!$result) {
                            die("Invalid query: " . $conn->error);
                        }
                        ?>

                        <div class="container">
                            <h1>Transaction Details</h1>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Remarks</th>
                                        <th>Photo</th>
                                        <th>Batch</th>
                                        <th>Student ID</th>
                                        <th>Email</th>
                                        <th>Payment Purpose</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $imagePath = "../htmlStd/uploads/" . htmlspecialchars($row['photo']); // Corrected path
                                            echo "
                                        <tr>
                                            <td>" . htmlspecialchars($row['transaction_id']) . "</td>
                                            <td>" . htmlspecialchars($row['amount']) . "</td>
                                            <td>" . htmlspecialchars($row['remark']) . "</td>
                                            <td>
                                                <a href='#' onclick='showPopup(\"" . $imagePath . "\")'>
                                                    <img src='" . $imagePath . "' alt='Payment Photo' width='100' style='cursor: pointer;'>
                                                </a>
                                            </td>
                                            <td>" . htmlspecialchars($row['batch']) . "</td>
                                            <td>" . htmlspecialchars($row['student_id']) . "</td>
                                            <td>" . htmlspecialchars($row['email']) . "</td>
                                            <td>" . htmlspecialchars($row['payment_purpose']) . "</td>
                                            <td>
                                                <form action='process_transaction.php' method='POST'>
                                                    <a href='?accept_id=" . htmlspecialchars($row['transaction_id']) . "' class='accept-btn'>Accept</a>
                                                    <a href='deletePayment.php?id=" . htmlspecialchars($row['transaction_id']) . "' class='decline-btn'>Decline</a>
                                                </form>
                                            </td>
                                        </tr>
                                    ";
                                        }
                                    } else {
                                        echo "<tr><td colspan='9'>No transactions found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>


                        <!-- Popup Container -->
                        <div id="photoPopup" class="popup" onclick="hidePopup()">
                            <div class="popup-content">
                                <img id="popupImage" src="" alt="Full-size Payment Photo">
                            </div>
                        </div>








                    </section>
            </main>


        </div>

    </div>

    <!-- JavaScript for Popup -->
    <script>
        function showPopup(imageSrc) {
            const popup = document.getElementById('photoPopup');
            const popupImage = document.getElementById('popupImage');
            popupImage.src = imageSrc;
            popup.style.display = 'flex';
        }

        function hidePopup() {
            const popup = document.getElementById('photoPopup');
            popup.style.display = 'none';
        }
    </script>

    <script src="../js/homepageTimeUpdate.js"></script>
    <script src="../js/navigationRespon.js"></script>

    <script>
        var form = document.getElementById('form-main');
        var display = 0;

        function makePayment() {

            if (display == 0) {
                form.style.display = 'block';
                display = 1;
            } else {
                form.style.display = 'none';
                display = 0;
            }

        }
    </script>

    <script>
        document.getElementById('form-main').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const amount = parseFloat(document.getElementById('amount').value);
            const program = document.getElementById('program').value;
            const batch = document.getElementById('batch').value;
            const comment = document.getElementById('comment').value.trim();

            let errorMsg = '';

            // Check all fields are filled
            if (!title || isNaN(amount) || !program || !batch || !comment) {
                errorMsg = 'All fields must be filled out.';
            }

            // Check amount is not negative
            if (amount < 0) {
                errorMsg = 'Amount cannot be negative.';
            }

            // Display error message if validation fails
            if (errorMsg) {
                e.preventDefault(); // Prevent form submission
                document.getElementById('error-msg').textContent = errorMsg;
            }
        });
    </script>


</body>

</html>





<?php

include("../api/connection.php");

if (isset($_GET['accept_id'])) {
    $transaction_id = intval($_GET['accept_id']); // Ensure integer type for security

    // Fetch the transaction from either transaction_details or other_transaction_details
    $sqlFetch = "SELECT * FROM transaction_details WHERE transaction_id = ?";
    $stmtFetch = $conn->prepare($sqlFetch);
    $stmtFetch->bind_param("i", $transaction_id);
    $stmtFetch->execute();
    $resultFetch = $stmtFetch->get_result();

    if ($resultFetch->num_rows > 0) {
        $row = $resultFetch->fetch_assoc();
        $source_table = 'transaction_details';  // Identify source table
    } else {
        $stmtFetch->close();  // Close previous statement
        $sqlFetch = "SELECT * FROM other_transaction_details WHERE transaction_id = ?";
        $stmtFetch = $conn->prepare($sqlFetch);
        $stmtFetch->bind_param("i", $transaction_id);
        $stmtFetch->execute();
        $resultFetch = $stmtFetch->get_result();

        if ($resultFetch->num_rows > 0) {
            $row = $resultFetch->fetch_assoc();
            $source_table = 'other_transaction_details';
        } else {
            echo "<script> alert('Transaction not found.'); window.location.href = 'payment.php'; </script>";
            exit;
        }
    }


    $student_id = $row['student_id'];
    $email = $row['email'] ?? '';  
    $batch = $row['batch'] ?? '';
    $program = $row['program'] ?? '';
    $photo = $row['photo'] ?? '';
    $remark = $row['remark'] ?? '';
    $amount = $row['amount'];
    $payment_purpose = $row['payment_purpose'];  

    // Begin transaction for data integrity
    $conn->begin_transaction();

    try {
        // Insert data into payment_history table
        $sqlInsertHistory = "INSERT INTO payment_history (transaction_id, student_id, email, batch, program, photo, remark, payment_purpose, amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsertHistory = $conn->prepare($sqlInsertHistory);
        $stmtInsertHistory->bind_param("issssssss", $transaction_id, $student_id, $email, $batch, $program, $photo, $remark, $payment_purpose, $amount);
        if (!$stmtInsertHistory->execute()) {
            throw new Exception("Error inserting into payment_history: " . $conn->error);
        }

        // Handle based on payment purpose
        if (strtolower($payment_purpose) === 'regular' || strtolower($payment_purpose) === 'advance') {
            // Update fee_details table for regular payments
            $feeDetailsQuery = "SELECT total_fee, paid_fee FROM fee_details WHERE student_id = ?";
            $feeDetailsStmt = $conn->prepare($feeDetailsQuery);
            $feeDetailsStmt->bind_param("s", $student_id);
            $feeDetailsStmt->execute();
            $feeDetailsResult = $feeDetailsStmt->get_result();

            if ($feeDetailsResult->num_rows > 0) {
                $feeDetails = $feeDetailsResult->fetch_assoc();
                $total_fee = $feeDetails['total_fee'];
                $paid_fee = $feeDetails['paid_fee'];

                // Calculate new paid fee and due fee
                $new_paid_fee = $paid_fee + $amount;
                $new_due_fee = $total_fee - $new_paid_fee;

                // Update fee_details table
                $update_sql = "UPDATE fee_details SET paid_fee = ?, due_fee = ? WHERE student_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("dds", $new_paid_fee, $new_due_fee, $student_id);
                if (!$update_stmt->execute() || $update_stmt->affected_rows <= 0) {
                    throw new Exception("Failed to update fee details for student ID: $student_id. " . $conn->error);
                }
            } else {
                throw new Exception("Student fee details not found for ID: $student_id.");
            }
        } else {
            // Check if student already has an entry in other_fee_details
            $checkQuery = "SELECT paid_amount FROM other_fee_details WHERE student_id = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("s", $student_id);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                // Update existing record
                $existingData = $checkResult->fetch_assoc();
                $new_paid_amount = $existingData['paid_amount'] + $amount;

                $updateOtherFee = "UPDATE other_fee_details SET paid_amount = ? WHERE student_id = ?";
                $updateOtherFeeStmt = $conn->prepare($updateOtherFee);
                $updateOtherFeeStmt->bind_param("ds", $new_paid_amount, $student_id);
                if (!$updateOtherFeeStmt->execute()) {
                    throw new Exception("Failed to update other_fee_details: " . $conn->error);
                }
            } else {
                // Insert new record if not exists
                $insertOtherFee = "INSERT INTO other_fee_details (student_id, paid_amount) VALUES (?, ?)";
                $insertOtherFeeStmt = $conn->prepare($insertOtherFee);
                $insertOtherFeeStmt->bind_param("sd", $student_id, $amount);
                if (!$insertOtherFeeStmt->execute()) {
                    throw new Exception("Failed to insert into other_fee_details: " . $conn->error);
                }
            }
        }

        // Delete the transaction from the source table
        $delete_sql = "DELETE FROM $source_table WHERE transaction_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $transaction_id);
        if (!$delete_stmt->execute()) {
            throw new Exception("Error deleting from $source_table: " . $conn->error);
        }

        $conn->commit();
        echo "<script> alert('Transaction processed successfully.'); window.location.href = 'payment.php'; </script>";
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "<script> alert('Error: " . $e->getMessage() . "'); window.location.href = 'payment.php'; </script>";
    }

    // Close all prepared statements
    $stmtInsertHistory->close();
    if (isset($feeDetailsStmt)) $feeDetailsStmt->close();
    if (isset($update_stmt)) $update_stmt->close();
    if (isset($checkStmt)) $checkStmt->close();
    if (isset($updateOtherFeeStmt)) $updateOtherFeeStmt->close();
    if (isset($insertOtherFeeStmt)) $insertOtherFeeStmt->close();
    $delete_stmt->close();
    $stmtFetch->close();
}

$conn->close();
?>