<?php

use App\Models\Powerinai;
use App\Models\WesternCall;

function removeCountryCode($phoneNumber)
{
    // Array of country codes (ISO 3166-1 alpha-2)
    $countryCodes = [
        '+1',   // USA, Canada
        '+20',  // Egypt
        '+21',  // Libya
        '+212', // Morocco
        '+213', // Algeria
        '+216', // Tunisia
        '+218', // Libya
        '+220', // Gambia
        '+221', // Senegal
        '+222', // Mauritania
        '+223', // Mali
        '+224', // Guinea
        '+225', // Ivory Coast
        '+226', // Burkina Faso
        '+227', // Niger
        '+228', // Togo
        '+229', // Benin
        '+230', // Mauritius
        '+231', // Liberia
        '+232', // Sierra Leone
        '+233', // Ghana
        '+234', // Nigeria
        '+235', // Chad
        '+236', // Central African Republic
        '+237', // Cameroon
        '+238', // Cape Verde
        '+239', // Sao Tome and Principe
        '+240', // Equatorial Guinea
        '+241', // Gabon
        '+242', // Republic of Congo
        '+243', // Democratic Republic of the Congo
        '+244', // Angola
        '+245', // Guinea-Bissau
        '+246', // Diego Garcia
        '+247', // Ascension Island
        '+248', // Seychelles
        '+249', // Sudan
        '+250', // Rwanda
        '+251', // Ethiopia
        '+252', // Somalia
        '+253', // Djibouti
        '+254', // Kenya
        '+255', // Tanzania
        '+256', // Uganda
        '+257', // Burundi
        '+258', // Mozambique
        '+260', // Zambia
        '+261', // Madagascar
        '+262', // Réunion
        '+263', // Zimbabwe
        '+264', // Namibia
        '+265', // Malawi
        '+266', // Lesotho
        '+267', // Botswana
        '+268', // Swaziland
        '+269', // Comoros
        '+27',  // South Africa
        '+28',  // Namibia/others
        '+290', // Ascension Island
        '+291', // Eritrea
        '+297', // Aruba
        '+298', // Faroe Islands
        '+299', // Greenland
        '+31',  // Netherlands
        '+32',  // Belgium
        '+33',  // France
        '+34',  // Spain
        '+350', // Gibraltar
        '+351', // Portugal
        '+352', // Luxembourg
        '+353', // Ireland
        '+354', // Iceland
        '+355', // Albania
        '+356', // Malta
        '+357', // Cyprus
        '+358', // Finland
        '+359', // Bulgaria
        '+36',  // Hungary
        '+370', // Lithuania
        '+371', // Latvia
        '+372', // Estonia
        '+373', // Moldova
        '+374', // Armenia
        '+375', // Belarus
        '+376', // Andorra
        '+377', // Monaco
        '+378', // San Marino
        '+379', // Vatican City
        '+380', // Ukraine
        '+381', // Serbia
        '+382', // Montenegro
        '+383', // Kosovo
        '+385', // Croatia
        '+386', // Slovenia
        '+387', // Bosnia and Herzegovina
        '+388', // International Freephone Service
        '+389', // North Macedonia
        '+39',  // Italy
        '+40',  // Romania
        '+41',  // Switzerland
        '+42',  // Former Czechoslovakia
        '+420', // Czech Republic
        '+421', // Slovakia
        '+423', // Liechtenstein
        '+424', // Former USSR
        '+425', // ???
        '+426', // ???
        '+427', // ???
        '+428', // ???
        '+429', // ???
        '+43',  // Austria
        '+44',  // United Kingdom
        '+45',  // Denmark
        '+46',  // Sweden
        '+47',  // Norway
        '+48',  // Poland
        '+49',  // Germany
        '+500', // Falkland Islands
        '+501', // Belize
        '+502', // Guatemala
        '+503', // El Salvador
        '+504', // Honduras
        '+505', // Nicaragua
        '+506', // Costa Rica
        '+507', // Panama
        '+508', // Saint Pierre and Miquelon
        '+509', // Haiti
        '+51',  // Peru
        '+52',  // Mexico
        '+53',  // Cuba
        '+54',  // Argentina
        '+55',  // Brazil
        '+56',  // Chile
        '+57',  // Colombia
        '+58',  // Venezuela
        '+590', // Guadeloupe
        '+591', // Bolivia
        '+592', // Guyana
        '+593', // Ecuador
        '+594', // French Guiana
        '+595', // Paraguay
        '+596', // Martinique
        '+597', // Suriname
        '+598', // Uruguay
        '+599', // Netherlands Antilles
        '+60',  // Malaysia
        '+61',  // Australia
        '+62',  // Indonesia
        '+63',  // Philippines
        '+64',  // New Zealand
        '+65',  // Singapore
        '+66',  // Thailand
        '+670', // East Timor
        '+672', // Australian External Territories
        '+673', // Brunei
        '+674', // Nauru
        '+675', // Papua New Guinea
        '+676', // Tonga
        '+677', // Solomon Islands
        '+678', // Vanuatu
        '+679', // Fiji
        '+680', // Palau
        '+681', // Wallis and Futuna
        '+682', // Cook Islands
        '+683', // Niue
        '+685', // Samoa
        '+686', // Kiribati
        '+687', // New Caledonia
        '+688', // Tuvalu
        '+689', // French Polynesia
        '+7',   // Russia & Kazakhstan
        '+81',  // Japan
        '+82',  // South Korea
        '+84',  // Vietnam
        '+850', // North Korea
        '+851', // ???
        '+852', // Hong Kong
        '+853', // Macau
        '+855', // Cambodia
        '+856', // Laos
        '+857', // ???
        '+86',  // China
        '+870', // Inmarsat
        '+871', // Inmarsat
        '+872', // Inmarsat
        '+873', // Inmarsat
        '+874', // Inmarsat
        '+875', // Inmarsat
        '+876', // Jamaica
        '+877', // International Freephone Service
        '+878', // Shared Cost Service
        '+879', // ??
        '+88', // Bangladesh
        '+881', // Global Mobile Satellite
        '+882', // International Networks
        '+883', // International Networks
        '+886', // Taiwan
        '+888', // ???
        '+90',  // Turkey
        '+91',  // India
        '+92',  // Pakistan
        '+93',  // Afghanistan
        '+94',  // Sri Lanka
        '+95',  // Myanmar
        '+960', // Maldives
        '+961', // Lebanon
        '+962', // Jordan
        '+963', // Syria
        '+964', // Iraq
        '+965', // Kuwait
        '+966', // Saudi Arabia
        '+967', // Yemen
        '+968', // Oman
        '+969', // ???
        '+970', // Palestinian Territory
        '+971', // United Arab Emirates
        '+972', // Israel
        '+973', // Bahrain
        '+974', // Qatar
        '+975', // Bhutan
        '+976', // Mongolia
        '+977', // Nepal
        '+978', // ???
        '+979', // ???
        '+98',  // Iran
        '+992', // Tajikistan
        '+993', // Turkmenistan
        '+994', // Azerbaijan
        '+995', // Georgia
        '+996', // Kyrgyzstan
        '+998', // Uzbekistan
    ];

    // Create a regex pattern from the country codes
    $pattern = '/^(' . implode('|', array_map('preg_quote', $countryCodes)) . ')/';

    // Remove the country code from the phone number
    $cleanedNumber = preg_replace($pattern, '', $phoneNumber);

    // Trim any leading/trailing whitespace
    return trim($cleanedNumber);
}


