<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ShoppingRegisterPostRequest;
use App\Models\Shopping_list as Shopping_listModel;
use Illuminate\Support\Facades\DB;

class ShoppingListController extends Controller
{
    /**
     * トップページ を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function list()
    {
        // 1Page辺りの表示アイテム数を設定
        $per_page = 10;
        
        //一覧の取得
        $list = Shopping_listModel::orderBy('name','ASC')
                                 ->paginate($per_page);
        
        return view('shopping_list/list',['list' => $list]);
    }
    
    /**
     * 「買うもの」を登録する
     * 
     * @return \Illuminate\View\View
     */
    public function register(ShoppingRegisterPostRequest $request)
    {
        $datum = $request->validated();
        
         // テーブルへのINSERT
        try {
            $r = Shopping_listModel::create($datum);
    
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
            exit;
        }
        
        // タスク登録成功
        $request->session()->flash('front.shopping_register_success', true);

        //
        return redirect('shopping_list/list');
    }
}