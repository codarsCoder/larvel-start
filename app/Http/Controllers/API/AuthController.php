<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SessionLog;
use App\Models\SmsConfirmation;
use App\Services\Sms;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use Hash;

class AuthController extends Controller
{

    protected $smsService;

    public function __construct(Sms $smsService)
    {
        $this->smsService = $smsService;
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

            $dt = Carbon::now();
            $join_date = $dt->toDayDateTimeString();

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            $generateCode = rand(100000, 999999);
            $smsConfirmation = new SmsConfirmation();
            $smsConfirmation->action = "REGISTER-VERIFY";
            $smsConfirmation->expire_at = now()->addSeconds(185);
            $smsConfirmation->phone = clearPhone($request->phone);
            $smsConfirmation->code = $generateCode;
            $smsConfirmation->save();
            // $this->smsService->send(clearPhone($request->phone), "Üyeliğinizi tamamlamak için doğrulama kodunuz " . $generateCode);


            $data = [];
            $data['response_code'] = '200';
            $data['status'] = 'success';
            $data['message'] = 'success Register';
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $data = [];
            $data['response_code'] = '400';
            $data['status'] = 'error';
            $data['message'] = 'fail Register';
            return response()->json($data);
        }
    }

    /**
     * Login Req
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
                $newUser = new User();
                $newUser->phone = clearPhone($request->phone);
                $newUser->save();
                if ($newUser) {
                    $generateCode = 123456;
                               // $generateCode = rand(100000, 999999);
                    $expired_at = now()->addSeconds(185);
                    $smsConfirmation = new SmsConfirmation();
                    $smsConfirmation->user_id = $newUser->id;
                    $smsConfirmation->action = "REGISTER";
                    $smsConfirmation->expire_at = $expired_at;
                    $smsConfirmation->phone = $newUser->phone;
                    $smsConfirmation->code = $generateCode;
                    $smsConfirmation->save();
                        // $this->smsService->send(clearPhone($request->phone), "Üyeliğinizi tamamlamak için doğrulama kodunuz " . $generateCode);

                    return response()->json([
                        'status' => '200',
                        'message' => 'Register success, sended code',
                        'expire_at' => $expired_at
                    ]);
                } else {
                    $data = [];
                    $data['status'] = 401;
                    $data['message'] = 'Fail Register';
                    return response()->json($data);
                }
            } else {
                // $generateCode = rand(100000, 999999);
                $generateCode = 123456;
                $expired_at = now()->addSeconds(185);
                $smsConfirmation = new SmsConfirmation();
                $smsConfirmation->user_id = $user->id;
                $smsConfirmation->action = "LOGIN";
                $smsConfirmation->expire_at = $expired_at; ;
                $smsConfirmation->phone = clearPhone($user->phone);
                $smsConfirmation->code = $generateCode;
                $smsConfirmation->save();
                // $this->smsService->send(clearPhone($request->phone), "Üyeliğinizi tamamlamak için doğrulama kodunuz " . $generateCode);

                return response()->json([
                    'status' => '200',
                    'message' => 'sended code',
                    'expire_at' => $expired_at
                ]);
            }


        } catch (\Exception $e) {
            // \Log::info($e);
            $data = [];
            $data['response_code'] = '401';
            $data['status'] = 'error';
            $data['message'] = $e->getMessage();
            return response()->json($data);
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
            $confirmation = SmsConfirmation::where('phone', $phone)
            ->where('code', $code)
            ->orderBy('created_at', 'desc')
            ->first();
            // return response()->json($confirmation);
            if ($confirmation) {
                $user = User::where('id', $confirmation->user_id)->first();
                if ($confirmation->expire_at < now()) {
                    $data = [];
                    $data['status'] = 401;
                    $data['message'] = 'Code Expired';
                    return response()->json($data);
                }

                if ($confirmation->action == "REGISTER") {
                    $user->is_active = 1;
                    $user->save();
                }

                $accessToken = $user->createToken($user->phone)->accessToken;
                // Auth::login($user); web rotaları için girişte bu önemli oturumu başlatmak için kullanılır
                $data = [];
                $data['status'] = 200;
                $data['message'] = 'success Login';
                $data['token'] = $accessToken;

                $ipAddress = $request->ip();
                $userAgent = $request->userAgent();

                // Örneğin, bu bilgileri veritabanına kaydedebilirsiniz
                SessionLog::create([
                    'user_id' => $user->id,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]);
                return response()->json($data);
            } else {
                $data = [];
                $data['status'] = 401;
                $data['message'] = 'Unauthorised';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            \Log::info($e);
            $data = [];
            $data['response_code'] = '401';
            $data['status'] = 'error';
            $data['message'] = $e->getMessage();
            return response()->json($data);
        }
    }

    /** user info */
    public function userInfo()
    {
        try {
            $userDataList = User::latest()->paginate(10);
            $data = [];
            $data['response_code'] = '200';
            $data['status'] = 'success';
            $data['site'] = config('settings.site_name');
            ;
            $data['data_user_list'] = $userDataList;
            return response()->json($data);
        } catch (\Exception $e) {
            // \Log::info($e);
            $data = [];
            $data['response_code'] = '400';
            $data['status'] = 'error';
            $data['message'] = 'fail get user list';
            return response()->json($data);
        }
    }
}
