<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    public function attendances()
    {
        return $this->hasMany(Attendance::class,'emp_id','emp_id');
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class,'emp_id','emp_id');
    }
}
