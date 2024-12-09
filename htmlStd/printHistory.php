<?php
    session_start();
?>

<?php
    $user_profile = $_SESSION["email"];
    $role = $_SESSION["role"];

    
    if ($user_profile == true && $role == "student") {
        
    }else{
        header("location:../index.php");
    }

?>


<?php
// Include database connection
include('../api/connection.php');

// Check if transaction_id is passed in the URL
if (isset($_GET['transaction_id'])) {
    $transaction_id = $conn->real_escape_string($_GET['transaction_id']);

    // Fetch data for the specified transaction
    $query = "SELECT transaction_id, student_id, email, batch, program, photo, remark, payment_purpose, amount 
              FROM payment_history 
              WHERE transaction_id = '$transaction_id'";
    $result = $conn->query($query);
} else {
    echo "No transaction ID specified.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History for Transaction ID: <?php echo htmlspecialchars($transaction_id); ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .print-btn {
            display: inline-block;
            margin: 10px 5px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
        .transaction-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 700px;
            text-align: left;
        }
        .transaction-card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #ddd;
            display: block;
            margin: 10px 0;
        }
        .transaction-field {
            margin: 15px 0;
        }
        .transaction-field strong {
            color: #555;
            display: inline-block;
            width: 150px;
        }
        @media print {
            /* Hide the Go Back button in print */
            .go-back-btn {
                display: none;
            }
            .print-btn {
                display: none; /* Hide print and download PDF buttons in print mode */
            }
            body {
                background-color: white;
                color: black;
            }
        }
    </style>
</head>
<body>

<!-- Go Back Button (Hidden in Print Mode) -->
<a href="details.php" class="go-back-btn" style="text-decoration: none; color: royalblue; font-size: 20px;">Go Back</a>

<h2>Payment History for Transaction ID: <?php echo htmlspecialchars($transaction_id); ?></h2>
<button class="print-btn" onclick="window.print()">Print</button>
<button class="print-btn" id="download-pdf-btn">Download PDF</button>

<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
    <div id="content" class="transaction-card">
        <div class="transaction-field">
            <strong>Transaction ID:</strong> <?php echo htmlspecialchars($row['transaction_id']); ?>
        </div>
        <div class="transaction-field">
            <strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?>
        </div>
        <div class="transaction-field">
            <strong>Batch:</strong> <?php echo htmlspecialchars($row['batch']); ?>
        </div>
        <div class="transaction-field">
            <strong>Program:</strong> <?php echo htmlspecialchars($row['program']); ?>
        </div>
        <div class="transaction-field">
            <strong>Remark:</strong> <?php echo htmlspecialchars($row['remark']); ?>
        </div>
        <div class="transaction-field">
            <strong>Payment Purpose:</strong> <?php echo htmlspecialchars($row['payment_purpose']); ?>
        </div>
        <div class="transaction-field">
            <strong>Amount:</strong> Rs.<?php echo htmlspecialchars($row['amount']); ?>
        </div>
        <?php if ($row['photo']): ?>
        <div class="transaction-field">
            <strong>Photo:</strong><br>
            <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="Photo">
        </div>
        <?php endif; ?>
    </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No records found for Transaction ID: <?php echo htmlspecialchars($transaction_id); ?></p>
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('download-pdf-btn').addEventListener('click', function () {
        const element = document.getElementById('content');
        const opt = {
            margin: 0.5,
            filename: 'payment_history_<?php echo htmlspecialchars($transaction_id); ?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().from(element).set(opt).save();
    });
</script>

</body>
</html>
