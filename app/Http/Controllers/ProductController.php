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
      $query = $product_model->getListQuery();

      // バリデーション
      $request->validate([
        'keyword' => 'nullable | alpha_num',
      ]);

      $products = $product_model->getListQueryCategory();
      // $products = $query
      // ->select('products.*', 'companies.company_name')
      // ->leftjoin('companies', 'companies.id', 'products.company_id')
      // ->orderBy('products.id')
      // ->paginate(20);

      $category = $request->input('category');
      $companies = new Company();
      $companies = $companies->getLists();

      return view('list.index', ['products' => $products, 'companies' => $companies, 'keyword' => $keyword, 'category' => $category]);
    }

    // public function radioMethod(Request $request){
    //   return response()->json([
    //     'message' => '非同期処理が成功しました',
    //     'data' => $radioData
    //   ]);
    // }


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
        // $product_model = new Product();
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
      return response('list.destroy');
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
