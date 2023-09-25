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

    // public function getDec(){
    //   $sales = Product::select('stock')->where('products.id', '=', $request->id)->get();

    //   return $sales;
    // }

    // public function dec(){
    //   // 在庫を減らす処理
    //   $sales = DB::table('products')
    //   ->select('products.*', 'sales.product_id')
    //   // $sales = Product::select('stock')
    //   ->where('sales.product_id', $request->id)
    //   ->decrement('products.stock', 1);
    //   // 在庫が０ならjsonでエラーメッセージを出す
    //   // if($sales === 0){
    //   //     return response()->json(['message' => '在庫がありません'], 404);
    //   // }
    //   // $sales = Product::decrement('products.stock', 1);

    //   return $sales;
    // }

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

         // model
    //getDecのカッコの中に引数を入れて、入力値（$requestを受けとれるようにしましょう。）
    //また、$request->idとしていますが、コントローラーを見るかぎりおそらく'product_id'として送っているはずです。
    public function getDec($id){
      $sales = Product::select('stock')->where('products.id', '=', $id)->value('stock');

      return $sales;
    }

    public function dec($id){
    //getDecと同じく、引数とidについて
      $sales = DB::table('products')
      //salesテーブルの情報も使おうとしいますが、joinもしていないのでここでは使えません。
      ->select('products.stock')
      //productsテーブルの操作になるので、ここのwhereはsales.product_idではなくproducts.idで絞るべきです
      ->where('products.id', $id)
      ->decrement('products.stock', 1);

      // return $sales;
    }
    //salesテーブルへの新規追加処理は別途関数を作りましょう。
    public function getinc($id){
      $sales = DB::table('sales')
      // ->select('product_id', 'id')
      ->insert([
        'product_id' => $id,
        'created_at' => now(),
        'updated_at' => now()
      ]);
      // ->where('sales.id', $id);

      // return $sales;
      
    }
}
