<?php

namespace App\Http\Controllers;

use App\Models\Powerinai;
use Illuminate\Http\Request;

class PowerinaiController extends Controller
{
    public function index()
    {
        return Powerinai::all();
    }
    public function sendCall(Request $request)
    {

        // $cleanedPhone = removeCountryCode($request->phone);
        $cleanedPhone = $request->phone;

        // API endpoint
        $url = 'https://powerinai.speaklar.com/api/api.php?id=call';

        // API authorization token
        $authToken = '4f239e8837559bdd543a9c';

        // Prepare the language data
        // $languageData = $request->customData['language'] ?? 'en-US';

        // Call the systemUpdate method directly
        // systemUpdatePai($languageData);


        $webhook_prompt = [
            // Capture the client's name (not the AI agent's name)
            "name" => 'What is the client’s name? (Client’s info only)',
            // Capture the business or company name
            "business_name" => 'What is the name of the client’s business?',
            // Identify the client’s primary goal or challenge
            "main_goal_or_challenge" => 'What is the biggest goal or challenge the client wants to address with PowerinAI?',
            // Determine which processes or areas the client wants to automate
            "automation_focus" => 'Which processes does the client want to automate or streamline?',
            // Understand the client's current communication methods
            "current_communication_methods" => 'How is the client currently managing customer communication?',
            // Gather the client’s preference for a demo date/time
            "demo_scheduling_preference" => 'When is the client available/prefer to schedule the demo?',
            // Check if the client is interested in proceeding or scheduling
            'is_interested' => 'Is the client interested in this solution or scheduling a demo? (yes/no/maybe) (Client’s info only)',
            // General summary of the call, focusing on the client’s needs and context
            "call_summary" => 'Please summarize the conversation regarding the client’s specific needs, challenges, and desired outcomes.'
        ];


        // Data to be sent in the request
        $data = [
            "phone_number" => "$cleanedPhone",
            "carrier" => "911035",
            "extension" => "5003",
            "pause" => 10,
            "welcome_message" => $request->customData['welcome_message'] ?? $request->welcome_message,
            "pause_message" => $request->customData['pause_message'] ?? $request->pause_message,
            "system_prompt" => $request->customData['call_prompt'] ?? $request->call_prompt,
            "webhook_prompt" => json_encode($webhook_prompt),
            "webhook" => "http://68.183.189.27/powerinai/callback-call",
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
        $err = curl_error($ch);

        // Close the cURL session
        curl_close($ch);


        $status = false;
        $call_id = null;
        $message = null;
        $err_message = null;

        if ($err) {
            $status = false;
            $err_message = $err;
        } else {
            $response = json_decode($response);
            if (isset($response->message)) {
                $status = true;
                $message = $response->message;
            }
            if (isset($response->uuid)) {
                $status = true;
                $call_id = $response->uuid;
            }
        }


        $callInfo = new Powerinai();
        $callInfo->name = $request->full_name;
        $callInfo->phone = $request->phone;
        $callInfo->call_id = $call_id;
        $callInfo->status = $status;
        $callInfo->message = $message;
        $callInfo->call_type = $request->customData['call_type'] ?? 'default';
        $callInfo->err_message = $err_message;
        $callInfo->save();

        return response()->json($callInfo, 200);
    }
    public function sendCallBn(Request $request)
    {

        $cleanedPhone = removeCountryCode($request->phone);
        // $cleanedPhone = $request->phone;

      // API endpoint
      $url = 'https://westernai.speaklar.com/api/api.php?id=call';

      // API authorization token
      $authToken = '4a9273911b5098280e9cbc';

        // Prepare the language data
        // $languageData = $request->customData['language'] ?? 'en-US';

        // Call the systemUpdate method directly
        // systemUpdatePai($languageData);


        $webhook_prompt = [
            // Capture the client's name (not the AI agent's name)
            "name" => 'What is the client’s name? (Client’s info only)',
            // Capture the business or company name
            "business_name" => 'What is the name of the client’s business?',
            // Identify the client’s primary goal or challenge
            "main_goal_or_challenge" => 'What is the biggest goal or challenge the client wants to address with PowerinAI?',
            // Determine which processes or areas the client wants to automate
            "automation_focus" => 'Which processes does the client want to automate or streamline?',
            // Understand the client's current communication methods
            "current_communication_methods" => 'How is the client currently managing customer communication?',
            // Gather the client’s preference for a demo date/time
            "demo_scheduling_preference" => 'When is the client available/prefer to schedule the demo?',
            // Check if the client is interested in proceeding or scheduling
            'is_interested' => 'Is the client interested in this solution or scheduling a demo? (yes/no/maybe) (Client’s info only)',
            // General summary of the call, focusing on the client’s needs and context
            "call_summary" => 'Please summarize the conversation regarding the client’s specific needs, challenges, and desired outcomes.'
        ];


        // Data to be sent in the request
        $data = [
            "phone_number" => "$cleanedPhone",
            "carrier" => "911035",
            "extension" => "5004",
            "pause" => 10,
            "welcome_message" => $request->customData['welcome_message'] ?? $request->welcome_message,
            "pause_message" => $request->customData['pause_message'] ?? $request->pause_message,
            "system_prompt" => $request->customData['call_prompt'] ?? $request->call_prompt,
            "webhook_prompt" => json_encode($webhook_prompt),
            "webhook" => "http://68.183.189.27/powerinai/callback-call-bn",
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
        $err = curl_error($ch);

        // Close the cURL session
        curl_close($ch);


        $status = false;
        $call_id = null;
        $message = null;
        $err_message = null;

        if ($err) {
            $status = false;
            $err_message = $err;
        } else {
            $response = json_decode($response);
            if (isset($response->message)) {
                $status = true;
                $message = $response->message;
            }
            if (isset($response->uuid)) {
                $status = true;
                $call_id = $response->uuid;
            }
        }


        $callInfo = new Powerinai();
        $callInfo->name = $request->full_name;
        $callInfo->phone = $request->phone;
        $callInfo->call_id = $call_id;
        $callInfo->status = $status;
        $callInfo->message = $message;
        $callInfo->call_type = $request->customData['call_type'] ?? 'default';
        $callInfo->err_message = $err_message;
        $callInfo->save();

        return response()->json($callInfo, 200);
    }


    public function callbackCall(Request $request)
    {

        $call_id = null;
        $need_update = false;
        $phn = null;

        if (isset($request->src)) {
            $phn = "+" . $request->src;
        }

        if (isset($request->uuid)) {
            $call_id = $request->uuid;

            if (Powerinai::where('call_id', $call_id)->exists()) {
                $callInfo = Powerinai::where('call_id', $call_id)->first();
                $need_update = true;
                $callInfo->name = "outbound";
            } else {
                $callInfo = new Powerinai();
                $callInfo->call_id = $call_id;
                $callInfo->phone = $phn;
                $callInfo->name = "inbound";
                $need_update = true;
            }

            $callInfo->response = json_encode($request->all());
            $callInfo->save();
        } else {
            $callInfo = new Powerinai();
            $callInfo->name = $call_id;
            $callInfo->phone = $phn;
            $callInfo->response = json_encode($request->all());
            $callInfo->save();
        }



        if ($need_update) {
            sleep(3);
            updateCallDataPai($callInfo->id);
        }

        return response()->json($callInfo, 200);
    }
    public function callbackCallBn(Request $request)
    {

        $call_id = null;
        $need_update = false;
        $phn = null;

        if (isset($request->src)) {
            $phn = "+" . $request->src;
        }

        if (isset($request->uuid)) {
            $call_id = $request->uuid;

            if (Powerinai::where('call_id', $call_id)->exists()) {
                $callInfo = Powerinai::where('call_id', $call_id)->first();
                $need_update = true;
                $callInfo->name = "outbound";
            } else {
                $callInfo = new Powerinai();
                $callInfo->call_id = $call_id;
                $callInfo->phone = $phn;
                $callInfo->name = "inbound";
                $need_update = true;
            }

            $callInfo->response = json_encode($request->all());
            $callInfo->save();
        } else {
            $callInfo = new Powerinai();
            $callInfo->name = $call_id;
            $callInfo->phone = $phn;
            $callInfo->response = json_encode($request->all());
            $callInfo->save();
        }



        if ($need_update) {
            sleep(3);
            // updateCallDataPai($callInfo->id);
            updateCallDataPaiBn($callInfo->id);
        }

        return response()->json($callInfo, 200);
    }

//     public function systemUpdate(Request $request)
//     {

//         $id = isset($request->id) ? $request->id : 'outbound';
//         $language = isset($request->language) ? $request->language : 'en-US';
//         // API endpoint
//         $url = "https://powerinai.speaklar.com/api/api.php?id=$id";

//         // API authorization token
//         $authToken = '4f239e8837559bdd543a9c';

//         $webhook_prompt = [
//             // Capture the client's name (not the AI agent's name)
//             "name" => 'What is the client’s name? (Client’s info only)',
//             // Capture the business or company name
//             "business_name" => 'What is the name of the client’s business?',
//             // Identify the client’s primary goal or challenge
//             "main_goal_or_challenge" => 'What is the biggest goal or challenge the client wants to address with PowerinAI?',
//             // Determine which processes or areas the client wants to automate
//             "automation_focus" => 'Which processes does the client want to automate or streamline?',
//             // Understand the client's current communication methods
//             "current_communication_methods" => 'How is the client currently managing customer communication?',
//             // Gather the client’s preference for a demo date/time
//             "demo_scheduling_preference" => 'When is the client available/prefer to schedule the demo?',
//             // Check if the client is interested in proceeding or scheduling
//             'is_interested' => 'Is the client interested in this solution or scheduling a demo? (yes/no/maybe) (Client’s info only)',
//             // General summary of the call, focusing on the client’s needs and context
//             "call_summary" => 'Please summarize the conversation regarding the client’s specific needs, challenges, and desired outcomes.'
//         ];

//         // Data to send in the POST request
//         $data = [
//             "webhook" => "http://68.183.189.27/powerinai/callback-call",
//             "webhook_prompt" => json_encode($webhook_prompt),
//             "text_to_speech_language" => "$language",
//             "text_to_speech_gender" => "FEMALE",
//             "text_to_speech_name" => "bn-IN-Wavenet-B",
//             "welcome_message" => "Hello this is Your dedicated AI Assistant Aiva from Power in AI",
//             "pause_message" => "Is there anything else i can help you with?",
//             "pause" => 20,
//             "system_prompt" => "Introduction
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
//  Power in AI is a platform delivering AI-driven solutions to streamline business processes, automate sales, and accelerate growth.
// What does “Hire AI Employee” mean?
//  It refers to our AI-powered virtual assistant that automates tasks like inbound/outbound calls, lead management, and appointment scheduling.
// What industries benefit from Power in AI?
//  Retail, real estate, healthcare, hospitality, and finance.
// What results can Power in AI deliver?
//  Improved lead conversion, reduced costs, faster customer response times, and streamlined workflows.
// What services does the AI Employee provide?
// Customer Interaction Management: Handles calls and engages across SMS, WhatsApp, and social media.
// Lead Management: Automates follow-ups and messaging.
// Appointment Scheduling: Fully automates bookings and reminders.
// How does Power in AI integrate with existing systems?
//  It works seamlessly with popular CRMs like Salesforce, Zoho, and HubSpot, ensuring real-time data updates.
// Why choose Power in AI?
//  Our tailored AI solutions are designed for measurable ROI, reduced operational costs, and enhanced customer engagement.

// Knowledge Base 2: Frequently Asked Questions
// Can the AI Employee handle large call volumes?
//  Yes, it scales effortlessly to manage thousands of interactions simultaneously.
// Is the AI Employee customizable?
//  Yes, it adapts to your business needs with tailored workflows and messaging.
// How does appointment scheduling work?
//  It integrates with Google Calendar, Zoom, and CRMs, automating bookings and reminders.
// Does Power in AI offer post-deployment support?
//  Absolutely. We provide ongoing assistance and optimization.
// How quickly can I see improvements?
//  Businesses typically notice faster response times and better engagement shortly after deployment.
// Can the AI Employee engage customers on social media?
//  Yes, it manages interactions on platforms like Facebook, Instagram, and Google My Business.
// Does this solution reduce costs?
//  Yes, many clients report a significant decrease in manual workload and operational expenses.
// How do I get started?
//  Contact us via phone, email, or website, and we’ll guide you through the process.
// CONTACT
//  +880 1670309328 +880 1711925048
//  Boshoti Legacy, House 29, Road 6, Dhanmondi, Dhaka 1205, Bangladesh
//  +971 529 32 4987 +18777941702
//  25th Floor, The Citadel Tower, Business Bay, Dubai
//  info@powerinai.com
//  www.powerinai.com"

//         ];

//         // Initialize cURL
//         $ch = curl_init();

//         // Set cURL options
//         curl_setopt($ch, CURLOPT_URL, $url);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, [
//             'Authorization: Bearer ' . $authToken,
//             'Content-Type: application/json',
//         ]);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

//         // Execute the cURL request
//         $response = curl_exec($ch);

//         // Check for errors
//         if (curl_errno($ch)) {
//             echo 'Error: ' . curl_error($ch);
//         } else {
//             // Print the response
//             echo  $response;
//         }

//         // Close the cURL session
//         curl_close($ch);
//     }
}
