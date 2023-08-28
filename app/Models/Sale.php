<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class Sale extends Model
{
    // use HasFactory;
    // テーブルのデータにアクセスする
    protected $table = 'sales';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = ['id', 'product_id'];

    public function products(){
      return $this->belongsTo('App\Models\Product');
    }

    public function dec($id){
      // 在庫を減らす処理
      $sales = DB::table('products')
      ->where('products.id', '=', $id)
      ->decrement('stock', 1);
      // 在庫が０ならjsonでエラーメッセージを出す
      if($sales === 0){
          return response()->json(['message' => '在庫がありません'], 404);
      }

      $sales = DB::table('sales')
      ->select('products.*', 'sales.*')
      ->join('products', 'sales.product_id', '=', 'products.id')
      ->where('products.id', '=', $id)
      ->decrement('stock', 1);
      // 在庫が０ならjsonでエラーメッセージを出す
      if($sales === 0){
          return response()->json(['message' => '在庫がありません'], 404);
      }

      return $sales;
    }

    // public function inc($id){
    //   // salesテーブルへ情報を追加

    //   return 
    // }
    // public function getLists(){
    //   $sales = Sale::pluck('product_id', 'id');
    //   return $sales;
    // }

    // public function products(){
    //   return $this->belongsTo('App\Models\Product');
    // }
}
