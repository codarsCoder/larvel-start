<?php

namespace App\Http\Controllers\API;

use App\Services\ExamService;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    protected $examService;
    protected $purchaseService;

    public function __construct(ExamService $examService, PurchaseService $purchaseService)
    {
        $this->examService = $examService;
        $this->purchaseService = $purchaseService;
    }

    public function createPaymentIntent(Request $request)
    {
        $user = Auth::user();

        // Diğer form verilerini al
        $transactionId = $request->input('transaction_id');
        $examIds = json_decode($request->input('exams'), true);
        $exams = $this->examService->getExamByExamIds($examIds);
        // const resp = await axios.post(
        //     url, {
        //     transaction_id: clientSecretId.split('_secret_')[0],
        //     exams: JSON.stringify(ids),
        //     paymentMethod:'paytr'
        //   }

        $paymentMethod = $request->input('paymentMethod');
        $totalAmount = 0;
        $cartIds = [];

        foreach ($exams as $key => $exam) {
            $totalAmount += $exam->amount;

        }

        $createPaymentId = $this->purchaseService->createPayment([
            'user_id' => $user->id,
            'transaction_id' => $transactionId,
            'payment_method' => mb_strtoupper($paymentMethod,"UTF-8"),
            'payment_type' => 'cart',
            'current_amount' => $totalAmount,
            'current_currency' => "TRY",
            'amount' => 0,
            'currency' => "TRY",
            'status' => 'pending',
        ]);
        foreach ($exams as $key => $exam) {

            $exam = $this->examService->findById($exam->id);
            $this->purchaseService->createCart([
                'user_id' => $user->id,
                'exam_id' => $exam->id,
                'purchase_id' => $createPaymentId,
                'amount' => $exam->amount,
                'currency' => $exam->currency,
                'payment_method' => $paymentMethod,
            ]);
        }


        // Toplam tutarı güncelle
        // $payment = PaymentsModel::create([
        //     'user_id' => $userId,
        //     'email' => "testasdas",
        //     'cart_id' => implode(',', $cartIds),
        //     'currency' => $currency,
        //     'amount' => $totalAmount, // Güncellendi
        //     'payment_method' => $paymentMethod,
        //     'transaction_id' => $transaction_id,
        //     'status' => 'pending',
        //     'cart_total' => $totalAmount,
        // ]);
        return response()->json(['data' => $totalAmount]);
    }
}
