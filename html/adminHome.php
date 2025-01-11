<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="../newCss/layout.css">
    <link rel="stylesheet" href="../newCss/adminHome.css">
</head>

<body>

    <?php
    $user_profile = $_SESSION["user_name"];
    $role = $_SESSION["role"];

    if (!$user_profile || $role !== "admin") {
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
                <p></p>
            </div>
        </header>

        <div class="wrapper">
            <aside class="sidebar">
                <img src="../images/icon/menu.png" class="menu-icon" width="30" height="30" onclick="openNav()">
                <p class="welcome">Hello! ADMIN</p><br><br>
                <nav class="menu">
                    <ul id="nav-menu">
                        <li><a href="#" class="active">Dashboard</a></li>
                        <li><a href="student.php">Student</a></li>
                        <li><a href="payment.php">Payment <?php
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
                <section class="first-section">
                    <a href="viewStudent.php" class="enrolled-box boxx" style="text-decoration: none;">
                        <div>
                            <section class="photo">
                                <img src="../images/enrolled.jpg" alt="Photo of enrolled students">
                            </section>
                            <h2>Enrolled Students</h2>
                            <?php
                            include("../api/connection.php");
                            $sql = "SELECT COUNT(*) AS student_count FROM students";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $student_count = $row['student_count'];
                            ?>
                            <p class='count-std'><?php echo $student_count; ?></p>
                        </div>
                    </a>

                    <a href="paymentHistory.php" class="fee-collection-box boox" style="text-decoration: none;">
                        <div>
                            <section class="photo">
                                <img src="../images/payment.jpg" alt="Fee collection">
                            </section>
                            <h2>Total Fee Collection</h2>
                            <?php
                            include("../api/connection.php");

                            $sql = "SELECT paid_fee FROM fee_details";
                            $result = $conn->query($sql);

                            $total_sum = 0;

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $total_sum += $row['paid_fee'];
                                }
                            }
                            ?>
                            <p class="count-fee"><?php echo $total_sum; ?></p>
                        </div>
                    </a>

                    <a href="#" class="due-box boox" style="text-decoration: none;">
                        <div>
                            <section class="photo">
                                <img src="../images/fee_due.jpg" alt="Due fee students">
                            </section>
                            <h2>Due Fee</h2>
                            <?php
                            include("../api/connection.php");

                            $sql = "SELECT total_fee, paid_fee FROM fee_details";
                            $result = $conn->query($sql);

                            $total_due_fee = 0;

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $total_fee = $row['total_fee'];
                                    $paid_fee = $row['paid_fee'];

                                    $due_fee = $total_fee - $paid_fee;
                                    $total_due_fee += $due_fee;
                                }
                            }
                            ?>
                            <p class="count-due"><?php echo $total_due_fee; ?></p>
                        </div>
                    </a>
                </section>





                <button onclick="showOtherPayment()" id="showOtherPaymentBtn">Show other payments</button>

                <section class="other-fee-field" id="other-payment-table">
                    <?php
                    include("../api/connection.php");

                    // Fetch data where payment_purpose is not "regular"
                    $sql = "SELECT transaction_id, student_id, email, batch, program, photo, remark, payment_purpose, amount FROM payment_history WHERE payment_purpose != 'regular'";
                    $result = $conn->query($sql);
                    ?>

                    <h2>Payment History (Non-Regular Payments)</h2>

                    <table>
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Student ID</th>
                                <th>Email</th>
                                <th>Batch</th>
                                <th>Program</th>
                                <th>Photo</th>
                                <th>Remark</th>
                                <th>Payment Purpose</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $photo_path = "../htmlStd/uploads/" . htmlspecialchars($row['photo']);
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['transaction_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['batch']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['program']) . "</td>";
                                    echo "<td><img src='" . $photo_path . "' onclick='openModal(\"" . $photo_path . "\")' alt='Student Photo'></td>";
                                    echo "<td>" . htmlspecialchars($row['remark']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['payment_purpose']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>No records found.</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>

                    <!-- Modal for displaying photo -->
                    <div id="modal">
                        <span id="close" onclick="closeModal()">&times;</span>
                        <img id="modal-content" src="" alt="Enlarged Photo">
                    </div>
                </section>





                <div>
                    <?php

                    include("../api/connection.php");

                    // Initialize variables for row counts
                    $transaction_count = 0;
                    $register_count = 0;

                    // Count rows in transaction_details with respect to transaction_id
                    $transaction_query = "SELECT COUNT(transaction_id) AS transaction_count FROM transaction_details";
                    $result_transaction = $conn->query($transaction_query);

                    if ($result_transaction && $result_transaction->num_rows > 0) {
                        $row_transaction = $result_transaction->fetch_assoc();
                        $transaction_count = $row_transaction['transaction_count'];
                    }

                    // Count rows in register table with respect to id
                    $register_query = "SELECT COUNT(id) AS register_count FROM register";
                    $result_register = $conn->query($register_query);

                    if ($result_register && $result_register->num_rows > 0) {
                        $row_register = $result_register->fetch_assoc();
                        $register_count = $row_register['register_count'];
                    }

                    // Close the database connection
                    $conn->close();
                    $totalRow = $transaction_count + $register_count;
                    ?>



                    <button onclick="showNotification()" id="notification-btn">Notification<?php echo "<sup style='color:red; font-weight:600;'>$totalRow</sup>" ?></button>

                    <div id="notificationTable" class="notification">


                        <?php

                        include("../api/connection.php");


                        $registerData = [];
                        $transactionData = [];


                        $sql_register = "SELECT id, full_name, program, batch FROM register";
                        $result_register = $conn->query($sql_register);

                        if ($result_register->num_rows > 0) {
                            while ($row = $result_register->fetch_assoc()) {
                                $registerData[] = $row;
                            }
                        }


                        $sql_transaction = "SELECT transaction_id, student_id, amount, batch, program FROM transaction_details";
                        $result_transaction = $conn->query($sql_transaction);

                        if ($result_transaction->num_rows > 0) {
                            while ($row = $result_transaction->fetch_assoc()) {
                                $transactionData[] = $row;
                            }
                        }
                        $conn->close();
                        ?>





                        <!-- Register Table -->
                        <div class="table-container" id="registerTableContainer">
                            <h2>Register Table</h2>
                            <table id="registerTable">
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Program</th>
                                    <th>Batch</th>
                                    <th>Action</th>
                                </tr>
                                <?php if (!empty($registerData)): ?>
                                    <?php foreach ($registerData as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['program']); ?></td>
                                            <td><?php echo htmlspecialchars($row['batch']); ?></td>
                                            <td><?php echo "<a href='application.php'>view</a>" ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">No data found in the Register table.</td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>

                        <!-- Transaction Details Table -->
                        <div class="table-container" id="transactionTableContainer">
                            <h2>Transaction Details Table</h2>
                            <table id="transactionTable">
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Student ID</th>
                                    <th>Amount</th>
                                    <th>Batch</th>
                                    <th>Program</th>
                                    <th>Action</th>
                                </tr>
                                <?php if (!empty($transactionData)): ?>
                                    <?php foreach ($transactionData as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['amount']); ?></td>
                                            <td><?php echo htmlspecialchars($row['batch']); ?></td>
                                            <td><?php echo htmlspecialchars($row['program']); ?></td>
                                            <td><?php echo "<a href='payment.php'>view</a>" ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">No data found in the Transaction Details table.</td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="../js/homepageTimeUpdate.js"></script>
    <script src="../js/navigationRespon.js"></script>
    <script>
        var a = document.getElementById('nav-menu');
        var x = 0;

        function openNav() {
            if (x == 0) {
                a.style.display = "flex";
                x = 1;
            } else {
                a.style.display = "none";
                x = 0;
            }
        }
    </script>

    <script>
        // Open modal with photo
        function openModal(photoUrl) {
            document.getElementById('modal').style.display = "block";
            document.getElementById('modal-content').src = photoUrl;
        }

        // Close modal
        function closeModal() {
            document.getElementById('modal').style.display = "none";
        }
    </script>


    <script>
        var tableData = document.getElementById("notificationTable");
        var visible = 1;

        function showNotification() {
            if (visible == 0) {
                tableData.style.display = "none";
           
                visible = 1;
            } else {
                tableData.style.display = "block";
               
                visible = 0;
            }
        }
    </script>


    <script>
        var form = document.getElementById('other-payment-table');
        var display = 0;

        function showOtherPayment() {

            if (display == 0) {
                form.style.display = 'block';
                display = 1;
            } else {
                form.style.display = 'none';
                display = 0;
            }

        }
    </script>



</body>

</html>