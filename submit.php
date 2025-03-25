<?php
// Set response content type
header("Content-Type: text/plain");

// Read the raw text payload from the POST request
$data = file_get_contents("php://input");

if ($data !== false && trim($data) !== '') {
    // Define the path to your CSV file (it might be tab-separated data, but we’ll store it in data.csv)
    $csvFile = "data.csv";

    // Append a newline if not already present
    if (substr($data, -1) !== "\n") {
        $data .= "\n";
    }

    // Append the data to the file
    if (file_put_contents($csvFile, $data, FILE_APPEND | LOCK_EX) !== false) {
        echo "Data appended successfully";
    } else {
        http_response_code(500);
        echo "Error appending data";
    }
} else {
    http_response_code(400);
    echo "No data received";
}
?>
