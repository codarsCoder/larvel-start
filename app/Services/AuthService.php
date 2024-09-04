<?php

namespace App\Services;

use App\Models\User;
use App\Models\SmsConfirmation;
use App\Models\SessionLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $smsService;

    public function __construct(Sms $smsService)
    {
        $this->smsService = $smsService;
    }

    public function registerUser(array $data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    public function sendSmsConfirmation($phone, $action, $userId = null)
    {
        $generateCode = rand(100000, 999999);
        $expired_at = now()->addSeconds(185);

        $smsConfirmation = new SmsConfirmation();
        $smsConfirmation->user_id = $userId;
        $smsConfirmation->action = $action;
        $smsConfirmation->expire_at = $expired_at;
        $smsConfirmation->phone = clearPhone($phone);
        $smsConfirmation->code = $generateCode;
        $smsConfirmation->save();

        // $this->smsService->send(clearPhone($phone), "Doğrulama kodunuz: " . $generateCode);

        return [
            'status' => '200',
            'message' => 'Code sent',
            'expire_at' => $expired_at->format('Y-m-d H:i:s')
        ];
    }

    public function verifyCode($phone, $code)
    {
        return SmsConfirmation::where('phone', $phone)
            ->where('code', $code)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function createAccessToken(User $user)
    {
        return $user->createToken($user->phone)->accessToken;
    }

    public function logSession(Request $request, User $user)
    {
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        SessionLog::create([
            'user_id' => $user->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
