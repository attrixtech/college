<?php

namespace App\Models;

class FinancialVoucher extends BaseModel
{
    public function debitAccount() { return $this->belongsTo(ChartOfAccount::class, "debit_account_id"); }
    public function creditAccount() { return $this->belongsTo(ChartOfAccount::class, "credit_account_id"); }
    public function department() { return $this->belongsTo(Department::class); }
    public function creator() { return $this->belongsTo(User::class, "created_by"); }
}
