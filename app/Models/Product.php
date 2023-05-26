<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    public function users(){
        return $this->belongsTo('App\Models\User');//リレーション
    }

    protected $fillable = [
      'product_name',
      'company_id',
      'price',
      'stock',
      'comment'
    ];
    // データベース持ってくる
    public function getList() {
      // $products = Product::all();
      $products = DB::table('products')
        ->select('products.id', 'products.product_name', 'products.price', 'products.stock', 'products.comment', 'products.img_path', 'companies.company_name')
        ->leftjoin('companies', 'companies.id', 'products.company_id')
        ->get();

      return $products;
    }
    // テーブル結合
    public function companies(){
      return $this->belongsTo('App\Models\Company');
    }



    public function sales(){
      return $this->hasMany('App\Models\Sale');
    }

    // public function getShow(){
    //   $products = DB::table('products')
    //   ->select('products.*', 'companies.company_name')
    //   ->leftjoin('companies', 'companies.id', 'products.company_id')
    //   ->where('products.id')
    //   ->first();
    //
    //   return $products;
    // }


    // protected $guarded = array('id');
    //
    // public static $rules = array(
    //   'product_name' => 'required',
    //   'company_name' => 'required',
    //   'price' => 'integer|min:0|max:999',
    //   'stock' => 'integer|min:0|max:999',
    //   'comment' => 'required',
    //   'img_path' => 'file'
    // );
    //
    // public function getData(){
    //   return $this->id;
    // }
}
