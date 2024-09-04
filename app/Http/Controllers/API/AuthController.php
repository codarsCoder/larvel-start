<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /** register new account */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|min:4',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'phone' => 'required|numeric',
            ]);

            $user = $this->authService->registerUser($request->all());
            $smsResponse = $this->authService->sendSmsConfirmation($user->phone, "REGISTER-VERIFY");

            return response()->json($smsResponse);
        } catch (\Exception $e) {
            \Log::info($e);
            return response()->json([
                'response_code' => '400',
                'status' => 'error',
                'message' => 'Fail Register'
            ]);
        }
    }

    /**
     * Login Start
     */
    public function loginStart(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        try {
            $phone = $request->phone;
            $user = User::where('phone', $phone)->first();

            if (!$user) {
                $user = $this->authService->registerUser(['phone' => clearPhone($phone)]);
                $smsResponse = $this->authService->sendSmsConfirmation($phone, "REGISTER", $user->id);
            } else {
                $smsResponse = $this->authService->sendSmsConfirmation($phone, "LOGIN", $user->id);
            }

            return response()->json($smsResponse);
        } catch (\Exception $e) {
            \Log::info($e);
            return response()->json([
                'response_code' => '401',
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
            'code' => 'required|numeric',
        ]);

        try {
            $phone = $request->phone;
            $code = $request->code;
            $confirmation = $this->authService->verifyCode($phone, $code);

            if ($confirmation) {
                $user = User::where('id', $confirmation->user_id)->first();

                if ($confirmation->expire_at < now()) {
                    return response()->json([
                        'status' => 401,
                        'message' => 'Code Expired',
                    ]);
                }

                if ($confirmation->action == "REGISTER") {
                    $user->is_active = 1;
                    $user->save();
                }

                $accessToken = $this->authService->createAccessToken($user);
                $this->authService->logSession($request, $user);

                return response()->json([
                    'status' => 200,
                    'message' => 'success Login',
                    'token' => $accessToken,
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorised',
                ]);
            }
        } catch (\Exception $e) {
            \Log::info($e);
            return response()->json([
                'response_code' => '401',
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /** user info */
    public function testToken()
    {
        try {
            $user = Auth::user();
            return response()->json([
                'status' => 200,
                'message' => 'success',
                'phone' => $user->phone,
            ]);
        } catch (\Exception $e) {
            \Log::info($e);
            return response()->json([
                'status' => 400,
                'message' => 'failed',
            ]);
        }
    }
}
