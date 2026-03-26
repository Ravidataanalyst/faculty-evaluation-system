<?php
$db_host = "localhost";
$db_user = "odmufpjo_faculty_eval";
$db_pass = "Ravi==kumar123";
$db_name = "odmufpjo_faculty_eval";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filename = 'yourfile.sql';
$templine = '';
$lines = file($filename);
foreach ($lines as $line) {
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;
    $templine .= $line;
    if (substr(trim($line), -1, 1) == ';') {
        $conn->query($templine) or print ('Error: ' . $conn->error . '<br>');
        $templine = '';
    }
}
echo "SQL file imported successfully";
?>