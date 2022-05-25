@extends('admin.layout')

{{-- タイトル --}}
@section('title')(ユーザ一覧画面)@endsection

{{-- メインコンテンツ --}}
@section('contets')
        <h1>ユーザ一覧</h1>
        <table border="1">
        <tr>
            <th>ユーザID
            <th>ユーザ名
            <th>購入した「買うもの」の数
@foreach($users as $list)
        <tr>
            <td>{{ $list->id }}
            <td>{{ $list->name }}
            <td>{{ $list->shopping_num }}
@endforeach
        </table>     
@endsection