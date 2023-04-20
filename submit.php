<?php
// Get form data
$name = $_POST['name'];
$phone = $_POST['phone'];
$city = $_POST['city'];

// Generate UUID
$uuid = uniqid();

// Create PDF document
require('./FPDF/fpdf.php'); // Include FPDF library
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Form Submission Details',1,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(50,10,'UUID:',1,0);
$pdf->Cell(0,10,$uuid,1,1);
$pdf->Cell(50,10,'Name:',1,0);
$pdf->Cell(0,10,$name,1,1);
$pdf->Cell(50,10,'Phone Number:',1,0);
$pdf->Cell(0,10,$phone,1,1);
$pdf->Cell(50,10,'City:',1,0);
$pdf->Cell(0,10,$city,1,1);
$pdf->Output('F', 'form_submission_'.$uuid.'.pdf');

// Store data in database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO form_data (uuid, name, phone, city) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $uuid,$name, $phone, $city);

// Generate UUID
$uuid = uniqid();

// Retrieve form data
$name = $_POST['name'];
$phone = $_POST['phone'];
$city = $_POST['city'];

// Execute SQL statement
$stmt->execute();

// Check if data was successfully inserted
if ($stmt->affected_rows > 0) {
   echo "<h2>Form submitted successfully.</h2>";
} else {
   echo "Error submitting form.";
}

// Close statement and connection
$stmt->close();
$conn->close();
