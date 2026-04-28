<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = ['transaction_id','code'];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

}
