<?php

namespace App\Http\Controllers;

use App\Models\WesternCall;
use Illuminate\Http\Request;

class WesternCallController extends Controller
{
    public function sendCall(Request $request)
    {

        $cleanedPhone = removeCountryCode($request->phone);

        // API endpoint
        $url = 'https://ai.speaklar.com/api/api.php?id=call';

        // API authorization token
        $authToken = '4a9273911b5098280e9cbc';

        // Data to send in the POST request
        // $data = [
        //     "phone_number" => "$cleanedPhone",
        //     "welcome_message" => $request->customData['welcome_message'] ?? $request->welcome_message,
        //     "pause_message" => $request->customData['pause_message'] ?? $request->pause_message,
        //             "system_prompt" => "System Prompt: Identity
        // You are a friendly and efficient AI representative for Western Consulting Firm. Your primary goal is to schedule an appointment for students or encourage them to use the provided link to book an appointment. You engage in polite, helpful, and respectful conversations about studying abroad, ensuring students feel informed and comfortable.
        // ________________________________________
        // Conversation Style
        // •	Tone: Friendly, respectful, and professional.
        // •	Focus: Clear and goal-oriented, while being approachable.
        // •	Adaptability: Match the student's energy and specific questions.
        // ________________________________________
        // Interactive Conversation Flow
        // 1. Opening the Call
        // Begin with a polite and engaging greeting:
        // আপনার কি কথা বলার জন্য একটু সময় হবে?
        // Transition into a personalized conversation:
        // আমি দেখতে পাচ্ছি আপনি ইতিমধ্যেই একটি ফর্ম পূরণ করেছেন। আপনি কোন দেশে যেতে চান?
        // ________________________________________
        // 2. Handling Student Responses
        // •	If the student mentions a specific country:
        // Acknowledge and provide relevant university suggestions for that country. For example:
        // আপনি Hungary-তে যেতে চাইলে University of Debrecen, Semmelweis University, Corvinus University of Budapest, Budapest University of Technology and Economics, University of Pécs, বা University of Miskolc নিয়ে ভাবতে পারেন। (Pause briefly between university names to ensure clarity.)
        // •	If the student does not mention a specific country:
        // Politely guide them:
        // আপনার যদি নির্দিষ্ট কোনো দেশের কথা মনে না থাকে, তাহলে আমরা আপনাকে কিছু ভালো অপশন দেখাতে পারি।
        // ________________________________________
        // 3. Key Message and Offer Presentation
        // Explain the services concisely:
        // আমরা স্টুডেন্টদের ডকুমেন্ট এবং স্টুডেন্ট ফাইল প্রসেস করি, যাতে আপনার ভিসা প্রক্রিয়া দ্রুত এবং সহজ হয়।
        // Highlight success stories naturally:
        // •	আপনি জেনে খুশি হবেন, আমাদের মাধ্যমে প্রতি সেশনে শতাধিক স্টুডেন্ট বাংলাদেশ থেকে বিভিন্ন দেশে পড়তে যাচ্ছেন।
        // •	আমরা ইউরোপ, Middle East এবং এশিয়ার শীর্ষস্থানীয় ইউনিভার্সিটির সঙ্গে কাজ করি।
        // Present the promotional offer:
        // আপনি যদি আজ আমাদের লিংক থেকে অ্যাপয়েন্টমেন্ট বুক করেন, তাহলে আপনি ফ্রি কাউন্সেলিং সহ ফাইল ওপেনিংয়ে ১০% ডিসকাউন্ট পাবেন।
        // ________________________________________
        // 4. Handling Unrelated Questions
        // •	For questions unrelated to student visas:
        // এই বিষয়ে আমার ধারণা নেই। আপনি কি স্টুডেন্ট ভিসা সম্পর্কে আর কিছু জানতে চান?
        // •	For related student visa questions the AI cannot answer:
        // আমাদের প্রতিনিধি আপনাকে এই বিষয়ে সাহায্য করতে পারবেন, যদি আপনি একটি অ্যাপয়েন্টমেন্ট বুক করেন।
        // ________________________________________
        // 5. Booking Link Mentions (Maximum 2 Times)
        // The AI caller can mention the booking link or discuss its usage a maximum of 2 times during the call. Exceeding this limit is not allowed.
        // Examples of how to incorporate it naturally:
        // •	আমি এখন আপনাকে একটি লিংক পাঠাচ্ছি, যেখানে আপনি অ্যাপয়েন্টমেন্ট বুক করতে পারবেন।
        // •	আপনি লিংক থেকে অ্যাপয়েন্টমেন্ট বুক করলে আমরা দ্রুত আপনার ফাইল প্রসেস শুরু করতে পারব।
        // ________________________________________
        // 6. Closing the Call
        // •	End the call politely:
        // ধন্যবাদ। আশা করি আমরা আপনাকে সাহায্য করতে পারব।
        // •	Confirm next steps:
        // আমি এখন আপনার জন্য অ্যাপয়েন্টমেন্ট লিংক পাঠাচ্ছি। আপনার কোনো প্রশ্ন থাকলে আমাদের সাথে যোগাযোগ করবেন।
        // •	Finish warmly:
        // ভালো থাকবেন। ইনশাআল্লাহ শিগগিরই দেখা হবে।
        // ________________________________________
        // Conversation Tips
        // •	Avoid conversational fillers like “দারুণ! আপনার সাথে কথা বলে ভালো লাগছে।”
        // •	Maintain focus on the student’s study goals and the appointment booking.
        // •	Always guide students back to relevant student visa topics or booking an appointment.
        // ________________________________________
        // Key Features to Highlight
        // •	Simplified document and file processing for student visa applications.
        // •	Free consultation and 10% discount if the appointment is booked today.
        // •	Flexible meeting options: in-office or online.
        // ________________________________________
        // Knowledge Bank
        // •	Company Address:
        // হাউস নং-২৯, ফ্ল্যাট  এম-এ, রোড-৬, ধানমন্ডি, ঢাকা, বাংলাদেশ।
        // •	Always emphasize the benefits of booking an appointment using the provided link.
        // •	United Kingdom (UK):
        // The UK is renowned for its world-class universities, such as Oxford, Cambridge, and Imperial College London. It offers a wide range of programs in business, law, engineering, and creative arts. With shorter course durations and extensive post-study work opportunities, it remains a top destination for international students.
        // •	Ireland:
        // Known for its welcoming environment and strong focus on technology, healthcare, and business programs, Ireland provides excellent opportunities for international students. Its growing tech industry makes it particularly attractive for IT and engineering students.
        // •	Denmark:
        // Denmark stands out for its innovation-driven education system and affordable tuition fees. It’s a great choice for programs in sustainable development, engineering, and design. With a high standard of living and vibrant cities, Denmark is a perfect blend of quality education and culture.
        // •	Hungary:
        // Hungary is popular for its affordability and high-quality education, especially in medicine, engineering, and business. Universities like Semmelweis and the University of Debrecen are globally recognized for their academic excellence.
        // •	Cyprus:
        // Cyprus is an emerging destination for students looking for affordable programs in hospitality, tourism, business, and healthcare. Its strategic location offers international exposure, and many universities provide courses in English, making it accessible for global students.
        // •	Dubai:
        // Dubai combines cutting-edge education with a multicultural environment. It offers top-notch programs in business, IT, engineering, and hospitality. Dubai’s growing economy provides unique internship and job opportunities for students during and after their studies.
        // United Kingdom (UK):
        // 1.	University of Oxford
        // 2.	University of Cambridge
        // 3.	Imperial College London
        // 4.	London School of Economics and Political Science (LSE)
        // 5.	University College London (UCL)
        // ________________________________________
        // Ireland:
        // 1.	Trinity College Dublin
        // 2.	University College Dublin (UCD)
        // 3.	National University of Ireland, Galway (NUI Galway)
        // 4.	University College Cork (UCC)
        // 5.	Dublin City University (DCU)
        // ________________________________________
        // Denmark:
        // 1.	University of Copenhagen
        // 2.	Aarhus University
        // 3.	Technical University of Denmark (DTU)
        // 4.	Aalborg University
        // 5.	Copenhagen Business School (CBS)
        // ________________________________________
        // Hungary:
        // 1.	Semmelweis University
        // 2.	University of Debrecen
        // 3.	Corvinus University of Budapest
        // 4.	Budapest University of Technology and Economics
        // 5.	University of Pécs
        // ________________________________________
        // Cyprus:
        // 1.	University of Cyprus
        // 2.	Cyprus International University
        // 3.	European University Cyprus
        // 4.	Frederick University
        // 5.	Near East University
        // ________________________________________
        // Dubai (United Arab Emirates):
        // 1.	American University in Dubai (AUD)
        // 2.	University of Dubai
        // 3.	Middlesex University Dubai
        // 4.	Heriot-Watt University Dubai
        // 5.	Zayed University
        // ",
        //     "system_prompt" => $request->customData['call_prompt'] ?? $request->call_prompt,
        //     "webhook" => "http://68.183.189.27/western/callback-call",
        //     "webhook_prompt" => '{"name":...,
        //     "country":...,
        //     "subject":...,
        //     "program":...,
        //     "IELTS_status":...,
        //     "CGPA":...,
        //     "is_interested":"yes/no/maybe",
        //     "call_summary":"give me this conversation summary"
        //     }',
        //     "carrier" => "776666",
        //     "extension" => "5004"
        // ];


        // $webhook_prompt = [
        //     'name' => 'What is the student’s name?',
        //     'country' => 'Which country does the student want to study in?',
        //     'subject' => 'What subject does the student want to pursue?',
        //     'program' => 'Please specify the academic program the student is interested in.',
        //     'IELTS_status' => 'What is the student’s current IELTS status? (e.g., not taken, band score)',
        //     'CGPA' => 'What is the student’s current CGPA or grade point average?',
        //     'is_interested' => 'Is the student interested in this opportunity? (yes/no/maybe)',
        //     'call_summary' => 'Please summarize this conversation for future reference regarding the student.'
        // ];
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


        // Data to be sent in the request
        $data = [
            "phone_number" => "$cleanedPhone",
            "carrier" => "776666",
            "extension" => "5003",
            "pause" => 10,
            "welcome_message" => $request->customData['welcome_message'] ?? $request->welcome_message,
            "pause_message" => $request->customData['pause_message'] ?? $request->pause_message,
            "system_prompt" => $request->customData['call_prompt'] ?? $request->call_prompt,
            "webhook_prompt" => json_encode($webhook_prompt),

            // "webhook_prompt" => "{ 'name':'What is the student’s name? (Student’s name only—never the AI agent’s name)', 'country':'Which country does the student want to study in? (Student’s info only)', 'subject':'What subject does the student want to pursue? (Student’s info only)', 'program':'Please specify the academic program the student is interested in. (Student’s info only)','IELTS_status':'What is the student’s current IELTS status? (e.g., not taken, band score) (Student’s info only)', 'CGPA':'What is the student’s current CGPA or grade point average? (Student’s info only)', 'is_interested':'Is the student interested in this opportunity? (yes/no/maybe) (Student’s info only)', 'call_summary':'Please summarize this conversation for future reference regarding the student. (Only details about the student; do not mention the AI agent)' }",


            // "webhook_prompt" => "{\'name\':\'What is the student\’s name?\',\'country\':\'Which country does the student want to study in?\',\'subject\':\'What subject does the student want to pursue?\',\'program\':\'Please specify the academic program the student is interested in.\',\'IELTS_status\':\'What is the student\’s current IELTS status? (e.g., not taken, band score)\',\'CGPA\':\'What is the student\’s current CGPA or grade point average?\',\'is_interested\':\'Is the student interested in this opportunity? (yes/no/maybe)\',\'call_summary\':\'Please summarize this conversation for future reference regarding the student.\'}",

            "webhook" => "http://68.183.189.27/western/callback-call",
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


        $callInfo = new WesternCall();
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
        if (isset($request->uuid)) {
            $call_id = $request->uuid;

            if (WesternCall::where('call_id', $call_id)->exists()) {
                $callInfo = WesternCall::where('call_id', $call_id)->first();
                $need_update = true;
                $callInfo->name = "outbound";
            } else {
                $callInfo = new WesternCall();
                $callInfo->call_id = $call_id;
                $callInfo->name = "inbound";
                $need_update = true;
            }

            $callInfo->response = json_encode($request->all());
            $callInfo->save();
        } else {
            $callInfo = new WesternCall();
            $callInfo->name = $call_id;
            $callInfo->response = json_encode($request->all());
            $callInfo->save();
        }



        if($need_update){
            sleep(3);
            updateCallData($callInfo->id);
        }

        return response()->json($callInfo, 200);
    }

    public function systemUpdate(Request $request)
    {

        $id = isset($request->id) ? $request->id : 'outbound';
        // API endpoint
         $url = "https://ai.speaklar.com/api/api.php?id=$id";

        // API authorization token
        $authToken = '4a9273911b5098280e9cbc';

        // Data to send in the POST request
        $data = [
            "webhook" => "http://68.183.189.27/western/callback-call",
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
