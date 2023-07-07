
@extends('layouts.app')

@section('title', 'List')

@section('menubar')
  @parent
  商品一覧

  <!-- 認証 -->
  <div class="card-body">
      @if (session('status'))
          <div class="alert alert-success" role="alert">
              {{ session('status') }}
          </div>
      @endif

      You are logged in!
  </div>
  @if(Auth::check())
  <li><a href="{{ route('logout') }}">Logout</a></li>
  @else
  <li>
    <a href="{{ route('list') }}">List</a>
  </li>
  @endif
    @csrf

  <!-- エラーメッセージ -->
  @if($errors->any())
    <div class="valid">
      @foreach($errors->all() as $error)
        {{ $error }}<br>
      @endforeach
    </div>
  @endif

  <!-- 検索フォーム -->
  <form action="list" method="get">
    @csrf
    <h2>商品検索</h2>
    <input type="text" name="keyword" value="{{ $keyword }}" placeholder="商品名" style="font-size: 20px; margin-bottom: 10px;">

    <select name="category" value="{{ $category }}" style="width: 20%; font-size: 20px;">
      <option value="">未選択</option>
      @foreach($companies as $id => $company_name)
        <option value="{{ $id }}">
          {{ $company_name }}
        </option>
      @endforeach
    </select><br>
    <label>
      <input type="radio" name="price" value="cheep">価格が安い順
    </label>
    <label>
      <input type="radio" name="price" value="expensive">価格が高い順
    </label><br>
    <label>
      <input type="radio" name="stock" value="low">在庫が少ない順
    </label>
    <label>
      <input type="radio" name="stock" value="high">在庫が多い順
    </label><br>

    <input type="submit" value="検索" class="btn-2" style="margin: 10px 0 15px 0;"><br>
  </form>


  <table>
    <tr>
      <td class="btn-1"><a href="{{ route('add') }}" class="btn btn-primary">新規登録</a></td>
    </tr>
  </table>
@endsection

@section('content')
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>画像</th>
        <th>商品名</th>
        <th>価格</th>
        <th>在庫数</th>
        <th>メーカー名</th>
      </tr>
    </thead>

    <tbody>
      @foreach ($products as $product)
      <tr>
        <td>{{ $product->id }}</td>
        <!-- <td><img src="{{ asset('storage/image/'. $product->img_path) }}"></td> -->
        <td><img src="{{ asset( Storage::url($product->img_path)) }}"></td>
        <td>{{ $product->product_name }}</td>
        <td>{{ $product->price }}</td>
        <td>{{ $product->stock }}</td>
        <td>{{ $product->company_name }}</td>
        <td class="btn-2"><a href="{{ route('show', $product->id) }}" class="btn btn-primary">詳細</a></td>
        <td class="btn-2">
          <form class="" action="{{ route('destroy', ['id'=>$product->id]) }}" method="post">
            @csrf
            
            <!-- <input type="submit" class="btn btn-del" value="削除" onclick='confirm("削除しますか？");'> -->
            <input type="button" class="btn btn-del" value="削除" data_productId="{{ $product->id }}">
          </form>

        </td>
      </tr>
      @endforeach

    </tbody>
  </table>
  <script>
            console.log('い');
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN' : '{{ csrf_token() }}'
              }
            });

            $(function(){
              // <!-- 削除ボタンがクリックされた時の処理 -->
              $('.btn-del').on('click', function(){
                console.log('あ');
                var deleteConfirm = confirm('削除しますか？');
                productID = $('input[data-productId="{ $product->id }"]');
                // var productID = $(this).data('{{ $product->id }}');

                // <!-- 削除対象のアイテムの識別子を取得 -->

                  if(deleteConfirm == true) {
                    console.log($(this));
                    $.ajax({
                      type: 'post',
                      url: 'list/destroy/' + productID,
                      // data: 'product_id',
                      data: { _method:'DELETE'},
                      datatype: 'json',
                      
                    }).done(function(response){
                      // 通信が成功した時の処理
                      var deleteConfirm = confirm('削除しました');
                      // console.log('response.message');
                    }).fail(function(jqXHR, textStatus, errorThrown){
                      // 通信が失敗した時の処理
                      var deleteConfirm = confirm('削除に失敗しました');
                      
                    });
                  }
                              // <!-- 削除リクエストを送信 -->
                
              });
            });
          </script>
@endsection
