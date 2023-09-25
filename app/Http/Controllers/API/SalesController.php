<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    // 在庫を減算する
    // public function buy(Request $request) {

    //     $id = $request->id;
    //     // モデルの減算処理を呼び出す
    //     DB::beginTransaction();
    //     try{


    //         $sale_model = new Sale();
    //         // モデルでproductテーブルの在庫を取得する


    //         $sales = $sale_model->getDec($id);
    //         // $products->stock = $request->input('stock');
    //         // $sales = $request->input('stock');
    //         // productテーブルの在庫を１減らす
    //         if($sales->stock == 0){
    //             return response()->json(['message' => '在庫がありません'], 404);
    //          }else{
    //             // $sale_model = new Sale();
    //             $sales = $sale_model->dec($id);
    //             // $sales = Product::decrement('stock', 1);
    //             $sales->update([
    //                 $sales->stock => $request->stock,
    //             ]);
    //             $sales->save();
    //          }
            
    //         // $product_id = $request->input('product_id');
            
    //         DB::commit();
    //     }
    //     catch(\Exception $e){
    //         DB::rollback();
    //     }
    // return response()->json($sales);
    // }

    // salesテーブルに情報を追加する
    // public function pur(Request $request) {

    //   DB::beginTransaction();
    //     try{
    //         // $id = $request->id;
    //         $sales = new Sale();
    //         $sales = $sale_model->getDec();
            
            
    //         $sales->product_id = $request->input('product_id');
    //         // $sales->stock = $request->input('stock');
    //         $sales = $sale_model->dec();
    //         if(($sales->stock) == 0){
    //             return response()->json(['message' => '在庫がありません'], 404);
    //         };
            

    //         $sales->save();
    //         DB::commit();
    //     }catch(\Exception $e){
    //         DB::rollback();
    //     }
    //  return response()->json($sales);
    // }


    // いただいたコードにコメントをつけておきましたのでご確認ください （もともとコメントアウトされていたところは消しています。） controller
    public function pur(Request $request) {

        DB::beginTransaction();

            try{
                //以降の処理でモデルの処理呼びだしを$sale_model->としているので、変数名は$sale_modelの方が良いです。
                $sale_model = new Sale();
                // $sales = new Sale();

                //上でgetDecを呼び出す前にここはやりましょう。また、＝の左辺はただ$idとかにした方がわかりやすいです

                // $id = $request->input('id');
                // $sales->id = $request->input('id');
                // $sales->product_id = $request->input('product_id');
                $id = $request->input('product_id');

                
                
                
                // $sales->product_id = $request->input('product_id');
                //getDec()に引数がないため、モデルにidが渡せていません。
                //また$sale_modelが定義されていません。
                $sales = $sale_model->getDec($id);
                // $sales = $sale_model->getinc($id);

                // $id = $request->input('id');

                //ここの在庫0なら買えない処理は、モデルのdecに飛ばす前にやりましょう。
                if($sales <= 0){
                    return response()->json(['message' => '在庫がありません'], 404);
                }else{
                    //ここも引数がありません＆$sale_modelが定義されていません。
                    //また、下のif文よりあとに実行しましょう。
                    $sale_model->dec($id);

                    // $sales->id = $request->input('id');
                    // $sales->product_id = $request->input('product_id');
                    // $sales->id = $request->input('id');
                    $sale_model->getinc($id);
                    
                    // $sale = $sale_model->save($id);
                    // 減算後のメッセージ
                    $stock = $sale_model->getDec($id);
                    
                    
                };
                
                
                DB::commit();
                return response()->json($stock);
            }catch(\Exception $e){
                DB::rollback();
            }

    }

}
