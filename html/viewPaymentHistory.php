<?php
    session_start();
?>

<?php
    $user_profile = $_SESSION["email"];
    $role = $_SESSION["role"];

    
    if ($user_profile == true && $role == "admin") {
        
    }else{
        header("location:../index.php");
    }

?>


<?php
include("../api/connection.php"); // Database connection

// Fetch the transaction ID from URL
if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];

    // Fetch record based on transaction ID
    $sql = "SELECT * FROM payment_history WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: royalblue;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            margin: 5px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .print-btn { background-color: royalblue; }
        .download-btn { background-color: green; }
        .photo img {
            width: 150px;
            height: auto;
            cursor: pointer;
        }
        /* Lightbox for photo */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }
        .lightbox img {
            max-width: 90%;
            max-height: 80%;
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

        @media print {
            body * {
                visibility: hidden;
            }
            .container, .container * {
                visibility: visible;
            }
            .container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }

    </style>
</head>
<body>
    <a href="paymentHistory.php" id='back-btn'><i class="ri-arrow-left-line"></i></a>
    
    <div class="container">
        <h2>Payment Details</h2>
        <table class="details-table">
            <tr><td><strong>Transaction ID:</strong></td><td><?php echo htmlspecialchars($row['transaction_id']); ?></td></tr>
            <tr><td><strong>Student ID:</strong></td><td><?php echo htmlspecialchars($row['student_id']); ?></td></tr>
            <tr><td><strong>Email:</strong></td><td><?php echo htmlspecialchars($row['email']); ?></td></tr>
            <tr><td><strong>Batch:</strong></td><td><?php echo htmlspecialchars($row['batch']); ?></td></tr>
            <tr><td><strong>Program:</strong></td><td><?php echo htmlspecialchars($row['program']); ?></td></tr>
            <tr><td><strong>Amount:</strong></td><td><?php echo htmlspecialchars($row['amount']); ?></td></tr>
            <tr><td><strong>Payment Purpose:</strong></td><td><?php echo htmlspecialchars($row['payment_purpose']); ?></td></tr>
            <tr><td><strong>Remark:</strong></td><td><?php echo htmlspecialchars($row['remark']); ?></td></tr>
            <tr><td><strong>Photo:</strong></td><td class="photo">
                <img src="<?php echo '../htmlStd/uploads/' . htmlspecialchars($row['photo']); ?>" alt="Payment Photo" id="paymentPhoto">
            </td></tr>
        </table>

        
    </div>
    <div class="btn-container">
            <button class="btn print-btn" onclick="printSection()">Print</button>
            <button class="btn download-btn" onclick="downloadPDF()">Download PDF</button>
        </div>

    <!-- Lightbox for photo -->
    <div class="lightbox" id="lightbox">
        <img src="" alt="Enlarged Photo" id="lightboxImg">
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        // Lightbox functionality
        const photo = document.getElementById('paymentPhoto');
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightboxImg');

        photo.onclick = function() {
            lightbox.style.display = 'flex';
            lightboxImg.src = photo.src;
        };

        lightbox.onclick = function() {
            lightbox.style.display = 'none';
        };

        // Function to print only the container section
        function printSection() {
            window.print();
        }

        // Function to download the container as PDF
        function downloadPDF() {
            const element = document.querySelector('.container'); // Select the container div
            const options = {
                margin: 10,
                filename: 'payment_details.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().from(element).set(options).save();
        }
    </script>
</body>
</html>
