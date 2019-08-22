<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit',compact('user'));
    }

//    public function update(Request $request,User $user)
//    {
//        $this->validate($request,[
//            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
//            'email' => 'required|email|unique:users,email,' . Auth::id(),
//            'introduction' => 'max:80',
//        ],
//        [
//            'name.unique' => '用户名已被占用，请重新填写',
//            'email.unique' => '该邮箱已被注册',
//            'name.regex' => '用户名只支持英文、数字、横杠和下划线。',
//            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
//            'name.required' => '用户名不能为空。',
//        ]);
//
//        $user->update($request->all());
//        return redirect()->route('users.show',$user->id)->with('success', '个人资料更新成功！');
//    }

    public function update(UserRequest $request,User $user)
    {

        $user->update($request->all());
        return redirect()->route('users.show',$user->id)->with('success', '个人资料更新成功！');
    }


}
