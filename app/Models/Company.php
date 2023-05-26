<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
      public function getLists(){
        $companies = Company::pluck('company_name', 'id');
        return $companies;
      }

      protected $fillable = [
        'company_name',
      ];

      public function products(){
        return $this->hasMany('App\Models\Product');
      }
}
