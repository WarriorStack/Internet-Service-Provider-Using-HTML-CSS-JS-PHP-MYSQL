<?php
require('tcpdf/tcpdf.php');
require('../phps/db_connection.php');

error_reporting(0); // Temporarily hide warnings (remove in production)

$tables = ["accountinfo", "complaints", "datausage", "employee", "enquiry", "logininfo", "plans", "review", "rginfo", "server_resources", "transactions"];
$selectedTable = $_GET['table'] ?? 'transactions';

if (!in_array($selectedTable, $tables)) {
    die("Invalid table selected.");
}

$sql = "SELECT * FROM $selectedTable";
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $dataHtml = "<h2 class='report-title'>" . ucfirst($selectedTable) . " Report</h2>";
$dataHtml = '<div style="margin: 30px; text-align: center;">
                <h2 class="report-title" style="margin-bottom: 15px;">' . ucfirst($selectedTable) . ' Report</h2>
            </div>';

if (!empty($results)) {
    $dataHtml .= '<div class="table-responsive"><table class="styled-table"><tr>';

    $columns = array_keys($results[0]);
    foreach ($columns as $column) {
        $dataHtml .= "<th>{$column}</th>";
    }

    $dataHtml .= '</tr>';

    foreach ($results as $row) {
        $dataHtml .= "<tr>";
        foreach ($columns as $column) {
            $dataHtml .= "<td>" . ($row[$column] ?? 'N/A') . "</td>"; // Prevent undefined errors
        }
        $dataHtml .= "</tr>";
    }

    $dataHtml .= '</table></div>';
} else {
    $dataHtml .= '<p class="no-data">No records found.</p>';
}

// ✅ PDF Download Logic
if (isset($_GET['download']) && $_GET['download'] == 'pdf') {
    ob_clean(); // Clear any previous output to prevent TCPDF errors

    class PDF extends TCPDF
    {
        public function Header()
        {
            $this->SetFont('helvetica', 'B', 16);
            $this->SetTextColor(0, 102, 204);
            $this->Cell(0, 10, 'SpeedNet Internet Services', 0, 1, 'C');
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 8, 'Fast & Reliable Internet Solutions', 0, 1, 'C');
            $this->Ln(5);
            $this->SetDrawColor(0, 102, 204);
            $this->Line(10, 30, 200, 30);
            $this->Ln(5);
        }

        public function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $this->Cell(0, 10, 'SpeedNet | www.speednet.com | Contact: +123 456 7890', 0, 0, 'C');
        }
    }

  $pdf = new PDF();
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Ln(10);
$pdf->Cell(0, 12, ucfirst($selectedTable) . ' Report', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 8, 'Generated on: ' . date("Y-m-d H:i:s"), 0, 1, 'C');
$pdf->Ln(10);

if (!empty($results)) {
    // ✅ Define max width for columns and calculate dynamic width
    $columnWidths = [];
    $totalColumns = count($columns);
    $pageWidth = $pdf->GetPageWidth() - 20; // Page width minus margins
    $maxWidth = $pageWidth / max(1, $totalColumns);
    
    foreach ($columns as $column) {
        // Limit max width to 50mm to prevent overflow
        $columnWidths[] = min($maxWidth, 50);
    }

    // ✅ Header Styling
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetFillColor(0, 102, 204);
    $pdf->SetTextColor(255);
    
    foreach ($columns as $i => $column) {
        $pdf->Cell($columnWidths[$i], 10, ucfirst($column), 1, 0, 'C', true);
    }
    $pdf->Ln();

    // ✅ Data Rows with Padding and Alternating Colors
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(50);
    $fill = false;
    $recordCount = 0;

    foreach ($results as $row) {
        if ($recordCount > 0 && $recordCount % 5 === 0) {
            // ✅ Add new page after 5 records
            $pdf->AddPage();

            // ✅ Repeat header on new page
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetFillColor(0, 102, 204);
            $pdf->SetTextColor(255);
            foreach ($columns as $i => $column) {
                $pdf->Cell($columnWidths[$i], 10, ucfirst($column), 1, 0, 'C', true);
            }
            $pdf->Ln();
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(50);
        }

        foreach ($columns as $i => $column) {
            $pdf->SetFillColor($fill ? 240 : 255);

            // ✅ Use MultiCell to wrap text and prevent overflow
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell($columnWidths[$i], 8, ' ' . ($row[$column] ?? 'N/A') . ' ', 1, 'C', true);
            $pdf->SetXY($x + $columnWidths[$i], $y); // Move cursor to next column
        }
        $pdf->Ln();
        $fill = !$fill;
        $recordCount++;
    }
} else {
    $pdf->Cell(0, 10, 'No records found.', 1, 1, 'C');
}

// ✅ Output PDF
$pdf->Output('SpeedNet_Report.pdf', 'D');
exit;

    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Report</title>
    <link rel="stylesheet" href="reports.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
<div class="main-content">
    <main>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/css/dashboard2.php'; ?>
        <div class="container fade-in">
            <h1 class="page-title">Admin Report</h1>
            <form method="GET">
                <label for="table" class="label">Select Table:</label>
                <select name="table" id="table" class="dropdown" onchange="this.form.submit()">
                    <?php foreach ($tables as $table): ?>
                        <option value="<?= $table ?>" <?= ($selectedTable == $table) ? 'selected' : '' ?>><?= ucfirst($table) ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <a href="?table=<?= $selectedTable ?>&download=pdf" class="download-btn">Download PDF</a>
                <div class="table-container slide-up">
                    <?= $dataHtml; ?>
                </div>
            </div>
        </main>
    </div>
</body>

</html>