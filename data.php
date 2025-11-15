<?php
header('Content-Type: application/json');

$filename = "internet_speed.csv";

if(!file_exists($filename)) {
    echo json_encode(["timestamps"=>[], "download"=>[], "upload"=>[], "ping"=>[]]);
    exit;
}

$timestamps = [];
$download = [];
$upload = [];
$ping = [];

if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $timestamps[] = $data[0];
        $download[] = (float)$data[1];
        $upload[] = (float)$data[2];
        $ping[] = (float)$data[3];
    }
    fclose($handle);
}

echo json_encode([
    "timestamps" => $timestamps,
    "download" => $download,
    "upload" => $upload,
    "ping" => $ping
]);
?>
