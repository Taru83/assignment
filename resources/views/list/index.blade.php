
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
  <form action="{{ route('list') }}" method="get">
    @csrf
    <h2>商品検索</h2>
    <input id="keyword" type="text" name="keyword" value="{{ $keyword }}" placeholder="商品名" style="font-size: 20px; margin-bottom: 10px;">

    <select id="category" name="category" value="{{ $category }}" style="width: 20%; font-size: 20px;">
      <option value="" hidden>メーカーを選択</option>
      @foreach($companies as $company)
        
        <option value="{{ $company->id }}">
          {{ $company->company_name }}
        </option>
      @endforeach
    </select><br>

    <div class="maxmin">
      <div class="price-search">
        <h2>価格</h2>
        <div class="jougen">
          <input type="number" name="max_price" id="max_price" placeholder="上限">
        </div>
        <div class="kagen">
          <input type="number" name="min_price" id="min_price" placeholder="下限">
        </div>
      </div>

      <div class="stock-search">
        <h2>在庫数</h2>
        <div class="jougen">
          <input type="number" name="max_stock" id="max_stock" placeholder="上限">
        </div>
        <div class="kagen">
          <input type="number" name="min_stock" id="min_stock" placeholder="下限">
        </div>
      </div>
    </div>
    
    <input type="submit" class="btn btn-search" value="検索" class="btn-2" style="margin: 10px 0 15px 0; font-size: 20px;"><br>
    <!-- <input type="button" class="btn btn-search" value="検索" class="btn-2" style="margin: 10px 0 15px 0;"><br> -->
  </form>


  <table>
    <tr>
      <td class="btn-1"><a href="{{ route('add') }}" class="btn btn-primary">新規登録</a></td>
    </tr>
  </table>
@endsection

@section('content')
  <table class="table table-striped" id="product-list">
    <thead>
      <tr>
        <!-- <th><a href="{{ route('list', )}}" title="">ID</a></th> -->
        <th>@sortablelink('id', 'ID')</th>
        <th>画像</th>
        <!-- <th>商品名</th> -->
        <th>@sortablelink('product_name', '商品名')</th>
        <!-- <th><a href="" title="">価格</a></th> -->
        <th>@sortablelink('price', '価格')</th>
        <!-- <th><a href="" title="">在庫数</a></th> -->
        <th>@sortablelink('stock', '在庫数')</th>
        <!-- <th>メーカー名</th> -->
        <th>@sortablelink('company_name', 'メーカー名')</th>
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
            @method('DELETE')
            <!-- <input type="submit" class="btn btn-del" value="削除" onclick='confirm("削除しますか？");'> -->
            <input type="button" class="btn btn-del" value="削除" data-productId="{{ $product->id }}">
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
                // 'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              }
            });

            // 削除ボタンの非同期通信
            $(function(){
              // <!-- 削除ボタンがクリックされた時の処理 -->
              $('.btn-del').on('click', function(){
                console.log('あ');
                var deleteConfirm = confirm('削除しますか？');
                // var productID = $('input[data-productId="{ $product->id }"]');
                var productID = $(this).data('productid');
                $(this).parents('tr').remove();

                  if(deleteConfirm == true) {
                    console.log($(this));
                    $.ajax({
                      type: 'post',
                      url: 'list/destroy/' + productID,
                      // data: 'product_id',
                      data: { _method:'DELETE'},
                      datatype: 'html',
                      
                    }).done(function(response){
                      // 通信が成功した時の処理
                      // $('.list').html(response);
                      // $('#product-list').find('tbody tr').remove();
                      // $(this).remove();
                      // $('#product-list　tr:has(input[type=button]:checked)').remove();
                      var deleteConfirm = confirm('削除しました');
                      // $(this).parents('tr').remove();
                      // console.log('response.message');
                    }).fail(function(jqXHR, textStatus, errorThrown){
                      // 通信が失敗した時の処理
                      var deleteConfirm = confirm('削除に失敗しました');
                    })
                  }
              });
            });

            // 検索ボタンの非同期通信（とりあえず入力フォームだけ）
            $(function(){
              $('.btn-search').on('click', function(){
                event.preventDefault();
                console.log('う');
                // 元々ある要素を空にしている
                // $('.table-striped tbody').empty();
                // 検索ボックスに入れられた値を取得し、変数search_catchに格納
                // var keyText = $(this).val();
                var keyword = $('#keyword').val();
                var category = $('#category').val();
                var max_price = $('#max_price').val();
                var min_price = $('#min_price').val();
                var max_stock = $('#max_stock').val();
                var min_stock = $('#min_stock').val();
                
                  $.ajax({
                    type: 'get',
                    url: "{{ route('list') }}",
                    data: { 'keyword': keyword,
                            'category': category,
                            'max_price': max_price,
                            'min_price': min_price,
                            'max_stock': max_stock,
                            'min_stock': min_stock },
                    datatype: 'html',

                  }).done(function(data){
                    console.log('ちゃんと動いているよ');

                    var newTable = $(data).find('#product-list');
                    $('#product-list').html(newTable);
                    // $('.loading').addClass('display-none'); //通信中のぐるぐるを消す
                    // let html = '';
                    //dataの中身からvalueを取り出す
                    // $.each(data, function (index, value) {

                    //   let id = value.id;
                    //   let img_path = value.img_path;
                    //   let product_name = value.product_name;
                    //   let price = value.price;
                    //   let stock = value.stock;
                    //   let company_name = value.company_name;

                    //   html = `
                    //     <tr>
                    //       // ${}で変数展開
                    //       <td>${id}</td>
                    //       <td><img src="${img_path}"></td>
                    //       <td>${product_name}</td>
                    //       <td>${price}</td>
                    //       <td>${stock}</td>
                    //       <td>${company_name}</td>
                    //     </tr>
                    //   `
                    // })
                    //できあがったテンプレートをビューに追加
                    // $('.table-striped tbody').append(html);

                  }).fail(function(jqXHR, textStatus, errorThrown){
                    console.log('検索できてないよ');
                    var search_catch = confirm('検索に失敗しました');
                  });
              });
            });
  </script>
@endsection
