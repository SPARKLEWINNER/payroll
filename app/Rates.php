<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rates extends Model
{
    protected $fillable = [
        'daily',
        'uid',
        "nightshift",
        "restday",
        "restdayot",
        "holiday",
        "holidayot",
        "holidayrestday",
        "holidayrestdayot",
        'specialholiday',
        'specialholidayot',
        'specialholidayrestday',
        'specialholidayrestdayot',
        'sss',
        'philhealth',
        'pagibig',
        'overtime',
        'status',
        'store',
        'allowance',
        'late',
        'undertime',

    ];
}
