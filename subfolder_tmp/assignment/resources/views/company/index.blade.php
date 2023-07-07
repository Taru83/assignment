
@extends('layouts.app')

@section('title', 'Company.index')

@section('menubar')
  @parent
  メーカー
@endsection

@section('content')
  <table>
    <tr>
      <th>Data</th>
    </tr>
    @foreach($items as $item)
      <tr>
        <td>{{$item->getData()}}</td>
      </tr>
    @endforeach
  </table>
@endsection
