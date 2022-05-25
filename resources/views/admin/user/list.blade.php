@extends('admin/layout')

@section('contets')
    <div style="padding-left:40px;padding-top:10px">
        <a href="../top">管理画面Top</a><br>
        <a href="list">ユーザ一覧</a><br>
        <a href="../logout">ログアウト</a><br>
    </div>
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