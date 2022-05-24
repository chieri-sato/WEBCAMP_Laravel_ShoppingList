<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginPostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * トップページ を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('user/register');
    }
    
    /**
     * 会員登録をする
     * 
     * @return \Illuminate\View\View
     */
    public function register(UserRegisterRequest $request)
    {
        // validate済

        // データの取得
        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

// テーブルへのINSERT
        try {
            $r = UserModel::create($validatedData);
            
//var_dump($r); exit;
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
            exit;
        }
        
        // 会員登録成功
        $request->session()->flash('front.user_register_success', true);
        
        return redirect('/');
    }
    

}