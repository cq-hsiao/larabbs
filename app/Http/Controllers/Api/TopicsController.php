<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TopicsRequest;
use App\Http\Resources\TopicsResource;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TopicsController extends Controller
{

    public function store(TopicsRequest $request,Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicsResource($topic);
    }


    public function update(TopicsRequest $request,Topic $topic)
    {
        $this->authorize('update',$topic);

        $topic->update($request->all());
        return new TopicsResource($topic);
    }
}
