<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    public function getLists(){
      $sales = Sale::pluck('product_id', 'id');
      return $sales;
    }

    protected $fillable = [
      'id', 'product_id',
    ];

    public function products(){
      return $this->belongsTo('App\Models\Product');
    }

    // テーブルのデータにアクセスする
    protected $table = 'sales';
    protected $dates =  ['created_at', 'updated_at'];

}
