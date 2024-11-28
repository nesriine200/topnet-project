<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Support\Facades\Http;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('{message}', function (BotMan $botman, $message) {
            // Convert the message to lowercase for case-insensitive comparison
            $message = strtolower($message);

            // Predefined responses for common user queries
            $predefinedResponses = [
                'Rouza' => 'Je t\'aime ma Nousa',
                'access offers' => 'To access our offers, visit the "Offers" section in our app or check our website: https://www.topnet.tn/offers.',
                'contact support' => 'You can contact our support team via phone at 1234-5678 or by email at support@topnet.tn.',
                'access contracts' => 'You can view your contracts in the "Contracts" section of the app or by logging into your Topnet account online.',
                'chat with someone' => 'To chat with an agent, go to the "Support" section in our app and select "Live Chat."',
                'phone number' => 'Our customer support phone number is 1234-5678.',
                'contact' => 'You can reach us at support@topnet.tn or call us at 1234-5678.',
                'pay my bills' => 'You can pay your bills through our app, via our website, or at authorized payment centers.',
                'internet plans' => 'We offer a variety of internet plans. Visit our website at https://www.topnet.tn/internet-plans for details.',
                'reset my password' => 'To reset your password, go to the "Forgot Password" section on our login page and follow the instructions.',
                'office hours' => 'Our offices are open from 8 AM to 6 PM, Monday to Friday.',
                'offices located' => 'Our offices are located at 123 Main Street, City Center, Tunis.',
                'the coverage area' => 'We provide coverage across Tunisia. Please contact us to confirm availability in your area.',
                'cancel my subscription' => 'To cancel your subscription, please contact our support team at support@topnet.tn or call 1234-5678.',
                'upgrade my plan' => 'To upgrade your plan, visit the "Plans" section in the app or contact our support team.',
                'report an issue' => 'You can report an issue through the "Support" section in our app or by contacting us at support@topnet.tn.',
                'check my data usage' => 'You can check your data usage in the "Account" section of the app.',
                'payment methods' => 'We accept payments via credit card, bank transfer, and cash at authorized centers.',
                'is there a mobile app' => 'Yes, download our Topnet app from the App Store or Google Play.',
                'install the app' => 'Search for "Topnet" in the App Store or Google Play, download, and install it.',
                'configure my router' => 'Refer to the setup guide provided with your router or contact our support for assistance.',
                'the speed of my internet' => 'You can check your internet speed using the "Speed Test" feature in our app.',
                'current promotions' => 'Check our current promotions on our website: https://www.topnet.tn/promotions.',
                'update my contact information' => 'Go to the "Account Settings" section in our app or website to update your contact details.',
                'request a technician' => 'Request a technician via the "Support" section in the app or by calling our support team.',
                'the installation fee' => 'The installation fee varies depending on your plan. Contact support for details.',
                'terminate my contract' => 'To terminate your contract, please submit a request through the app or contact our support team.',
                'the minimum contract period' => 'The minimum contract period is typically 12 months. Refer to your contract for details.',
                'can I transfer my account' => 'Yes, you can transfer your account to another user. Contact support for assistance.',
                'enable parental controls' => 'You can enable parental controls in your router settings. Refer to the guide provided with your router.',
                'troubleshoot connection issues' => 'Restart your router and check the cables. If the issue persists, contact support.',
                'test my internet speed' => 'Use the "Speed Test" feature in our app or visit https://www.speedtest.net.',
                'reset my router' => 'Press the reset button on your router for 10 seconds. Contact support for help if needed.',
                'the customer support email' => 'Our customer support email is support@topnet.tn.',
                'check my invoice' => 'Check your invoice in the "Billing" section of our app or website.',
                'business internet solutions' => 'We offer tailored business internet solutions. Contact our sales team at sales@topnet.tn.',
                'the bandwidth limit' => 'Your bandwidth limit depends on your plan. Refer to your contract or app for details.',
                'apply for a new connection' => 'Apply for a new connection via our website or app under "New Connection."',
                'configure email' => 'Refer to our email configuration guide on the website or contact support for assistance.',
                'the router username and password' => 'The default username and password are on the router label. Contact support if you’ve changed them and need help.',
                'reconnect my service' => 'If your service is disconnected, pay your outstanding balance to reconnect automatically.',
                'the late payment penalty' => 'The late payment penalty is 5% of your bill amount. Refer to your contract for details.',
                'check service availability' => 'Check service availability in your area through our website or contact our sales team.',
                'enable auto-payment' => 'Enable auto-payment in the "Billing" section of our app or website.',
                'extend my plan validity' => 'Contact support to extend your plan validity.',
                'the refund policy' => 'Refer to our refund policy at https://www.topnet.tn/refund-policy.',
                'pause my service' => 'You can pause your service temporarily by contacting our support team.',
                'switch to fiber' => 'To switch to fiber, check availability in your area and contact our sales team.',
                'retrieve my account number' => 'Find your account number in the "Account Details" section of the app or on your invoice.',
            ];

            // Check for predefined responses
            if (array_key_exists($message, $predefinedResponses)) {
                $response = $predefinedResponses[$message];
                $botman->reply($response);
            } elseif ($message === 'hi') {
                $this->askName($botman); // Handle "hi" with a custom flow
            } else {
                // Default to Hugging Face for dynamic responses
                $response = $this->askHuggingFace($message);
                $botman->reply($response);
            }
        });

        $botman->listen();
    }

    private function askHuggingFace($message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer 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',
        ])->post('https://api-inference.huggingface.co/models/facebook/blenderbot-400M-distill', [
            'inputs' => "Please be professional, and answer professionally to this: " . $message,
        ]);

        if ($response->ok() && isset($response->json()[0]['generated_text'])) {
            return $response->json()[0]['generated_text'];
        }

        return "Sorry, I couldn't process your request.";
    }
}
