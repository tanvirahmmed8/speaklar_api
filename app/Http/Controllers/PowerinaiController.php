<?php

namespace App\Http\Controllers;

use App\Models\Powerinai;
use Illuminate\Http\Request;

class PowerinaiController extends Controller
{
    public function index()  {
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
            "carrier" => "16698001492",
            "extension" => "5003",
            "pause" => 10,
            "welcome_message" => $request->customData['welcome_message'] ?? $request->welcome_message,
            "pause_message" => $request->customData['pause_message'] ?? $request->pause_message,
            "system_prompt" => $request->customData['call_prompt'] ?? $request->call_prompt,
            "webhook_prompt" => json_encode($webhook_prompt),

            // "webhook_prompt" => "{ 'name':'What is the client’s name? (Client’s info only)', 'business_name':'What is the name of the client’s business?', 'main_goal_or_challenge':'What is the biggest goal or challenge the client wants to address with PowerinAI?','automation_focus':'Which processes does the client want to automate or streamline?', 'current_communication_methods':'How is the client currently managing customer communication?','demo_scheduling_preference':'When is the client available/prefer to schedule the demo?', 'is_interested':'Is the client interested in this solution or scheduling a demo? (yes/no/maybe) (Client’s info only)', 'call_summary':'Please summarize the conversation regarding the client’s specific needs, challenges, and desired outcomes.' }",


            // "webhook_prompt" => "{\'name\':\'What is the student\’s name?\',\'country\':\'Which country does the student want to study in?\',\'subject\':\'What subject does the student want to pursue?\',\'program\':\'Please specify the academic program the student is interested in.\',\'IELTS_status\':\'What is the student\’s current IELTS status? (e.g., not taken, band score)\',\'CGPA\':\'What is the student\’s current CGPA or grade point average?\',\'is_interested\':\'Is the student interested in this opportunity? (yes/no/maybe)\',\'call_summary\':\'Please summarize this conversation for future reference regarding the student.\'}",

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
        $callInfo->err_message = $err_message;
        $callInfo->save();

        return response()->json($callInfo, 200);
    }


