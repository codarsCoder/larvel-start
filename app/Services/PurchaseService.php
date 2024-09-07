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
}
