
@extends('layouts.app')

@section('title', 'Company.Add')

@section('menubar')
  @parent
  メーカー登録
@endsection

@section('content')
  <form action="/Company/add" method="post">
  <table>
    @csrf
    <tr>
      <th>company_name: </th><td><input type="text" name="company_name"></td>
    </tr>
    <tr>
      <th>street_address: </th><td><input type="text" name="street_address"></td>
    </tr>
    <tr>
      <th>representative_name: </th><td><input type="text" name="representative_name"></td>
    </tr>
    <tr>
      <th></th><td><input type="submit" value="send"></td>
    </tr>
  </table>
  </form>
@endsection
