<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <title>@yield('title')</title>
    <style>
      body { font-size: 16pt; color: #3B4B59; margin: 5px; }
      h1 { font-size: 50pt; text-align: right; color: #f6f6f6; margin: -20px 0px -30px 0px; letter-spacing: -4pt; }
      h2 { font-size: 16pt; color: #3B4B59; margin-bottom: 15px; }
      .valid { font-size: 13pt; color: #b94047; }
      ul { font-size: 12pt; }
      hr { margin: 25px 100px; border-top: 1px dashed #ddd; }
      .menutitle { font-size: 14pt; font-weight: bold; margin: 0px; }
      .content {margin: 10px; }
      th { background-color: #999; color: #fff; padding: 5px 10px; }
      td { border: solid 1px #aaa; color: #999; padding: 5px 10px; }
      .btn-1 { font-size: 21px; background-color: #9CB6D8; padding: 5px 20px; }
      .btn-2 { background-color: #9CB6D8;}
      a {text-decoration: none; color: #3B4B59;}
      img { width: 100px; height: 100px; object-fit: cover; }
      .success { padding-left: 30px; }
    </style>
</head>
<body>
    <h1>@yield('title')</h1>
    @section('menubar')

    <ul>
      <li>@show</li>
    </ul>
    <hr size="1">
    <div class="container">
        @yield('content')

        @yield('script')
    </div>
</body>
</html>
