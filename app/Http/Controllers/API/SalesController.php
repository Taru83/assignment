<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;

class SalesController extends Controller
{
    // 在庫を減算する
    public function buy(Request $request) {

        $id = $request->id;
        // モデルの減算処理を呼び出す
        DB::beginTransaction();
        try{

            $sale_model = new Sale();
            // モデルでproductテーブルの在庫を取得する
            
            // productテーブルの在庫を１減らす
            // salesテーブルに情報を追加する
            $sales = $sale_model->dec($id);
            // $product_id = $request->input('product_id');
            
            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
        }
    return response()->json($sales);
    }

    // 購入履歴をつける
    // public function pur(Request $request) {

    //   DB::beginTransaction();
    //     try{
    //         $sales = new Sale();
    //         $sales->product_id = $request->input('product_id');

    //         $sales->save();
    //         DB::commit();
    //     }catch(\Exception $e){
    //         DB::rollback();
    //     }
    //     return response()->json();
    // }

    
}
