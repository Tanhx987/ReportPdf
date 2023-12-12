<?php

require 'vendor/autoload.php';
// 连接数据库
function connectToDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "admin123";
    $db = "test";

    $conn = new mysqli($servername, $username, $password, $db);

    // 检查连接是否成功
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// 查询数据
function fetchDataFromDatabase($conn) {
    $sql = "SELECT attendance.student_id, attendance.clock_type, attendance.clock_datetime, timetable.subject
            FROM attendance
            JOIN timetable ON attendance.student_id = timetable.student_id AND attendance.date = timetable.date
            WHERE attendance.student_id IN (1, 2, 3, 4)
            AND attendance.date BETWEEN '2023-12-04' AND '2023-12-08'";

    $result = $conn->query($sql);

    return $result;
}

// 生成PDF
function generatePDF($result) {
    require_once('C:/wamp64/www/tc-lib-pdf-main/src/Tcpdf.php');

    $pdf = new TCPDF();
    $pdf->SetMargins(10, 10, 10);

    $pdf->AddPage();

    // 输出表头
    $pdf->Cell(30, 10, 'Student ID', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Date', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Status', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Subject', 1, 1, 'C');

    // 输出查询结果
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(30, 10, $row['student_id'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['date'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['status'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['subject'], 1, 1, 'C');
    }

    // 输出PDF文件
    $pdf->Output('output.pdf', 'D');
}

// 主程序
$conn = connectToDatabase();
$result = fetchDataFromDatabase($conn);
generatePDF($result);

// 关闭数据库连接
$conn->close();
?>
