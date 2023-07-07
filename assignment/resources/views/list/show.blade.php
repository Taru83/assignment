
@extends('layouts.app')

@section('title', 'Show')

@section('menubar')
  @parent
  商品詳細
@endsection

@section('content')
  <form action="/list/show" method="post">
    <table>
          <tr>
            <th>id: </th><td>{{ optional($products)->id }}</td>
          </tr>
          <tr>
            <th>画像: </th><td><img src="{{ asset( Storage::url(optional($products)->img_path)) }}"></td>
          </tr>
          <tr>
            <th>商品名: </th><td>{{ optional($products)->product_name }}</td>
          </tr>
          <tr>
            <th>価格: </th><td>{{ optional($products)->price }}</td>
          </tr>
          <tr>
            <th>在庫数: </th><td>{{ optional($products)->stock }}</td>
          </tr>
          <tr>
            <th>メーカー: </th><td>{{ optional($products)->company_name }}</td>
          </tr>
          <tr>
            <th>コメント: </th><td>{{ optional($products)->comment }}</td>
          </tr>
          <tr>
            <td><a href="{{ route('edit', $products->id) }}" class="btn btn-primary">編集</a></td>
            <td><a type="button" href="{{ route('list') }}" class="btn btn-primary">戻る</a></td>
          </tr>
    </table>
  </form>

@endsection
