<?php

// API endpoint
$url = 'https://western.speaklar.com/api/api.php?id=call';

// API authorization token
$authToken = '4a9273911b5098280e9cbc';

// Data to send in the POST request
$data = [
    "phone_number" => "01731171023",
    "carrier" => "776666",
    "extension" => "5004"
];

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $authToken,
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute the cURL request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Print the response
    echo 'Response: ' . $response;
}

// Close the cURL session
curl_close($ch);

?>


























