<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Completed_shopping_list as Completed_shopping_listModel;

class CompletedShoppingListController extends Controller
{
    /**
     * 購入済み「買うもの」一覧を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function list()
    {
        // 1Page辺りの表示アイテム数を設定
        $per_page = 3;
        
        //一覧の取得
        $list = Completed_shopping_listModel::orderBy('name','ASC')
                                 ->paginate($per_page);
        
        return view('completed_shopping_list/list',['list' => $list]);
    }
}