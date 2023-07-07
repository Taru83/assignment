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
    // 一覧
    public function getList() {
      // $products = Product::all();
      $products = DB::table('products')
        ->select('products.id', 'products.product_name', 'products.price', 'products.stock', 'products.comment', 'products.img_path', 'companies.company_name')
        ->leftjoin('companies', 'companies.id', 'products.company_id')
        ->get();

      return $products;
    }

    public function getListQuery() {
      $query = Product::query();
      if(!empty($keyword)){
        $query->where('product_name', 'LIKE', '%'. $keyword. '%');
      }

      return $query;
    }

    public function getListQueryCategory(){
      $query = Product::query()
      ->select('products.*', 'companies.company_name')
      ->leftjoin('companies', 'companies.id', 'products.company_id')
      ->orderBy('products.id')
      ->paginate(20);

      return $query;
    }

    // 詳細
    public function getShow($id) {
      $products = DB::table('products')
        ->select('products.*', 'companies.company_name')
        ->leftjoin('companies', 'companies.id', 'products.company_id')
        ->where('products.id', $id)
        ->first();

      return $products;
    }

    // 削除
    public function getDestroy($id) {
      $products = Product::find($id);

      return $products;
    }

    // 追加
    public function getAdd() {
      $query = Product::query();
      if(!empty($keyword)){
        $query->where('product_name', 'LIKE', '%'. $keyword. '%');
      }

      return $query;
    }

    public function getAddQueryCategory() {
      $query = Product::query()
      ->leftjoin('companies', 'companies.id', 'products.company_id')->paginate(20);

      return $query;
    }

    // 変更
    public function getEdit($id) {
      $products = DB::table('products')
      ->select('products.*', 'companies.company_name')
      ->leftjoin('companies', 'companies.id', 'products.company_id')
      ->where('products.id', $id)
      ->first();

      return $products;
    }

    public function getEditQuery() {
      $query = Product::query();

      return $query;
    }

    public function getUpdate($id) {
      $products = Product::find($id);

      return $products;
    }

    // public function getUpdateProducts() {
    //   $products->product_name = $request->input('product_name');
    //   $products->company_id = $request->input('category');
    //   $products->price = $request->input('price');
    //   $products->stock = $request->input('stock');
    //   $products->comment = $request->input('comment');
    //
    //   $products->img_path = $request->file('img_path');
    //
    //   return $products;
    // }
    // 
    // public function getUpdateImg() {
    //   $path = $products->img_path;
    //   if( !is_null($products->img_path) ){
    //    \Storage::disk('public')->delete($path);
    //    $path = $request->file('img_path')->store('public/image');
    //   }
    //
    //   return $path;
    // }

    // public function getUpdateDetail() {
    //   $products->update([
    //     $products->product_name => $request->product_name,
    //     $products->company_id => $request->category,
    //     $products->price => $request->price,
    //     $products->stock => $request->stock,
    //     $products->comment => $request->comment,
    //     $products->img_path => $path,
    //   ]);
    //
    //   return $products;
    // }
    // テーブル結合
    public function companies(){
      return $this->belongsTo('App\Models\Company');
    }
    public function sales(){
      return $this->hasMany('App\Models\Sale');
    }

}