function updateCallDataPai($id)
{

    $call = Powerinai::find($id);

    if (!$call) {
        return false;
    }

    // API endpoint
    $url = 'https://powerinai.speaklar.com/api/api.php?id=call_details';
    $status = false;
    $authToken = '4f239e8837559bdd543a9c';
    $webhook_url = "https://services.leadconnectorhq.com/hooks/FFoC8B5sSLMlbYaQ4Tcz/webhook-trigger/14dfb751-ae5b-499c-8e82-adac3ac24d8b";

    // Data to send in the POST request
    $data = [
        "uuid" => $call->call_id
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
    $response = json_decode($response);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {

        $status = true;
    }

    // Close the cURL session
    curl_close($ch);


    if ($status) {

        if ($call->response !== null) {

            // Decode the JSON response stored in the 'response' field
            $call_response = json_decode($call->response);
            $call_response_a =  json_decode($call_response->webhook_response);

          $sendData = [
             "phone" => $call->phone,
             "call_id" => $call->call_id,
             "inbound" => $call->name == 'outbound' ? 'NO' : 'YES',
             "recording_url" => $response[0]->audio_url ?? '',
             "is_interested" => $call_response_a->is_interested ?? '',
             "name" => $call_response_a->name ?? '',
             "business_name" => $call_response_a->business_name ?? '',
             "main_goal_or_challenge" => $call_response_a->main_goal_or_challenge ?? '',
             "automation_focus" => $call_response_a->automation_focus ?? '',
             "current_communication_methods" => $call_response_a->current_communication_methods ?? '',
             "demo_scheduling_preference" => $call_response_a->demo_scheduling_preference ?? '',
             "summary" => $call_response_a->call_summary ?? '',
             "status" => $response[0]->disposition ?? '',
             "call_type" => $call->call_type
            ];


            $call->is_call_completed = $response[0]->disposition ?? null;
            $call->is_send_gohihglevel = true;
            $call->save();

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $webhook_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $sendData
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);



        }
    }
}
function updateCallDataPaiBn($id)
{

    $call = Powerinai::find($id);

    if (!$call) {
        return false;
    }

    // API endpoint
    $url = 'https://ai.speaklar.com/api/api.php?id=call_details';
    $status = false;
    $authToken = '4a9273911b5098280e9cbc';
    $webhook_url = "https://services.leadconnectorhq.com/hooks/FFoC8B5sSLMlbYaQ4Tcz/webhook-trigger/14dfb751-ae5b-499c-8e82-adac3ac24d8b";

    // Data to send in the POST request
    $data = [
        "uuid" => $call->call_id
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
    $response = json_decode($response);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {

        $status = true;
    }

    // Close the cURL session
    curl_close($ch);


    if ($status) {

        if ($call->response !== null) {

            // Decode the JSON response stored in the 'response' field
            $call_response = json_decode($call->response);
            $call_response_a =  json_decode($call_response->webhook_response);

          $sendData = [
             "phone" => $call->phone,
             "call_id" => $call->call_id,
             "inbound" => $call->name == 'outbound' ? 'NO' : 'YES',
             "recording_url" => $response[0]->audio_url ?? '',
             "is_interested" => $call_response_a->is_interested ?? '',
             "name" => $call_response_a->name ?? '',
             "business_name" => $call_response_a->business_name ?? '',
             "main_goal_or_challenge" => $call_response_a->main_goal_or_challenge ?? '',
             "automation_focus" => $call_response_a->automation_focus ?? '',
             "current_communication_methods" => $call_response_a->current_communication_methods ?? '',
             "demo_scheduling_preference" => $call_response_a->demo_scheduling_preference ?? '',
             "summary" => $call_response_a->call_summary ?? '',
             "status" => $response[0]->disposition ?? '',
             "call_type" => $call->call_type
            ];


            $call->is_call_completed = $response[0]->disposition ?? null;
            $call->is_send_gohihglevel = true;
            $call->save();

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $webhook_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $sendData
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);



        }
    }
}

