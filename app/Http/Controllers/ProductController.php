<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Company;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\File;

class ProductController extends Controller
{
    // 一覧画面
    public function index(Request $request) {

      $id = $request->id;
      $product_model = new Product();
      $products = $product_model->getList();

      $keyword = $request->input('keyword');
      $query = Product::query();
      if(!empty($keyword)){
        $query->where('product_name', 'LIKE', '%'. $keyword. '%');
      }
      // バリデーション
      $request->validate([
        'keyword' => 'nullable | alpha_num',
      ]);

      $products = $query
      ->select('products.*', 'companies.company_name')
      ->leftjoin('companies', 'companies.id', 'products.company_id')
      ->orderBy('products.id')
      ->paginate(20);

      $category = $request->input('category');
      $companies = new Company();
      $companies = $companies->getLists();

      return view('list.index', ['products' => $products, 'companies' => $companies, 'keyword' => $keyword, 'category' => $category]);
    }

    // 詳細画面
    public function show(Request $request) {

      $id = $request->id;

      // $product_model = new Product();
      // $products = $product_model->getShow();

      $products = DB::table('products')
        ->select('products.*', 'companies.company_name')
        ->leftjoin('companies', 'companies.id', 'products.company_id')
        ->where('products.id', $id)
        ->first();

      return view('list.show', ['products' => $products]);
    }

    // 削除
    public function destroy($id) {

      $products = Product::find($id);
      // 例外処理
      DB::beginTransaction();
      try{
        $products->delete();
        DB::commit();
      }catch(\Exception $e){
        DB::rollback();
        return back();
      }
      // ビュー(list)にリダイレクト
      return redirect()->route('list')->with('success', '削除しました!');
    }

    // 登録画面
    public function add(Request $request){

      $keyword = $request->input('keyword');
      $query = Product::query();
      if(!empty($keyword)){
        $query->where('product_name', 'LIKE', '%'. $keyword. '%');
      }

      $products = $query->leftjoin('companies', 'companies.id', 'products.company_id')->paginate(20);

      $category = $request->input('category');
      $companies = new Company();
      $companies = $companies->getLists();


      return view('list.add', ['products' => $products, 'companies' => $companies, 'keyword' => $keyword, 'category' => $category]);
    }

    // 保存
    public function create(Request $request){
      // バリデーション
      $request->validate([
        'product_name' => 'required | alpha_dash',
        'category' => 'required',
        'price' => 'required | alpha_num',
        'stock' => 'required | alpha_num',
        'comment' => 'nullable | alpha_dash',
        'img_path' => 'nullable | file | image',
      ]);
      // 例外処理
      DB::beginTransaction();
      try{
        $products = new Product;

        $products->product_name = $request->input('product_name');
        $products->company_id = $request->input('category');
        $products->price = $request->input('price');
        $products->stock = $request->input('stock');
        $products->comment = $request->input('comment');
        $products->img_path = $request->file('img_path')->store('public/image');

        $products->img_path = str_replace('public', '', $products->img_path);

        $img_path = new Product;
        $img_path->file = $img_path;

        $products->save();
        DB::commit();
      }catch(\Exception $e){
        DB::rollback();
      }
      // ビュー(add)にリダイレクト
      return redirect()->route('add')->with('success', '追加しました！');
    }

    // 編集画面
    public function edit(Request $request){

      $id = $request->id;
      $products = DB::table('products')
      ->select('products.*', 'companies.company_name')
      ->leftjoin('companies', 'companies.id', 'products.company_id')
      ->where('products.id', $id)
      ->first();

      $query = Product::query();
      // $category = $request->input('category');
      $companies = new Company();
      $companies = $companies->getLists();
      // $category = Company::find($request->company_id);

      return view('list.edit', ['products' => $products, 'companies' => $companies]);
    }

    // 更新
    public function update(Request $request){

      $id = $request->id;
      $products = Product::find($request->id);
      // 例外処理
      DB::beginTransaction();
      try{
        $products->product_name = $request->input('product_name');
        $products->company_id = $request->input('category');
        $products->price = $request->input('price');
        $products->stock = $request->input('stock');
        $products->comment = $request->input('comment');

        $products->img_path = $request->file('img_path');

          $path = $products->img_path;
          if( !is_null($products->img_path) ){
           \Storage::disk('public')->delete($path);
           $path = $request->file('img_path')->store('public/image');
          }

        // $img_path = [];
        //
        // if($request->has('img_path')){
        //
        //   $file_name = $products['img_path'];
        //
        //   if($products->img_path !== null){
        //     // 送られてきているfileオブジェクトをeachで回す
        //     foreach ($request->img_path as $img_path) {
        //       if($file_name === $img_path){
        //         // 更新される予定のimageを取得
        //         $edit_image = Product::find($img_path);
        //         // 新しく入ってきている写真をfileで保存処理
        //         $path = $image->store('public/image');
        //         // カラムに保存するパスを作成
        //         $read_temp_path = str_replace('public/', 'storage/', $path);
        //         // 元からある写真に新しく作成したパスを入れる
        //         $edit_image->src = $read_temp_path;
        //       }else {
        //         // 更新処理でない場合は新規作成の流れ
        //         $img_path = new Product();
        //         $path = $image->store('public');
        //         $read_temp_path = str_replace('public/', 'storage/', $path);
        //
        //         $img_path->src = $read_temp_path;
        //       }
        //     }
        //   }
        // }

        // もし画像が空なら何もしない、変更があるなら元の画像を削除する
        // if( !is_null($products->img_path) ){
        //   // $request->file('img_path');
        //   \Storage::disk('public')->delete($path);
        //   $path = $request->file('img_path')->store('public/image');
        // }
        // $path = $products->img_path;

          // if (isset($products->img_path)){
          //   \Storage::disk('public')->delete($path);
          //   // $path = $products->img_path->store('public/image');
          //   $path = $request->file('img_path')->store('public/image');
          // }


        // dd($path);


          // $path = $products->img_path;
          // if (isset($products->img_path)){
          //   \Storage::disk('public')->delete($path);
          //   $path = $request->file('img_path');
          //   $path = $request->file('img_path')->store('public/image');




        // もし画像の変更があれば元の画像を削除する
        // $path = $products->img_path;
        // if (isset($products->img_path)){
        //   \Storage::disk('public')->delete($path);
        //   $path = $request->file('img_path')->store('public/image');
        // }
        // バリデーション
        $request->validate([
          'product_name' => 'required | alpha_dash',
          'category' => 'required',
          'price' => 'required | alpha_num',
          'stock' => 'required | alpha_num',
          'comment' => ' nullable | alpha_dash',
          'img_path' => 'nullable | file | image',
        ]);

        $products->update([
          $products->product_name => $request->product_name,
          $products->company_id => $request->category,
          $products->price => $request->price,
          $products->stock => $request->stock,
          $products->comment => $request->comment,
          $products->img_path => $path,
        ]);
          // もし'img_path'があればupdate、なければ実行しない
          // if (isset($path)){
          //   'img_path' -> $path;
          // }else{
          //   continue;
          // }


        DB::commit();
      }catch(\Exception $e){
        DB::rollback();
      }
      // ビュー(edit)にリダイレクト
      return redirect()->route('edit', $products)->with('success', '更新しました！');
    }

}
