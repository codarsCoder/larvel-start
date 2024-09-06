<?php

namespace App\Services;

use App\Models\Exam;
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

}
