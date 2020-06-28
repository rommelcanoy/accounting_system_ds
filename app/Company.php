<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $fillable = [
        'company_name', 'company_owner', 'business_org', 'business_type', 'user_id'
    ];
}
