<?php

use App\Models\Powerinai;
use App\Models\WesternCall;
use Illuminate\Support\Str;

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



function updateCallData($id)
{

    $call = WesternCall::find($id);

    // API endpoint
    $url = 'https://ai.speaklar.com/api/api.php?id=call_details';

    $status = false;

    // if ($company == "western") {
        $authToken = '4a9273911b5098280e9cbc';
        $webhook_url = 'https://services.leadconnectorhq.com/hooks/jbqBCI8qUQX3idpEyWym/webhook-trigger/ca2a2cc6-d92a-4291-9452-a81c98bc287a';

    // } else {
    //     $authToken = '';
    //     $webhook_url = "https://services.leadconnectorhq.com/hooks/FFoC8B5sSLMlbYaQ4Tcz/webhook-trigger/14dfb751-ae5b-499c-8e82-adac3ac24d8b";
    // }



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
        // Print the response
        // print_r(json_decode($response));
        $status = true;
    }

    // Close the cURL session
    curl_close($ch);



    if ($status) {
        // return $response[0]->disposition;
        // return $response[0]->audio_url;
        // return $call->phone;

        // $call_response = json_decode($call->response);

        // $webhook_response = json_decode($call_response->webhook_response);
        //    return $call_response->webhook_response;


        // Decode the JSON response stored in the 'response' field
        $call_response = json_decode($call->response);

        // Access the 'webhook_response' which also needs to be decoded if it's a string
       $webhook_response = Str::replace("{",'',$call_response->webhook_response);
       $webhook_response = Str::replace("}",'',$webhook_response);
       $webhook_response = explode(':', $webhook_response);
    //    $webhook_response = json_decode($webhook_response, true);

        // Now we can access the 'call_summary'
        // if (isset($call_response->webhook_response)) {
            $webhook_in = explode(',', $webhook_response[7]);
            $is_interested = $webhook_in[0]; // Return the call summary
            $summary = $webhook_response[8]; // Return the call summary
        // } else {
        //     return 'Call summary not found.'; // Return a message if call_summary doesn't exist
        // }

        // die();
        // ghl code
        $sendData = [
            "summary" => $summary,
            "phone" => $call->phone,
            "call_id" => $call->call_id,
            "inbound" => 'NO',
            "recording_url" => $response[0]->audio_url ?? '',
            "is_interested" => $is_interested,
            "status" => $response[0]->disposition ?? ''
        ];


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

function updateCallDataPai($id)
{

    $call = Powerinai::find($id);

    // API endpoint
    $url = 'https://powerinai.speaklar.com/api/api.php?id=call_details';

    $status = false;


        $authToken = '';
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
        // Print the response
        // print_r(json_decode($response));
        $status = true;
    }

    // Close the cURL session
    curl_close($ch);



    if ($status) {
        // return $response[0]->disposition;
        // return $response[0]->audio_url;
        // return $call->phone;

        // $call_response = json_decode($call->response);

        // $webhook_response = json_decode($call_response->webhook_response);
        //    return $call_response->webhook_response;


        // Decode the JSON response stored in the 'response' field
        $call_response = json_decode($call->response);

        // Access the 'webhook_response' which also needs to be decoded if it's a string
       $webhook_response = Str::replace("{",'',$call_response->webhook_response);
       $webhook_response = Str::replace("}",'',$webhook_response);
       $webhook_response = explode(':', $webhook_response);
    //    $webhook_response = json_decode($webhook_response, true);

        // Now we can access the 'call_summary'
        // if (isset($call_response->webhook_response)) {
            $webhook_in = explode(',', $webhook_response[7]);
            $is_interested = $webhook_in[0]; // Return the call summary
            $summary = $webhook_response[8]; // Return the call summary
        // } else {
        //     return 'Call summary not found.'; // Return a message if call_summary doesn't exist
        // }

        // die();
        // ghl code
        $sendData = [
            "summary" => $summary,
            "phone" => $call->phone,
            "call_id" => $call->call_id,
            "inbound" => 'NO',
            "recording_url" => $response[0]->audio_url ?? '',
            "is_interested" => $is_interested,
            "status" => $response[0]->disposition ?? ''
        ];


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
