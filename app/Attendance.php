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
}
