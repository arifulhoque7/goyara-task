<?php
function fetchXMLData($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/xml']);
    $response = curl_exec($curl);

    // Check for cURL errors
    if(curl_errno($curl)) {
        $error_msg = curl_error($curl);
        throw new Exception("cURL Error: " . $error_msg);
    }

    curl_close($curl);
    return $response;
}

try {
    $api_url = 'http://api.openweathermap.org/data/2.5/weather?q=London&mode=xml&appid=8ce33dd3a49e83c0ea5b0e6432066e46';

    $xml_data = fetchXMLData($api_url);

    if ($xml_data) {
        $xml = simplexml_load_string($xml_data);

        // Extract relevant information from the XML
        $location = $xml->city['name'];
        $temperature = $xml->temperature['value'];

        // Display the information in a structured HTML format
        echo "<h2>Weather Information</h2>";
        echo "<p><strong>Location:</strong> $location</p>";
        echo "<p><strong>Temperature:</strong> $temperature °C</p>";
    } else {
        echo "Failed to fetch XML data from the API.";
    }
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage();
}
?>
