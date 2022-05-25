<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ShoppingRegisterPostRequest;
use App\Models\Shopping_list as Shopping_listModel;
use Illuminate\Support\Facades\DB;
use App\Models\Completed_shopping_list as Completed_shopping_listModel;

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
        $per_page = 3;
        
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
        
        $datum['user_id'] = Auth::id();
        
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
    
    /**
     * 「買うもの」の完了
     */
    public function complete(Request $request,$shopping_list_id)
    {
        /* タスクを完了テーブルに移動させる */
        try {
            // トランザクション開始
            DB::beginTransaction();

            // shopping_list_idのレコードを取得する
            $shopping_list = $this->getShoppingList($shopping_list_id);
            if ($shopping_list === null) 
            {
                // shopping_list_idが不正なのでトランザクション終了
                throw new \Exception('');
            }

            // shopping_list側を削除する
            $shopping_list->delete();
//var_dump($shopping_list->toArray()); exit;

            // completed_shopping_list側にinsertする
            $dask_datum = $shopping_list->toArray();
            //var_dump($dask_datum->toArray()); exit;
            unset($dask_datum['created_at']);
            unset($dask_datum['updated_at']);
            $r = Completed_shopping_listModel::create($dask_datum);
            if ($r === null) {
                // insertで失敗したのでトランザクション終了
                throw new \Exception('');
            }
//echo '処理成功'; exit;

            // トランザクション終了
            DB::commit();
            // 完了メッセージ出力
            $request->session()->flash('front.shopping_completed_success', true);
        } catch(\Throwable $e) {
//var_dump($e->getMessage()); exit;
            // トランザクション異常終了
            DB::rollBack();
           // 完了失敗メッセージ出力
            $request->session()->flash('front.shopping_completed_failure', true);
        }

        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }
    
    /**
     * 削除処理
     */
    public function delete(Request $request,$shopping_list_id)
    {
        // shopping_list_idのレコードを取得する
        $shopping_list = $this->getShoppingList($shopping_list_id);
            
        // 「買うもの」を削除する
        if($shopping_list !== null)
        {
            $shopping_list->delete();
            $request->session()->flash('front.shopping_delete_success', true);
        }
        
        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }
    
    /**
     * shopping_list_idのレコード取得
     */
     public function getShoppingList($shopping_list_id)
     {
         //shopping_list_idのレコードを取得する
         $shopping_list = Shopping_listModel::find($shopping_list_id);
         if ($shopping_list === null) 
            {
                $shopping_list = null;
            }
            
            //本人以外
            if($shopping_list->user_id !== Auth::id())
            {
                $shopping_list = null;
            }
            
        return $shopping_list;
     }
}