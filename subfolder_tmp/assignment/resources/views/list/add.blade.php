
@extends('layouts.app')

@section('title', 'Add')

@section('menubar')
  @parent
  商品情報登録
@endsection

@section('content')
  @if($errors->any())
    <div class="valid">
      @foreach($errors->all() as $error)
        {{ $error }}<br>
      @endforeach
    </div>
  @endif



  <form action="{{ route('add') }}" method="post" enctype="multipart/form-data">
  <table class="table table-striped">
    @csrf
    <tr>
      <th>商品名: </th><td><input type="text" name="product_name" value="{{ old('product_name') }}"></td>
    </tr>
    <tr>
      <th>メーカー: </th>
      <td>
        <select name="category" value="{{ $category }}" style="width: 70%">
        <option value="">未選択</option>
        @foreach($companies as $id => $company_name)
          <option value="{{ $id }}">
            {{ $company_name }}
          </option>
        @endforeach
        </select>
      </td>
    </tr>
    <tr>
      <th>価格: </th><td><input type="text" name="price" value="{{ old('price') }}"></td>
    </tr>
    <tr>
      <th>在庫数: </th><td><input type="text" name="stock" value="{{ old('stock') }}"></td>
    </tr>
    <tr>
      <th>コメント: </th><td><textarea name="comment" rows="8">{{ old('comment') }}</textarea></td>
    </tr>
    <tr>
      <th>商品画像: </th><td><input type="file" name="img_path"></td>
    </tr>
    <tr>
      <th></th><td><input type="submit" value="登録"　style=""></td>
    </tr>
    <td><a type="button" href="{{ route('list') }}" class="btn btn-primary">戻る</a></td>
  </table>
  </form>
@endsection