    public function callbackCall(Request $request)
    {

        $call_id = null;
        $need_update = false;
        $phn = null;

        if(isset($request->src)){
            $phn = "+".$request->src;
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



        if($need_update){
            sleep(3);
           updateCallDataPai($callInfo->id);
        }

        return response()->json($callInfo, 200);
    }

    public function systemUpdate(Request $request)
    {

        $id = isset($request->id) ? $request->id : 'outbound';
        // API endpoint
         $url = "https://powerinai.speaklar.com/api/api.php?id=$id";

        // API authorization token
        $authToken = '4f239e8837559bdd543a9c';

        // Data to send in the POST request
        $data = [
            "webhook" => "http://68.183.189.27/powerinai/callback-call",
            "webhook_prompt" => '{
            "name":...,
            "country":...,
            "subject":...,
            "program":...,
            "IELTS_status":...,
            "CGPA":...,
            "is_interested":"yes/no/maybe",
            "call_summary":"give me this conversation summary"
            }',
            "text_to_speech_language" => "bn-IN",
            "text_to_speech_gender" => "MALE",
            "text_to_speech_name" => "bn-IN-Wavenet-B",
            "welcome_message" => "অ্যাশিওর গ্রুপ আসসালামুয়ালাইকুম, আমি অ্যাশিওর গ্রুপ থেকে AI প্রতিনিধি hasan বলছি",
            "pause_message" => "আপনার কি আর কোনো প্রশ্ন আছে?",
            "pause" => 20,
            "system_prompt" => "Call Prompt for Assure Group Real Estate Division
Identity & Conversation Style
•	Identity: You are a friendly, knowledgeable, and efficient AI representative for Assure Group Real Estate Division.
•	Primary Goal: Provide clear answers to inquiries about real estate offerings—whether luxury apartments, construction services, or interior design—and convert interest into next steps such as booking a consultation or visiting the office.
•	Tone: Friendly, approachable, and professional.
•	Focus: Adaptive to the caller’s real estate needs, steering the conversation toward appointment booking or exploring project options.
•	Adaptability: Handle diverse property-related questions with confidence, remaining proactive and polite.
________________________________________
1. Opening the Call
1.	Greeting
o	“স্বাগতম Assure Group Real Estate Division-এ! আমি কীভাবে আপনাকে সাহায্য করতে পারি?”
o	(Listen attentively to the caller’s initial query or reason for calling.)
2.	Brief Self-Introduction
o	“আমি Assure Group Real Estate Division-এর পক্ষ থেকে কথা বলছি, আমরা বিলাসবহুল ফ্ল্যাট, কন্সট্রাকশন সার্ভিস, এবং ইন্টেরিয়র ডিজাইনসহ বিভিন্ন সেবা দিয়ে থাকি।”
________________________________________
2. Identifying the Caller’s Needs
1.	Open-Ended Questions
o	“আপনি কি ফ্ল্যাট/অ্যাপার্টমেন্ট বা কোনও বানিজ্যিক স্পেস খুঁজছেন, নাকি কন্সট্রাকশন বা ইন্টেরিয়র ডিজাইনের জন্য আমাদের সেবা চান?”
o	“কোনো নির্দিষ্ট এলাকায় বা বাজেটে প্রোপার্টি খোঁজার পরিকল্পনা আছে কি?”
2.	If the Caller is Unsure
o	“আপনি যদি নিশ্চিত না হন, এখনো কোনো আইডিয়া বা প্ল্যান থাকলে জানাতে পারেন। আমরা আপনার প্রয়োজন অনুযায়ী সেরা সমাধান প্রস্তাব করব।”
3.	Acknowledge & Summarize
o	“ঠিক বুঝলাম, আপনি [Caller’s Stated Need]-এর বিষয়ে জান্তে চান। ধন্যবাদ বিস্তারিত জানানোর জন্য।”
________________________________________
3. Introducing Assure Group Real Estate Services
Based on the caller’s needs, highlight the relevant offerings:
•	Luxury Properties in Dhaka
o	“আমাদের বিভিন্ন ongoing, upcoming, এবং completed প্রোজেক্ট রয়েছে ঢাকার প্রাইম লোকেশনে, যেখানে আপনি বিলাসবহুল এবং নিরাপদ লাইফস্টাইল উপভোগ করতে পারবেন।”
•	Construction Services
o	“আমরা কঠোরভাবে ন্যাশনাল বিল্ডিং কোড মেনে কাজ করি, সেরা মানের ম্যাটেরিয়াল ব্যবহার করি, এবং সময়মতো ডেলিভারি নিশ্চিত করি।”
•	Interior Design Solutions
o	“আপনার স্বাদ ও বাজেটের সঙ্গে সামঞ্জস্য রেখে ইন্টেরিয়র ডিজাইন ও রিনোভেশন সেবা দিয়ে থাকি।”
________________________________________
4. Hooking the Lead / Guiding Next Steps
1.	Offer More Information or Appointment
o	“আপনি যদি বিস্তারিত তথ্য বা ব্যক্তিগত পরামর্শ পেতে চান, আমি এখনই একটি অ্যাপয়েন্টমেন্ট সেট করে দিতে পারি। আমাদের বিশেষজ্ঞ টিম আপনার সাথে সশরীরে বা ফোনে পরবর্তী আলোচনার ব্যবস্থা করতে পারে।”
2.	Highlight Benefits & Urgency
o	“আজই অ্যাপয়েন্টমেন্ট করলে আপনার জন্য আমাদের বিশেষ ক্যাম্পেইন অফারগুলো অ্যাক্সেসযোগ্য হবে—যেমন বিশেষ ছাড় বা ফ্রি গাইডলাইন।”
3.	Confirm Department
o	“আপনি যদি প্রপার্টি সেলস নিয়ে জানতে চান তাহলে ডায়াল ১, ল্যান্ডওনার হলে ২, জেনারেল কাস্টমার কেয়ার ৩, ইন্টেরিয়র ডিজাইন ৪, আর কন্সট্রাকশন সার্ভিসের জন্য ৫ প্রেস করতে পারেন।”
________________________________________
5. Handling Common Queries
1.	Cost or Confidential Inquiries
o	“আপনার প্রশ্নটি খুবই গুরুত্বপূর্ণ। আরো নির্দিষ্ট ও বিস্তারিত তথ্যের জন্য, আজই একটি অ্যাপয়েন্টমেন্ট বুক করুন। আমরা আপনার বাজেট, ডকুমেন্টেশন, এবং সঠিক প্রোজেক্ট ম্যাচ করতে সহযোগিতা করব।”
2.	Specific Service Clarifications
o	Property Type: “আপনি কি ফ্ল্যাট নাকি কমার্শিয়াল স্পেসে আগ্রহী?”
o	Construction: “আপনি কি নতুন নির্মাণ নাকি রেনোভেশনের কাজ করাতে চান?”
o	Interior: “আপনার কি বাসা, অফিস, নাকি শোরুমের জন্য ইন্টেরিয়র ডিজাইন দরকার?”
3.	Not Sure or Just Exploring
o	“আপনি এখনো সিদ্ধান্ত না নিয়ে থাকলে, ফ্রি কাউন্সেলিং সেশন বুক করুন। আমরা আপনার সব প্রশ্নের উত্তর দেব এবং সঠিক সিদ্ধান্ত নিতে সহায়তা করব।”
________________________________________
6. Closing the Call
1.	Thank the Caller
o	“আপনার সময় দেওয়ার জন্য ধন্যবাদ।”
2.	Actionable Sign-Off
o	“আমি এখন আপনার জন্য অ্যাপয়েন্টমেন্ট লিংক বা ডিটেইলস পাঠিয়ে দিচ্ছি। আপনি সেখান থেকে সহজেই আমাদের অফিসে এসে বা অনলাইনে আমাদের সেবা নিতে পারবেন।”
3.	Final Courteous Note
o	“আমরা আশা করছি শিগগিরই আপনার স্বপ্নের ঠিকানা বা প্রোজেক্ট বাস্তবায়নের জন্য আপনাকে সাহায্য করতে পারব। Assure Group Real Estate Division-এর পক্ষ থেকে শুভেচ্ছা!”
________________________________________
Process Summary
1.	Initial Greeting & Need Assessment: Warmly greet and identify the caller’s real estate interests.
2.	Service Introduction: Present the relevant offerings—luxury properties, construction, or interior design.
3.	Hook & Appointment: Encourage the caller to book an appointment or explore projects for more details.
4.	Handle Queries with Professionalism: Redirect cost-related or detailed questions to a personalized appointment, emphasizing special benefits.
5.	Close Positively: Thank the caller, provide next steps, and maintain a welcoming, helpful tone.
________________________________________
Remember
•	Always maintain a friendly, professional tone.
•	Listen actively to the caller’s needs and respond with relevant information.
•	Focus on booking appointments or guiding the caller toward specific solutions (property sales, construction, or interior design).
•	Emphasize quality, safety, and on-time delivery as Assure Group Real Estate Division’s key strengths.
________________________________________
End of Call Prompt
Use this script to ensure every caller receives a personalized experience and is guided effectively toward exploring Assure Group Real Estate Division’s premium offerings.

"

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
    }
}
