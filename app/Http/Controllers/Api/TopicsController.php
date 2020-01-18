<?php

namespace App\Http\Controllers\Api;

use App\Http\Queries\TopicQuery;
use App\Http\Requests\TopicsRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TopicsController extends Controller
{

    public function store(TopicsRequest $request,Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }


    public function update(TopicsRequest $request,Topic $topic)
    {
        $this->authorize('update',$topic);

        $topic->update($request->all());
        return new TopicResource($topic);
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy',$topic);

        $topic->delete();

        return response(null, 204);
    }


//    public function index(Request $request,Topic $topic){
//
////        $query = $topic->query();
////
////        if($categoryId = $request->category_id)
////        {
////            $query->where('category_id',$categoryId);
////        }
////
////        $topics = $query->withOrder($request->order)->paginate();
//
//        // allowedIncludes 方法传入可以被 include 的参数 /topics?include=user,category —— 返回话题数据、发布者的数据，以及所属的分类数据。
//        /* allowedFilters 方法传入可以被搜索的条件，可以传入某个字段，例如我们这里传入了 title，这样会模糊搜索标题；
//        如果某个字段是精确搜索需要进行指定，这里我们指定 category_id 是精确搜索的；
//        还可以传入某个 scope，并且制定默认的参数，例如这里我们指定可以使用 withOrder 进行搜索，默认的值是 recentReplied。
//        使用 filter 参数可以进行搜索，该参数是个数组。
//        例如我们搜索标题，分类以及按 recent 排序。
//        */
//
//        $beginTime = microtime(true);
//
//        $topics = QueryBuilder::for(Topic::class)
////            ->allowedIncludes('user', 'category')
//            ->allowedFilters([
//                'title',
//                AllowedFilter::exact('category_id'),
//                AllowedFilter::scope('withOrder')->default('recentReplied'),
//            ])
//            ->paginate();
//
//        $include = !empty($request->include) ? explode(',',$request->include) : [];
//
//
//        $topics->transform(function ($topic) use ($include) {
////            unset($topic->user);
////            $aa = $topic->setHidden(['user','category']);
////            dd($aa);
//            $diff = array_diff(array_keys($topic->getRelations()),$include);
//            foreach ($diff as $item){
//                $topic->unsetRelation($item);
//            }
//            return $topic;
//        });
//
//        $endTime = microtime(true);
//        \Log::info($endTime - $beginTime);
//
//
//        return TopicResource::collection($topics);
//    }
//
//
//    public function userIndex(Request $request, User $user)
//    {
//        $query = $user->topics()->getQuery();
//
//        $topics = QueryBuilder::for($query)
//            ->allowedIncludes('user', 'category')
//            ->allowedFilters([
//                'title',
//                AllowedFilter::exact('category_id'),
//                AllowedFilter::scope('withOrder')->default('recentReplied'),
//            ])
//            ->paginate();
//
//        return TopicResource::collection($topics);
//    }
//
//    public function show($topicId)
//    {
//        $topic = QueryBuilder::for(Topic::class)
//            ->allowedIncludes('user', 'category')
//            ->findOrFail($topicId);
//
//        return new TopicResource($topic);
//    }

    public function index(Request $request, TopicQuery $query)
    {
        $topics = $query->paginate();

        $include = !empty($request->include) ? explode(',',$request->include) : [];
        $topics->transform(function ($topic) use ($include) {
//            unset($topic->user);
//            $aa = $topic->setHidden(['user','category']);
//            dd($aa);
            $diff = array_diff(array_keys($topic->getRelations()),$include);
            foreach ($diff as $item){
                $topic->unsetRelation($item);
            }
            return $topic;
        });

        return TopicResource::collection($topics);
    }

    public function userIndex(Request $request, User $user, TopicQuery $query)
    {
        $topics = $query->where('user_id', $user->id)->paginate();

        return TopicResource::collection($topics);
    }

    public function show($topicId, TopicQuery $query)
    {
        $topic = $query->findOrFail($topicId);
        return new TopicResource($topic);
    }
}
