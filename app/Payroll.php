<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    //
    public function informations()
    {
        return $this->hasMany(PayrollInfo::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'generated_by','id');
    }
}
