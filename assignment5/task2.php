<?php
// Function to parse CSV file and return data as an array
function parseCSV($file) {
    $csvData = [];
    $header = NULL;
    if (($handle = fopen($file, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (!$header) {
                $header = $row;
            } else {
                $csvData[] = array_combine($header, $row);
            }
        }
        fclose($handle);
    }
    return $csvData;
}

// Read CSV file and get the data
$csvFile = 'file.csv'; // Replace 'your_file.csv' with the actual path to your CSV file
$data = parseCSV($csvFile);

// Output data in an HTML table
echo '<table border="1">';
echo '<thead><tr>';
foreach ($data[0] as $key => $value) {
    echo '<th>' . htmlspecialchars($key) . '</th>';
}
echo '</tr></thead>';
echo '<tbody>';
foreach ($data as $row) {
    echo '<tr>';
    foreach ($row as $value) {
        echo '<td>' . htmlspecialchars($value) . '</td>';
    }
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';
?>
