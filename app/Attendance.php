<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    public function attendances()
    {
        return $this->hasMany(Attendance::class,'emp_id','emp_id')->orderBy('id','desc');
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class,'emp_id','emp_id')->orderBy('id','desc');
    }
    public function rate()
    {
        return $this->hasMany(Rates::class,'uid','emp_id')->orderBy('id','desc');
    }
    public function payroll()
    {
        return $this->hasOne(Payroll::class,'store','store')->orderBy('payroll_from','desc')->latest();
    }
}
