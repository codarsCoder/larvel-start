<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Exam;
use App\Models\Payment;
use App\Models\Purchase;

class PurchaseService
{
    // public function create(array $data): Exam
    // {
    //     return Exam::create($data);
    // }

   public function getPurchasedsByUser($userId)
    {
        return Purchase::where('user_id', $userId)->get();
    }

    public function createCart($data)
    {
        return Cart::create($data);
    }

    public function createPayment($data)
    {
        $purchaseData =Payment::create($data);
        return $purchaseData->id;
    }
    public function getPaymentWithStatus($transactionId, $status)
    {
        return Payment::where('transaction_id', $transactionId)->where('status', $status)->first();
    }

    public function processPayment(object $paymentDetails, Payment $startPayment, array $requestData)
    {
        // Ödeme başarılıysa
        if ($paymentDetails->status === 'succeeded') {
            $startPaymentAmount = $startPayment->current_amount;
            $paymentDetailsAmount = $paymentDetails->amount / 100;

            if ($startPaymentAmount == $paymentDetailsAmount) {
                // Ödeme tutarı doğruysa
                $startPayment->status = 'succeeded';
            } else {
                // Ödeme tutarı uyumsuzsa
                $startPayment->status = 'mismatch';
            }

        } else {
            // Ödeme başarılı değilse, gelen status neyse onu kaydediyoruz
            $startPayment->status = $paymentDetails->status;
        }

        // Ödeme açıklaması ekleniyor
        $startPayment->description = json_encode($requestData);
        $startPayment->save();

        return $startPayment;
    }



}
