<?php

namespace Tests\Feature;

use App\Models\Topic;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\ActingJWTUser;

class TopicApiTest extends TestCase
{
   use RefreshDatabase;
   use ActingJWTUser;

   protected $user;

   protected function setUp(): void
   {
       parent::setUp();

       $this->user = factory(User::class)->create();
   }

    protected function makeTopic()
    {
        return factory(Topic::class)->create([
            'category_id' => 1,
            'user_id' => $this->user->id
        ]);
    }

   public function testStoreTopic()
   {
       $data = ['category_id' => 1,'body' => 'test body','title' => 'test title'];

//       $token = auth('api')->fromUser($this->user);
//       $response = $this->withHeaders(['Authorization' => 'Bearer'.$token])
//           ->json('POST','/api/v1/topics',$data);
       $response = $this->JWTActingAs($this->user)
           ->json('POST', '/api/v1/topics', $data);

       $assertData = [
           'category_id' => 1,
           'user_id' => $this->user->id,
           'title' => 'test title',
           'body' => clean('test body', 'user_topic_body'),
       ];

       $response->assertStatus(201)
           ->assertJsonFragment($assertData);

   }

   public function testUpdateTopic()
   {
       $topic = $this->makeTopic();

       $editData = ['category_id' => 2, 'body' => 'edit body', 'title' => 'edit title'];

       $response = $this->JWTActingAs($this->user)
           ->json('PATCH','/api/v1/topics/'.$topic->id,$editData);

       $assertData= [
           'category_id' => 2,
           'user_id' => $this->user->id,
           'title' => 'edit title',
           'body' => clean('edit body', 'user_topic_body'),
       ];

       $response->assertStatus(200)
           ->assertJsonFragment($assertData);

   }

   public function testShowTopic()
   {
       $topic = $this->makeTopic();

       $response = $this->json('GET','/api/v1/topics/'.$topic->id);

       $assertData= [
           'category_id' => $topic->category_id,
           'user_id' => $topic->user_id,
           'title' => $topic->title,
           'body' => $topic->body,
       ];

       // 断言响应状态码为 200 以及响应数据与刚才创建的话题数据一致
       $response->assertStatus(200)
           ->assertJsonFragment($assertData);
   }

   public function testIndexTopic()
   {
       $response = $this->json('GET','/api/v1/topics');

       // 断言响应数据结构中有 data 和 meta
       $response->assertStatus(200)
           ->assertJsonStructure(['data','meta']);
   }


   public function testDeleteTopic()
   {
       // 首先通过 makeTopic 创建一个话题，然后通过 DELETE 方法调用 删除话题 接口，将话题删除，断言响应状态码为 204。
       // 接着请求话题详情接口，断言响应状态码为 404，因为该话题已经被删除了，所以会得到 404。

       $topic = $this->makeTopic();
       $response = $this->JWTActingAs($this->user)
           ->json('DELETE', '/api/v1/topics/'.$topic->id);
       $response->assertStatus(204);

       $response = $this->json('GET', '/api/v1/topics/'.$topic->id);
       $response->assertStatus(404);
   }

}
