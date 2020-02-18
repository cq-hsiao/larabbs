<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    // 默认是 false，也就是默认将 phone 和 email 字段隐藏。
    protected $showSensitiveFields = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(!$this->showSensitiveFields){
            $this->resource->addHidden(['phone','email']);
        }
        $data =  parent::toArray($request);

        $data['bound_phone'] = $this->resource->phone ? true : false;
        $data['bound_wechat'] = ($this->resource->weixin_unionid || $this->resource->weixin_openid) ? true : false;
        $data['roles'] = new RoleResource($this->whenloaded('roles'));

        return $data;
    }

    public function showSensitiveFields()
    {
        $this->showSensitiveFields = true;
        return $this;
    }
}
