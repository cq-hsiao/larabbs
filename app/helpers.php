<?php

/**
 * @return mixed DIV-class命名函数
 */
function route_class(){
    return str_replace('.','-',Route::currentRouteName());
}

/**
 * @param $category_id
 * @return string  增加 active样式
 */
function category_nav_active($category_id)
{
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}

function make_excerpt($value,$length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt, $length);
}