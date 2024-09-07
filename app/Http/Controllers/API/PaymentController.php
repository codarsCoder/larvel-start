<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function startPayment(Request $request)
    {
        $user = Auth::user();

        // Diğer form verilerini al
        $transactionId = $request->input('transaction_id');
        $examsIds = json_decode($request->input('exams'), true);

        // const resp = await axios.post(
        //     url, {
        //     transaction_id: clientSecretId.split('_secret_')[0],
        //     exams: JSON.stringify(ids),
        //     currency:currency,
        //     paymentMethod:'paytr'
        //   }

        $currency = $request->input('currency');
        $paymentMethod = $request->input('paymentMethod');
        $totalAmount = 0;
        $cartIds = [];

        foreach ($examsIds as $key => $examsId) {
           
        }




        foreach ($lessons as $lesson) {


            $lessonData = LessonsModel::where('id', $lesson['id'])->first();

            if ($lesson['education_type'] == 0) {
                $lessonCalendarsIds = [];
                $result = $this->exchange($lessonData->currency, $lessonData->one_amount, $currency, $rates);

                //lesson_count kadar döngüye sok
                for ($i = 0; $i < $lesson['lesson_count']; $i++) {
                    $lessonCalendar = LessonsCalendarModel::create([
                        'user_id' => $lessonData->user_id,
                        'lesson_id' => $lessonData->id,
                        'group_no' => 0,
                        'group_lesson_no' => 0,
                        'education_type' => $lesson['education_type'],
                        'is_trial' => 0,
                    ]);

                    $lessonCalendarsIds[] = $lessonCalendar->id;
                }

                $addCart = CartsModel::create([
                    'client_id' => $userId,
                    'instructor_id' => $lessonData->user_id,
                    'lesson_id' => $lessonData->id,
                    'group_no' => 0,
                    'amount' => $result['total'] * $lesson['lesson_count'],
                    'currency' => $result['crrncy'],
                    'original_amount' => $lessonData->amount,
                    'original_currency' => $lessonData->currency,
                    'education_type' => $lesson['education_type'],
                    'lesson_count' => $lesson['lesson_count'],
                    'lesson_calendar_ids' => implode(',', $lessonCalendarsIds),
                    'transaction_id' => $transaction_id
                ]);

                $cartIds[] = $addCart->id;
                $totalAmount += $result['total'] * $lesson['lesson_count'];
            } else {
                $result = $this->exchange($lessonData->currency, $lessonData->amount, $currency, $rates);
                $lessonCalendarCount = LessonsCalendarModel::where('lesson_id', $lesson['id'])->where('group_no', $lesson['group_no'])->count();
                $lessonCalendarsIds = LessonsCalendarModel::where('lesson_id', $lesson['id'])->where('group_no', $lesson['group_no'])->pluck('id')->toArray();
                $addCart = CartsModel::create([
                    'client_id' => $userId,
                    'instructor_id' => $lessonData->user_id,
                    'lesson_id' => $lessonData->id,
                    'group_no' => $lesson['group_no'],
                    'amount' => $result['total'],
                    'currency' => $result['crrncy'],
                    'original_amount' => $lessonData->amount,
                    'original_currency' => $lessonData->currency,
                    'education_type' => $lesson['education_type'],
                    'lesson_count' => $lessonCalendarCount,
                    'lesson_calendar_ids' => implode(',', $lessonCalendarsIds),
                    'transaction_id' => $transaction_id
                ]);

                $cartIds[] = $addCart->id;
                $totalAmount += $result['total'];
            }
        }

        // Toplam tutarı güncelle
        $payment = PaymentsModel::create([
            'user_id' => $userId,
            'email' => "testasdas",
            'cart_id' => implode(',', $cartIds),
            'currency' => $currency,
            'amount' => $totalAmount, // Güncellendi
            'payment_method' => $paymentMethod,
            'transaction_id' => $transaction_id,
            'status' => 'pending',
            'cart_total' => $totalAmount,
        ]);
        return response()->json(['data' => $payment]);
    }
}
