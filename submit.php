<?php
// Set response content type
header("Content-Type: text/plain");

// Read the raw text payload from the POST request
$data = file_get_contents("php://input");

if ($data !== false && trim($data) !== '') {
    error_log("Original data: " . $data);

    // Split the TSV string into an array of fields using tab as the delimiter
    $fields = explode("\t", $data);
    error_log("Initial fields count: " . count($fields));

    // Ensure we have enough fields to perform the rearrangement.
    if (count($fields) >= 6) {
        // Remove and store the team number from the first field.
        $teamNumber = array_shift($fields);
        error_log("Raw team number: " . $teamNumber);
        
        // Convert the team number to an integer and then back to a string.
        $teamNumber = strval((int) trim($teamNumber));
        error_log("Processed team number: " . $teamNumber);

        // Insert "2025OHMV" into the second position (index 1)
        array_splice($fields, 1, 0, "2025OHMV");
        error_log("After inserting OHMV: " . implode("\t", $fields));

        // Insert the processed team number into the 6th position (index 5)
        array_splice($fields, 5, 0, $teamNumber);
        error_log("After inserting team number at index 5: " . implode("\t", $fields));

        // Reassemble the fields into a TSV string.
        $data = implode("\t", $fields);
    } else {
        error_log("Not enough fields to rearrange.");
    }

    // Append a newline if not already present.
    if (substr($data, -1) !== "\n") {
        $data .= "\n";
    }

    // Define the path to your CSV file.
    $csvFile = "data.txt";

    // Append the rearranged data to the file.
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
