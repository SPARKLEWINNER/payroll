<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollInfo extends Model
{
    //
    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
    public function payroll_allowances()
    {
        return $this->hasMany(PayrollAllowance::class);
    }
    public function payroll_gross_allowances()
    {
        return $this->hasMany(PayrollGrossAllowance::class);
    }
    public function deductions()
    {
        return $this->hasMany(PayrollDeduction::class);
    }
}
