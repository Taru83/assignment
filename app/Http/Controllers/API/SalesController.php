<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

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

            $sales = $sale_model->getDec($id);

            // $products->stock = $request->input('stock');
            // $sales = $request->input('stock');
            // productテーブルの在庫を１減らす
            if($sales->stock == 0){
                return response()->json(['message' => '在庫がありません'], 404);
             }else{
                // $sale_model = new Sale();
                $sales = $sale_model->dec($id);
                // $sales = Product::decrement('stock', 1);
                $sales->update([
                    $sales->stock => $request->stock,
                ]);
                $sales->save();
             }
            
            // $product_id = $request->input('product_id');
            
            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
        }
    return response()->json($sales);
    }

    // salesテーブルに情報を追加する
    public function pur(Request $request) {

      DB::beginTransaction();
        try{
            $sales = new Sale();

            $sales->product_id = $request->input('product_id');

            $sales->save();
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json($sales);
    }

    
}
