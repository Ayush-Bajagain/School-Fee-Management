<?php
    session_start();
    $user_profile = $_SESSION["user_name"];
    $role = $_SESSION["role"];

    if (!$user_profile || $role !== "admin") {
        header("location:../index.php");
    }
    ?>

<?php
include("../api/connection.php");  // Include the database connection file

// Check if search is submitted
$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// SQL query to fetch records from 'payment_history' table with search functionality
$sql = "SELECT * FROM payment_history WHERE 
        transaction_id LIKE '%$search%' OR
        student_id LIKE '%$search%' OR
        email LIKE '%$search%' OR
        batch LIKE '%$search%' OR
        program LIKE '%$search%' OR
        amount LIKE '%$search%' OR
        payment_purpose LIKE '%$search%'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <!-- <link rel="stylesheet" href="styles.css">  -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>

    <style>
            
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-content {
            padding: 20px;
        }

        /* Navigation */
        .navigation {
            width: 100%;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom: 2px solid #00000045;
        }

        .navigation h2 {
            color: royalblue;
            font-size: 30px;
        }

        /* Search Box */
        .search-box {
            margin: 20px 0;
            text-align: center;
        }

        .search-box input[type="text"] {
            padding: 10px;
            width: 300px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .search-box button {
            padding: 10px 20px;
            font-size: 16px;
            border: 1px solid #ddd;
            background-color: royalblue;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-box button:hover {
            background-color: #0044cc;
        }

        /* Table Styles */
        .payment-history-table table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .payment-history-table th, .payment-history-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .payment-history-table th {
            background-color: rgba(65, 105, 225, 0.857);
            color: white;
        }

        .payment-history-table tr:hover {
            outline: 1.1px solid #ccc;
        }

        /* Action Button */
        .view-btn {
            background-color: rgb(27, 221, 27);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        .view-btn:hover {
            background-color: green;
        }

        #back-btn{
                    text-decoration: none;
                    color: #333;
                    position: absolute;
                }
                
                #back-btn i{
                    font-size: 26px;
                    transition: .3s;
                    opacity: 40%;
                    
                }
                #back-btn i:hover{
                    font-size: 30px;
                    opacity: 100%;
                }

        /* Responsive Table */
        @media (max-width: 768px) {
            .payment-history-table {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                display: block;
            }

            .payment-history-table table {
                width: 100%;
                min-width: 700px;
            }

            .payment-history-table th, .payment-history-table td {
                white-space: nowrap;
            }

            .payment-history-table td a {
                padding: 5px 10px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .payment-history-table table {
                min-width: 500px;
            }

            .payment-history-table td a {
                padding: 5px 8px;
                font-size: 12px;
            }
        }

     </style>
</head>
<body>
    <main class="main-content">
    <a href="adminHome.php" id='back-btn'><i class="ri-arrow-left-line"></i></a>

        <div class="navigation">
            <h2>Payment History</h2>
        </div>

        <!-- Search Box -->
        <section class="search-box">
            <form method="POST" action="">
                <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" />
                <button type="submit">Search</button>
            </form>
        </section>

        <!-- Payment History Table -->
        <section class="payment-history-table">
            <table>
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Student ID</th>
                        <th>Email</th>
                        <th>Program</th>
                        <th>Batch</th>
                        <th>Amount</th>
                        <th>Payment Purpose</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "
                            <tr>
                                <td>" . htmlspecialchars($row['transaction_id']) . "</td>
                                <td>" . htmlspecialchars($row['student_id']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['program']) . "</td>
                                <td>" . htmlspecialchars($row['batch']) . "</td>
                                <td>" . htmlspecialchars($row['amount']) . "</td>
                                <td>" . htmlspecialchars($row['payment_purpose']) . "</td>
                                <td>
                                    <a href='viewPaymentHistory.php?id=" . urlencode($row['transaction_id']) . "' class='btn view-btn'>View</a>
                                </td>
                            </tr>
                            ";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
