
@extends('layouts.app')

@section('title', 'Edit')

@section('menubar')
  @parent
  商品編集
@endsection

@section('content')
  @if($errors->any())
    <div class="valid">
      @foreach($errors->all() as $error)
        {{ $error }}<br>
      @endforeach
    </div>
  @endif
  <form action="{{ route('update', ['id'=>$products->id]) }}" method="post" enctype="multipart/form-data">
    <table class="table table-striped">
      @csrf
      @method('patch')
      <tr>
        <th>id: </th><td>{{ optional($products)->id }}</td>
      </tr>
      <tr>
        <th>商品名 </th><td><input type="text" name="product_name" value="{{ optional($products)->product_name }}"></td>
      </tr>
      <tr>
        <th>メーカー: </th>
        <td>
          <select name="category" value="{{ $companies }}" style="width: 70%">
          <!-- <select name="category" value="{{ optional($products)->company_name }}" style="width: 70%"> -->
          <option value="">未選択</option>
          @foreach($companies as $id => $company_name)
            <option value="{{ $id }}" {{ old('products', $products->company_id ?? '') == $products->company_id ? 'selected' : '' }}>
              {{ $company_name }}
            </option>
          @endforeach
          </select>
        </td>
      </tr>
      <tr>
        <th>価格 </th><td><input type="text" name="price" value="{{ optional($products)->price }}"></td>
      </tr>
      <tr>
        <th>在庫数 </th><td><input type="text" name="stock" value="{{ optional($products)->stock }}"></td>
      </tr>
      <tr>
        <th>コメント </th><td><textarea name="comment" rows="8">{{ old('comment', $products->comment)}}</textarea></td>
      </tr>

      <tr>
        <th>商品画像 </th>
        <td>
          @if($products->img_path)
            <img src="{{ asset(Storage::url($path)) }}" alt="">
          @else
            <img src="{{ asset( Storage::url($products->img_path)) }}"><br>
          @endif
        </td>
        <td><input type="file" name="img_path"></td>
      </tr>
      <tr>
        <th></th><td><input type="submit" value="更新"　style=""></td>
      </tr>
      <td><a type="button" href="{{ route('show', $products->id) }}" class="btn btn-primary">戻る</a></td>
    </table>
  </form>
@endsection
