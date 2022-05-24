@extends('layout')

@section('tiele')
(一覧画面)
@endsection

{{-- メインコンテンツ --}}
@section('contets')
        <h1>「買うもの」の登録</h1>
        @if (session('front.shopping_register_success') == true)
                「買うもの」を登録しました！！<br>
            @endif
        @if ($errors->any())
            <div>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            </div>
        @endif
        <form action="register" method="post">
            @csrf
            「買うもの」名:<input name="name"><br>
            <button>「買うもの」登録する</button>
        </form>
        
        <br>
        <h1>「買うもの」一覧</h1>
        <a href="">購入済み「買うもの」一覧</a>
        <table border="1">
        <tr>
            <th>登録日
            <th>「買うもの」名
        </table>
        <!-- ページネーション -->
        {{-- {{ $list->links() }} --}}
        現在ページ目<br>
        最初のページ
        /
        前に戻る
        /
        次に進む

        <br>
        <hr>
        <menu label="リンク">
        <a href="/logout">ログアウト</a><br>
        </menu>

@endsection