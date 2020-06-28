<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    //
    protected $fillable = [
        'date', 'account_title', 'description', 'p_r', 'debit', 'credit', 'user_id'
    ];
    
}
