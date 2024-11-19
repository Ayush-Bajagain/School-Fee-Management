



<?php
session_start();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <title>payment</title>
    <link rel="stylesheet" href="../cssStd/studentLayout.css">
    <link rel="stylesheet" href="../css/payment.css">


    <style>
        
        .edit-btn {
            width: 70px;
            height: 30px;
            border: 1px solid #333;
            border: none;
            border-radius: 10px;
            background: royalblue;
        }

        .edit-btn a {
            text-decoration: none;
            font-size: 16px;
            border: none;
            color: #fff;
            background-color: royalblue;
        }

        .accept-btn {
            width: 80px;
            height: 30px;
            background-color: royalblue;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            padding: 8px 30px;
        }

        .decline-btn {
            width: 80px;
            height: 30px;
            background-color: red;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            padding: 8px 30px;
        }
    </style>

    
<!-- Styles for Popup -->
<style>
.popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}
.popup-content img {
    max-width: 90%;
    max-height: 90%;
    display: block;
    margin: auto;
}
</style>

</head>

<body>


    <?php
    $user_profile = $_SESSION["user_name"];
    $role = $_SESSION["role"];

    if (!$user_profile === true && !$role == "admin") {

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
                        <li><a href="payment.php" class="active">Payment</a></li>
                        <li><a href="application.php">Application</a></li>

                        <li id="logout-bnt"><a href="../api/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>




            <main class="main-content">


                <section class="make-payment">
                    <button id="create-payment-btn" onclick="makePayment() ">Create Payment</button>
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
                    $comment = $_POST["comment"];


                    $query = "INSERT INTO makepayment (title, amount, program, batch, message) VALUES (?, ?, ?, ?, ?)";


                    if ($stmt = $conn->prepare($query)) {

                        $stmt->bind_param("sssss", $title, $amount, $program, $batch, $comment);


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
                                <th>Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <?php
                        include("../api/connection.php");


                        $sql = "SELECT * FROM makepayment";
                        $result = $conn->query($sql);


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

                        // Close the database connection
                        $conn->close();
                        ?>





                    </table>


                </section>

                <section class="made-payment">

               
               
                    <?php
                        
                        include("../api/connection.php");

                        
                        $sql = "SELECT * FROM transaction_details";
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
                echo "<tr><td colspan='8'>No transactions found.</td></tr>";
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





                </section>
            </main>


        </div>

    </div>
    <script src="../js/homepageTimeUpdate.js"></script>
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
// Include connection file
include("../api/connection.php");

if (isset($_GET['accept_id'])) {
    $transaction_id = intval($_GET['accept_id']); // Use intval to ensure integer type

    // Fetch the transaction details for the accepted transaction
    $sqlFetch = "SELECT * FROM transaction_details WHERE transaction_id = ?";
    $stmtFetch = $conn->prepare($sqlFetch);
    $stmtFetch->bind_param("i", $transaction_id);
    $stmtFetch->execute();
    $resultFetch = $stmtFetch->get_result();

    if ($resultFetch->num_rows > 0) {
        $row = $resultFetch->fetch_assoc();

        // Extract data to variables
        $student_id = $row['student_id'];
        $email = $row['email'];
        $batch = $row['batch'];
        $program = $row['program']; // Assuming 'program' is available in the transaction_details table
        $photo = $row['photo'];
        $remark = $row['remark'];
        $amount = $row['amount'];

        // Start a transaction to ensure all operations are executed together
        $conn->begin_transaction();

        try {
            // Insert the data into the payment_history table
            $sqlInsert = "INSERT INTO payment_history (transaction_id, student_id, email, batch, program, photo, remark) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param(
                "issssss",
                $transaction_id,
                $student_id,
                $email,
                $batch,
                $program,
                $photo,
                $remark
            );
            if (!$stmtInsert->execute()) {
                throw new Exception("Error inserting into payment_history: " . $conn->error);
            }

            // Fetch the current total fee and paid fee
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

                // Update the fee_details table
                $update_sql = "UPDATE fee_details SET paid_fee = ?, due_fee = ? WHERE student_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("dds", $new_paid_fee, $new_due_fee, $student_id);
                if (!$update_stmt->execute() || $update_stmt->affected_rows <= 0) {
                    throw new Exception("Failed to update fee details for student ID: $student_id. " . $conn->error);
                }
            } else {
                throw new Exception("Student fee details not found for ID: $student_id.");
            }

            // Delete the transaction from transaction_details
            $delete_sql = "DELETE FROM transaction_details WHERE transaction_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $transaction_id);
            if (!$delete_stmt->execute()) {
                throw new Exception("Error deleting from transaction_details: " . $conn->error);
            }

            // Commit the transaction
            $conn->commit();
            echo "<script> alert('Transaction processed successfully.'); window.location.href = 'payment.php'; </script>";
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            echo "<script> alert('Error: " . $e->getMessage() . "'); window.location.href = 'payment.php'; </script>";
        }

        // Close statements
        $stmtInsert->close();
        $feeDetailsStmt->close();
        $update_stmt->close();
        $delete_stmt->close();
    } else {
        echo "<script> alert('Transaction not found.'); window.location.href = 'payment.php'; </script>";
    }

    // Close fetch statement
    $stmtFetch->close();
}
?>

