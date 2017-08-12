<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name'];

    public function offices()
    {
        return $this->hasMany(Office::class);
    }

    public function mainOffice()
    {
        return $this->offices()->where('is_main', true)->first();
    }

    public function activeEmployees()
    {
        return Employee::join('offices', 'offices.id', '=', 'employees.office_id')
            ->join('companies', 'companies.id', '=', 'offices.company_id')
            ->where('companies.id', $this->id)
            ->where('employees.active', true)
            ->get();
    }
}
