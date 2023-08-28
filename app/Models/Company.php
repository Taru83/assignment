<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Kyslik\ColumnSortable\Sortable;

class Company extends Model
{
      use Sortable;

      public function getLists(){
        // $companies = Company::pluck('company_name', 'id');
        $companies = DB::table('companies')->get();
        return $companies;
      }

      protected $fillable = [
        'company_name',
      ];

      public $sortable = [
        'company_name',
      ];

      public function products(){
        return $this->hasMany('App\Models\Product');
      }
}
