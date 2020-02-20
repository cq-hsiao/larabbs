<?php

namespace App\Models\Traits;

trait QueryBuilderBindable
{
    //  重写了 resolveRouteBinding 方法，如果模型定义了 queryClass 属性，那么使用这个属性指定的 Query 类，如果没有指定，则查找 Queries 目录下面对应名称的 Query 类
    public function resolveRouteBinding($value)
    {
        $queryClass = property_exists($this,'queryClass')
            ? $this->queryClass
            : '\\App\\Http\\Queries\\'.class_basename(self::class).'Query';


        if (!class_exists($queryClass)) {
            return parent::resolveRouteBinding($value);
        }

        return (new $queryClass($this))
            ->where($this->getRouteKeyName(), $value)
            ->first();

    }
}