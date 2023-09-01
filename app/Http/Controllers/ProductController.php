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

      // $request->input()で検索時に入力した項目を取得する
      $keyword = $request->input('keyword');
      $category = $request->input('category');
      $max_price = $request->input('max_price');
      $min_price = $request->input('min_price');
      $max_stock = $request->input('max_stock');
      $min_stock = $request->input('min_stock');

      // $query = $product_model->getListQuery();
      $query = Product::query();
      $query
      ->select('products.*', 'companies.company_name')
      ->leftjoin('companies', 'companies.id', 'products.company_id');
      if(!empty($keyword)){
        $query->where('product_name', 'LIKE', '%'. $keyword. '%');
      }
      // プルダウンメニューで指定なし以外を選択した場合$query->whereで選択したカテゴリーと一致するカラムを取得する
      if(!empty($category)){
        $query->where('company_id', '=', $category);
      }

      if(!empty($max_price)){
        $query->where('price', '>=', $max_price);
      }
      if(!empty($min_price)){
        $query->where('price', '<=', $min_price);
      }
      if(!empty($max_stock)){
        $query->where('stock', '>=', $max_stock);
      }
      if(!empty($min_stock)){
        $query->where('stock', '<=', $min_stock);
      }

      // バリデーション
      $request->validate([
        'keyword' => 'nullable | alpha_num',
      ]);

      // $products = $product_model->getListQueryCategory();
      

      $company_model = new Company();
      $companies = $company_model->getLists();

      

      $query
      ->sortable()
      ->orderBy('products.id', 'desc')
      ->paginate(20);
      $products = $query->get();



      return view('list.index', ['products' => $products, 'companies' => $companies, 'keyword' => $keyword, 'category' => $category, ]);
      // return response()->json([('list.index', ['products' => $products, 'companies' => $companies, 'keyword' => $keyword, 'category' => $category, 'listsorts' => $listsorts, 'order' => $orderpram, ])->render()
      // ]);s
    }

    // 詳細画面
    public function show(Request $request) {

      $id = $request->id;
      $product_model = new Product();
      $products = $product_model->getShow($id);

      // $products = DB::table('products')
      //   ->select('products.*', 'companies.company_name')
      //   ->leftjoin('companies', 'companies.id', 'products.company_id')
      //   ->where('products.id', $id)
      //   ->first();

      return view('list.show', ['products' => $products]);
    }

    // 削除
    public function destroy(Request $request, $id) {

      // 例外処理
      DB::beginTransaction();
      try{
        // $id = $request()->get('id');
        $product_model = new Product();
        $products = $product_model->getDestroy($id);

        // if(!$products){
        //   return response()->json(['message' => '削除できませんでした']);
        // }

        $products->delete();
        DB::commit();
      }catch(\Exception $e){
        DB::rollback();
        // return back();
      }
      // ビュー(list)にリダイレクト
      // return redirect()->route('list');
      return response('list');
    }

    // 登録画面
    public function add(Request $request){

      $keyword = $request->input('keyword');
      $product_model = new Product();
      $query = $product_model->getAdd();

      $products = $product_model->getAddQueryCategory();

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
        // $product_model = new Product();
        // $products = $product_model->getCreate();
        $products = new Product();
        $products->product_name = $request->input('product_name');
        $products->company_id = $request->input('category');
        $products->price = $request->input('price');
        $products->stock = $request->input('stock');
        $products->comment = $request->input('comment');
        $products->img_path = $request->file('img_path')->store('public/image');

        $products->img_path = str_replace('public', '', $products->img_path);

        // $img_path = $product_model->getCreateImg();
        $img_path = new Product;
        $img_path->file = $img_path;

        $products->save();
        DB::commit();
      }catch(\Exception $e){
        DB::rollback();
      }

      // ビュー(add)にリダイレクト
      return redirect()->route('add');
    }

    // 編集画面
    public function edit(Request $request){

      $id = $request->id;
      $product_model = new Product();
      $products = $product_model->getEdit($id);

      $query = $product_model->getEditQuery();
      // $query = Product::query();
      $category = $request->input('category');
      $companies = new Company();
      $companies = $companies->getLists();
      // $category = Company::find($request->company_id);

      return view('list.edit', ['products' => $products, 'companies' => $companies]);
    }

    // 更新
    public function update(Request $request){

      $id = $request->id;

      // バリデーション
      $request->validate([
        'product_name' => 'required | alpha_dash',
        'category' => 'required',
        'price' => 'required | alpha_num',
        'stock' => 'required | alpha_num',
        'comment' => ' nullable | alpha_dash',
        'img_path' => 'nullable | file | image',
      ]);

      // 例外処理
      DB::beginTransaction();
      try{
        $product_model = new Product();
        $products = $product_model->getUpdate($id);
        // $products = Product::find($id);

        // $products = $product_model->getUpdateProducts();
        $products->product_name = $request->input('product_name');
        $products->company_id = $request->input('category');
        $products->price = $request->input('price');
        $products->stock = $request->input('stock');
        $products->comment = $request->input('comment');



        $products->img_path = $request->file('img_path');

        $path = $products->img_path;
        if (isset($products->img_path)){
          \Storage::disk('public')->delete($path);
          $path = $request->file('img_path')->store('public/image');
        }

        // $path = $product_model->getUpdateImg();
        // $path = $products->img_path;
        // if( !is_null($products->img_path) ){
        //  \Storage::disk('public')->delete($path);
        //  $path = $request->file('img_path')->store('public/image');
        // }

        // もし画像の変更があれば元の画像を削除する




        // $products = $product_model->getUpdateDetail();

        $products->update([
          $products->product_name => $request->product_name,
          $products->company_id => $request->category,
          $products->price => $request->price,
          $products->stock => $request->stock,
          $products->comment => $request->comment,
          'img_path' => $path,
        ]);

        $products->save();

        DB::commit();
        // dd($products);
      }catch(\Exception $e){
        DB::rollback();
      }


      // ビュー(edit)にリダイレクト
      return redirect()->route('edit', $products);
    }

}
