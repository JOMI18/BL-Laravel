<?php


namespace App\Traits;

use App\Jobs\SendEmail;
use App\Mail\EmailAlert;
use App\Models\Otps;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Propaganistas\laravelPhone\PhoneNumber;

trait Utilities
{
    public $rand;

    public function sendOtp($userId)
    {

        $this->rand = mt_rand(1000, 9999);
        $user = User::find($userId);

        if (!$user->id) {
            return false;
        }
        $r = new EmailAlert([
            'name' => $user->first_name, 'subject' => 'Email Verification',
            'view' => 'alert', 'message' => 'The OTP to verify your email address on ' . env('APP_NAME') . ' is <b>' . $this->rand . '</b>'
        ]);
        Otps::updateOrCreate(['user_id' => $user->id], [
            'code' => $this->rand,
        ]);
        dispatch(new SendEmail($r, [$user->email]));
        return  response()->json(['status' => 'ok', 'message' => 'OTP has been sent successfully.']);
    }


    public function sendEmails($data, array $emails)
    {

        dispatch(new SendEmail($data, $emails));
    }


    public function sendSms($phone, $message)
    {

        $response = Http::withHeaders([
            "Content-Type" => "application/json"
        ])->post(
            "https://api.ng.teermii.com/api/sms/send",
            [
                "api_key" => config("app.termi_api_key"),
                "to" => $phone,
                "from" => "N-Alert",
                "sms" => $message,
                "type" => "plain",
                "channel" => "dnd",
            ]
        );

        $responseData =  $response->json();
        return true;
    }

    public function validatePhone($phone)
    {
        $validated = Validator::make($phone, ["phone" => "phone:NG"]);
        if ($validated->fails()) {
            return false;
        }
        return true;
    }

    public function formatPhoneWithZip($phone)
    {
        $phone = new PhoneNumber($phone, "NG");
        return $phone->formatE164();
    }

    public function formatPhoneWithoutZip($phone)
    {
        $phone = new PhoneNumber($phone, "NG");
        $formatted = $phone->formatNational();
        return Str::replace(" ", "", $formatted);
    }
}
