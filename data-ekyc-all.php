<?php
// data.php
header('Content-Type: application/json');


// เชื่อมต่อฐานข้อมูล
$servername = "127.0.0.1";
$username = "root";
$password = "12345678";
$dbname = "chart_example";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากตาราง ekyc_detail
$sql = "select distinct (mem_id), DATE_FORMAT(date_ekyc, '%Y-%m') as month FROM ekyc_detail";
$result = $conn->query($sql);

$monthly_counts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $month = $row['month'];
        if (!isset($monthly_counts[$month])) {
            $monthly_counts[$month] = 0;
        }
        $monthly_counts[$month]++;
    }
}

$conn->close();

// เตรียมข้อมูลสำหรับ Chart.js
$labels = array_keys($monthly_counts);
$values = array_values($monthly_counts);

$response = [
    "labels" => $labels,
    "datasets" => [
        [
            "label" => "จำนวนสมาชิกที่ทำ E-KYC ต่อเดือน",
            "backgroundColor" => "rgba(35,137,231)",
            "borderColor" => "rgba(35,137,231)",
            "borderWidth" => 1,
            "data" => $values
        ]
    ]
];
echo json_encode($response);
?>
