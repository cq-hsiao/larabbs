<?php

namespace App\Observers;

use App\Models\Link;
use Illuminate\Support\Facades\Cache;

/**
 * 自动缓存更新监视器
 * Class LinkObserver
 * @package App\Observers
 */
class LinkObserver
{
    // 在保存时清空 cache_key 对应的缓存
    public function saved(Link $link)
    {
        Cache::forget($link->cache_key);
    }
}

