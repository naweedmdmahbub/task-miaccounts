<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['account_head_id', 'date', 'debit', 'credit'];
    public function accountHead(){
        return $this->belongsTo(AccountHead::class);
    }
}
