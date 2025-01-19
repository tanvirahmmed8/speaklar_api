<?php

// API endpoint
// $url = 'https://westernai.speaklar.com/api/api.php?id=call_details';
$url = 'https://powerinai.speaklar.com/api/api.php?id=call_details';

// API authorization token
// $authToken = '4a9273911b5098280e9cbc';
$authToken = '4f239e8837559bdd543a9c';

// Data to send in the POST request
$data = [
    "uuid" => "3f5e91a9e3c21d8fae20a603413def1c"
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

//  $response;
// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Print the response
  //  print_r(json_decode($response));
}

// Close the cURL session
curl_close($ch);

// $response = json_decode($response);
echo $response;
?>
