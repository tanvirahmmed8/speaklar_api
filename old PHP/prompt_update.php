<?php

// API endpoint
$url = 'https://westernai.speaklar.com/api/api.php?id=outbound';

// API authorization token
$authToken = '4a9273911b5098280e9cbc';

// Data to send in the POST request
$data = [
    "webhook" => "https://brandellaltd.com/call-api/callback-call-mm",
    "webhook_prompt" => '{
  "name":...,
  "country":...,
  "subject":...,
  "program":...,
  "email":...,
  "IELTS_status":...,
  "CGPA":...,
  "inbound":"Yes/No",
  "phone":...,
  "call_id":...,
  "uuid":...,
  "recording_url":...,
  "status":"complete/incomplete",
  "is_interested":"yes/no/maybe",
  "call_summary":"give me this conversation summary"
}',
    "text_to_speech_language" => "bn-IN",
    "text_to_speech_gender" => "MALE",
    "text_to_speech_name" => "bn-IN-Wavenet-B",
    "welcome_message" => "Hello this is Aiva from Power in AI",
    "pause_message" => "Are you there?",
    "pause" => 20,
    "system_prompt" => "AI Caller Prompt for Appointment Reminder

[Greeting and Introduction]
“Hello Mr, this is Aiva calling from PowerIn AI. How are you today?”

[Purpose of the Call]
“I just wanted to remind you that your appointment with our team is scheduled for [Appointment Time], which is about two hours from now. We’re excited to connect and share how PowerIn AI can bring value to your business.”

[Confirmation of Attendance]
“Can you confirm if you’ll be able to join us at 3.00 PM, or would you prefer to reschedule for another time?”

[Response Handling]

If the Client Confirms:
“Great! We look forward to seeing you at 3.00 PM. If there’s anything specific you’d like to discuss during the demo, let me know. See you soon!”

If the Client Needs to Reschedule:
“Understood, let’s find a time that works better for you. I’ll send over a confirmation email with the updated details. What time would suit you best?”

[Closing]
“Thank you for your time, Mr. We’re looking forward to connecting!”

",
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
