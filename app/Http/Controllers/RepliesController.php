<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(ReplyRequest $request, Reply $reply)
    {
        $reply->content = $request->get('content');
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->topic_id;
        $res = $reply->save();
        if($res){
            return redirect()->to($reply->topic->link())->with('success', '评论创建成功！');
        } else {
            return redirect()->to($reply->topic->link())->with('warning','评论失败,可能存在非法字符。')->withInput($request->only(['content']));
        }
    }

	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);
		$reply->delete();

        return redirect()->to($reply->topic->link())->with('success', '评论删除成功！');
    }
}