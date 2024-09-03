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
            $user->password = Hash::make($request->password);
            $user->save();

            $generateCode = rand(100000, 999999);
            $smsConfirmation = new SmsConfirmation();
            $smsConfirmation->action = "REGISTER-VERIFY";
            $smsConfirmation->expire_at = now()->addMinutes(3);
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
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        try {

            $email = $request->email;
            $password = $request->password;

            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $user = Auth::User();
                $accessToken = $user->createToken($user->email)->accessToken;

                $data = [];
                $data['response_code'] = '200';
                $data['status'] = 'success';
                $data['message'] = 'success Login';
                $data['user_infor'] = $user;
                $data['token'] = $accessToken;


                $sessionId = session()->getId();
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
                $data['response_code'] = '401';
                $data['status'] = 'error';
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
