<?php

// API endpoint
$url = 'https://ai.speaklar.com/api/api.php?id=call';

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


CREATE DATABASE speaklar_api;
CREATE USER 'speaklar_api'@'localhost' IDENTIFIED BY 'Erty^%^%fgxhjhs162FGF$';
GRANT ALL PRIVILEGES ON speaklar_api.* TO 'speaklar_api'@'localhost';
FLUSH PRIVILEGES;
EXIT;


sudo chown -R www-data:www-data /var/www/speaklar_api
sudo chmod -R 775 /var/www/speaklar_api/storage /var/www/speaklar_api/bootstrap/cache

?>





























