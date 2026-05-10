<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    protected $fillable = [
        'transaction_id','condition','fine','notes','returned_at'
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

}