function updateCallData($id)
{

    $call = WesternCall::find($id);

    if (!$call) {
        return false;
    }

    // API endpoint
    $url = 'https://ai.speaklar.com/api/api.php?id=call_details';
    $status = false;
    $authToken = '4a9273911b5098280e9cbc';
    $webhook_url = 'https://services.leadconnectorhq.com/hooks/jbqBCI8qUQX3idpEyWym/webhook-trigger/ca2a2cc6-d92a-4291-9452-a81c98bc287a';

    // Data to send in the POST request
    $data = [
        "uuid" => $call->call_id
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
    $response = json_decode($response);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {

        $status = true;
    }

    // Close the cURL session
    curl_close($ch);


    if ($status) {

        if ($call->response !== null) {

            // Decode the JSON response stored in the 'response' field
            $call_response = json_decode($call->response);
            $call_response_a =  json_decode($call_response->webhook_response);

          $sendData = [
             "phone" => $call->phone,
             "call_id" => $call->call_id,
             "inbound" => $call->name == 'outbound' ? 'NO' : 'YES',
             "recording_url" => $response[0]->audio_url ?? '',
             "name" => $call_response_a->name ?? '',
             "country" => $call_response_a->country ?? '',
             "subject" => $call_response_a->subject ?? '',
             "program" => $call_response_a->program ?? '',
             "IELTS_status" => $call_response_a->IELTS_status ?? '',
             "CGPA" => $call_response_a->CGPA ?? '',
             "is_interested" => $call_response_a->is_interested ?? '',
             "summary" => $call_response_a->call_summary ?? '',
             "status" => $response[0]->disposition ?? '',
             "call_type" => $call->call_type
            ];

            $call->is_call_completed = $response[0]->disposition ?? null;
            $call->is_send_gohihglevel = true;
            $call->save();

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $webhook_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $sendData
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
        }
    }
}



function systemUpdateWes($language = 'bn-IN')
{

    $id = 'outbound';
    // API endpoint
    $url = "https://ai.speaklar.com/api/api.php?id=$id";

    // API authorization token
    $authToken = '4a9273911b5098280e9cbc';


    $webhook_prompt = [
        'name' => 'What is the student’s name? (Student’s name only—never the AI agent’s name)',
        'country' => 'Which country does the student want to study in? (Student’s info only)',
        'subject' => 'What subject does the student want to pursue? (Student’s info only)',
        'program' => 'Please specify the academic program the student is interested in. (Student’s info only)',
        'IELTS_status' => 'What is the student’s current IELTS status? (e.g., not taken, band score) (Student’s info only)',
        'CGPA' => 'What is the student’s current CGPA or grade point average? (Student’s info only)',
        'is_interested' => 'Is the student interested in this opportunity? (yes/no/maybe) (Student’s info only)',
        'call_summary' => 'Please summarize this conversation for future reference regarding the student. (Only details about the student; do not mention the AI agent)'
    ];

    // Data to send in the POST request
    $data = [
        "webhook" => "http://68.183.189.27/western/callback-call",
        "webhook_prompt" => json_encode($webhook_prompt),
        "text_to_speech_language" => "$language",
        "text_to_speech_gender" => "MALE",
        "text_to_speech_name" => "bn-IN-Wavenet-B",
        "welcome_message" => "Hello this is Your dedicated AI Assistant Aiva from Power in AI",
        "pause_message" => "Is there anything else i can help you with?",
        "pause" => 20,
        "system_prompt" => "Introduction
Aiva:
'In Powerin Ai We create AI-driven solutions to help businesses run smoother—like Conversation AI, Task Automator, and Analytics Dashboards.
How are things going for you in your [industry or business] lately?'
(No long pauses; keep it flowing while speaking clearly.)

Exploring Needs (Interactive and Conversational)
Aiva:
'I’d love to learn more about your goals. May I ask a couple of quick questions to see where we can help?
What’s the biggest challenge you face with repetitive tasks?
How do you currently manage customer interactions across different channels?
Is there one key process you’d love to automate right away?'
(Pause briefly after each question to let the customer respond, then acknowledge or clarify. Avoid overly repetitive praise like “That’s a great question!” after every input.)

Building Rapport (Acknowledging, Encouraging, and Connecting)
Use short, genuine acknowledgments after the customer shares something substantial:
Acknowledging Challenges:
'That does sound time-consuming. I’d love to show you how we can streamline it.'
Celebrating Progress:
'It’s great you’ve already taken steps in that area—Powerin AI can help you do even more.'
(Keep these affirmations succinct and meaningful.)

Addressing Questions
Aiva:
'Feel free to ask anything about our features or how they fit your business. I’ll keep it straightforward and clear.'
(Respond promptly, keep explanations concise, and speak clearly so the customer doesn’t get lost.)

Pricing Inquiries
Aiva:
'We have a dedicated pricing team who can give you tailored details. I’ll make sure they follow up with you if you’re interested.'
(Politely redirect pricing specifics to the pricing team.)

Redirecting with Warmth (If Needed)
Aiva:
'I understand that’s important. My focus today is showing you how our AI solutions can support your business. Let’s dive deeper into that.'

Appointment Encouragement
Aiva:
'I’d love for you to connect with one of our experts. I can send you a calendar link via SMS—does that sound good?'
(Immediately confirm if the customer agrees, without long wait times, but speak slowly enough to be clear.)

Final Reminder
Aiva:
'Thanks again for chatting with me! Keep an eye on your text messages for that booking link. Once you pick a time, we’ll connect to see how Powerin AI can help you streamline and grow your business. Take care!'
(No reference to email; booking link is sent via SMS.)

Additional Rapport-Building Tips
Active Listening: Use brief affirmations like “Got it,” “I understand,” or “Thanks for sharing.”
Show Enthusiasm: Expressions like “I’m excited to help” show genuine interest but avoid being overly repetitive.
Stay Personable: Keep the dialogue friendly and natural, focusing on how you can help rather than excessive small talk.

Knowledge Base 1: Business Overview
What is Power in AI?
Power in AI is a platform delivering AI-driven solutions to streamline business processes, automate sales, and accelerate growth.
What does “Hire AI Employee” mean?
It refers to our AI-powered virtual assistant that automates tasks like inbound/outbound calls, lead management, and appointment scheduling.
What industries benefit from Power in AI?
Retail, real estate, healthcare, hospitality, and finance.
What results can Power in AI deliver?
Improved lead conversion, reduced costs, faster customer response times, and streamlined workflows.
What services does the AI Employee provide?
Customer Interaction Management: Handles calls and engages across SMS, WhatsApp, and social media.
Lead Management: Automates follow-ups and messaging.
Appointment Scheduling: Fully automates bookings and reminders.
How does Power in AI integrate with existing systems?
It works seamlessly with popular CRMs like Salesforce, Zoho, and HubSpot, ensuring real-time data updates.
Why choose Power in AI?
Our tailored AI solutions are designed for measurable ROI, reduced operational costs, and enhanced customer engagement.

Knowledge Base 2: Frequently Asked Questions
Can the AI Employee handle large call volumes?
Yes, it scales effortlessly to manage thousands of interactions simultaneously.
Is the AI Employee customizable?
Yes, it adapts to your business needs with tailored workflows and messaging.
How does appointment scheduling work?
It integrates with Google Calendar, Zoom, and CRMs, automating bookings and reminders.
Does Power in AI offer post-deployment support?
Absolutely. We provide ongoing assistance and optimization.
How quickly can I see improvements?
Businesses typically notice faster response times and better engagement shortly after deployment.
Can the AI Employee engage customers on social media?
Yes, it manages interactions on platforms like Facebook, Instagram, and Google My Business.
Does this solution reduce costs?
Yes, many clients report a significant decrease in manual workload and operational expenses.
How do I get started?
Contact us via phone, email, or website, and we’ll guide you through the process.
CONTACT
+880 1670309328 +880 1711925048
Boshoti Legacy, House 29, Road 6, Dhanmondi, Dhaka 1205, Bangladesh
+971 529 32 4987 +18777941702
25th Floor, The Citadel Tower, Business Bay, Dubai
info@powerinai.com
www.powerinai.com"
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
        echo  $response;
    }

    // Close the cURL session
    curl_close($ch);
    return true;
}


//  function systemUpdatePai($language = 'en-US')
// {

//     $id = 'outbound';
//     // API endpoint
//     $url = "https://powerinai.speaklar.com/api/api.php?id=$id";

//     // API authorization token
//     $authToken = '4f239e8837559bdd543a9c';

//     $webhook_prompt = [
//         // Capture the client's name (not the AI agent's name)
//         "name" => 'What is the client’s name? (Client’s info only)',
//         // Capture the business or company name
//         "business_name" => 'What is the name of the client’s business?',
//         // Identify the client’s primary goal or challenge
//         "main_goal_or_challenge" => 'What is the biggest goal or challenge the client wants to address with PowerinAI?',
//         // Determine which processes or areas the client wants to automate
//         "automation_focus" => 'Which processes does the client want to automate or streamline?',
//         // Understand the client's current communication methods
//         "current_communication_methods" => 'How is the client currently managing customer communication?',
//         // Gather the client’s preference for a demo date/time
//         "demo_scheduling_preference" => 'When is the client available/prefer to schedule the demo?',
//         // Check if the client is interested in proceeding or scheduling
//         'is_interested' => 'Is the client interested in this solution or scheduling a demo? (yes/no/maybe) (Client’s info only)',
//         // General summary of the call, focusing on the client’s needs and context
//         "call_summary" => 'Please summarize the conversation regarding the client’s specific needs, challenges, and desired outcomes.'
//     ];

//     // Data to send in the POST request
//     $data = [
//         "webhook" => "http://68.183.189.27/powerinai/callback-call",
//         "webhook_prompt" => json_encode($webhook_prompt),
//         "text_to_speech_language" => "$language",
//         "text_to_speech_gender" => "FEMALE",
//         "text_to_speech_name" => "bn-IN-Wavenet-B",
//         "welcome_message" => "Hello this is Your dedicated AI Assistant Aiva from Power in AI",
//         "pause_message" => "Is there anything else i can help you with?",
//         "pause" => 20,
//         "system_prompt" => "Introduction
// Aiva:
// 'In Powerin Ai We create AI-driven solutions to help businesses run smoother—like Conversation AI, Task Automator, and Analytics Dashboards.
// How are things going for you in your [industry or business] lately?'
// (No long pauses; keep it flowing while speaking clearly.)

// Exploring Needs (Interactive and Conversational)
// Aiva:
// 'I’d love to learn more about your goals. May I ask a couple of quick questions to see where we can help?
// What’s the biggest challenge you face with repetitive tasks?
// How do you currently manage customer interactions across different channels?
// Is there one key process you’d love to automate right away?'
// (Pause briefly after each question to let the customer respond, then acknowledge or clarify. Avoid overly repetitive praise like “That’s a great question!” after every input.)

// Building Rapport (Acknowledging, Encouraging, and Connecting)
// Use short, genuine acknowledgments after the customer shares something substantial:
// Acknowledging Challenges:
// 'That does sound time-consuming. I’d love to show you how we can streamline it.'
// Celebrating Progress:
// 'It’s great you’ve already taken steps in that area—Powerin AI can help you do even more.'
// (Keep these affirmations succinct and meaningful.)

// Addressing Questions
// Aiva:
// 'Feel free to ask anything about our features or how they fit your business. I’ll keep it straightforward and clear.'
// (Respond promptly, keep explanations concise, and speak clearly so the customer doesn’t get lost.)

// Pricing Inquiries
// Aiva:
// 'We have a dedicated pricing team who can give you tailored details. I’ll make sure they follow up with you if you’re interested.'
// (Politely redirect pricing specifics to the pricing team.)

// Redirecting with Warmth (If Needed)
// Aiva:
// 'I understand that’s important. My focus today is showing you how our AI solutions can support your business. Let’s dive deeper into that.'

// Appointment Encouragement
// Aiva:
// 'I’d love for you to connect with one of our experts. I can send you a calendar link via SMS—does that sound good?'
// (Immediately confirm if the customer agrees, without long wait times, but speak slowly enough to be clear.)

// Final Reminder
// Aiva:
// 'Thanks again for chatting with me! Keep an eye on your text messages for that booking link. Once you pick a time, we’ll connect to see how Powerin AI can help you streamline and grow your business. Take care!'
// (No reference to email; booking link is sent via SMS.)

// Additional Rapport-Building Tips
// Active Listening: Use brief affirmations like “Got it,” “I understand,” or “Thanks for sharing.”
// Show Enthusiasm: Expressions like “I’m excited to help” show genuine interest but avoid being overly repetitive.
// Stay Personable: Keep the dialogue friendly and natural, focusing on how you can help rather than excessive small talk.

// Knowledge Base 1: Business Overview
// What is Power in AI?
// Power in AI is a platform delivering AI-driven solutions to streamline business processes, automate sales, and accelerate growth.
// What does “Hire AI Employee” mean?
// It refers to our AI-powered virtual assistant that automates tasks like inbound/outbound calls, lead management, and appointment scheduling.
// What industries benefit from Power in AI?
// Retail, real estate, healthcare, hospitality, and finance.
// What results can Power in AI deliver?
// Improved lead conversion, reduced costs, faster customer response times, and streamlined workflows.
// What services does the AI Employee provide?
// Customer Interaction Management: Handles calls and engages across SMS, WhatsApp, and social media.
// Lead Management: Automates follow-ups and messaging.
// Appointment Scheduling: Fully automates bookings and reminders.
// How does Power in AI integrate with existing systems?
// It works seamlessly with popular CRMs like Salesforce, Zoho, and HubSpot, ensuring real-time data updates.
// Why choose Power in AI?
// Our tailored AI solutions are designed for measurable ROI, reduced operational costs, and enhanced customer engagement.

// Knowledge Base 2: Frequently Asked Questions
// Can the AI Employee handle large call volumes?
// Yes, it scales effortlessly to manage thousands of interactions simultaneously.
// Is the AI Employee customizable?
// Yes, it adapts to your business needs with tailored workflows and messaging.
// How does appointment scheduling work?
// It integrates with Google Calendar, Zoom, and CRMs, automating bookings and reminders.
// Does Power in AI offer post-deployment support?
// Absolutely. We provide ongoing assistance and optimization.
// How quickly can I see improvements?
// Businesses typically notice faster response times and better engagement shortly after deployment.
// Can the AI Employee engage customers on social media?
// Yes, it manages interactions on platforms like Facebook, Instagram, and Google My Business.
// Does this solution reduce costs?
// Yes, many clients report a significant decrease in manual workload and operational expenses.
// How do I get started?
// Contact us via phone, email, or website, and we’ll guide you through the process.
// CONTACT
// +880 1670309328 +880 1711925048
// Boshoti Legacy, House 29, Road 6, Dhanmondi, Dhaka 1205, Bangladesh
// +971 529 32 4987 +18777941702
// 25th Floor, The Citadel Tower, Business Bay, Dubai
// info@powerinai.com
// www.powerinai.com"

//     ];

//     // Initialize cURL
//     $ch = curl_init();

//     // Set cURL options
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, [
//         'Authorization: Bearer ' . $authToken,
//         'Content-Type: application/json',
//     ]);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

//     // Execute the cURL request
//     $response = curl_exec($ch);

//     // Check for errors
//     if (curl_errno($ch)) {
//         echo 'Error: ' . curl_error($ch);
//     } else {
//         // Print the response
//         echo  $response;
//     }

//     // Close the cURL session
//     curl_close($ch);
// }
