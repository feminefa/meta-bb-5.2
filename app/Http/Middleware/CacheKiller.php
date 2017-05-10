<?php

namespace App\Http\Middleware;

use Closure;

class CacheKiller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('APP_ENV') != 'production') {

        $cachedViewsDirectory = app('path.storage') . '/framework/views/';

        if ($handle = opendir($cachedViewsDirectory)) {

            while (false !== ($entry = readdir($handle))) {
                if (substr($entry, 0, 1) != ".") {
                    @unlink($cachedViewsDirectory . $entry);
                }

                /*  if(strstr($entry, '.')) continue;
                  echo $entry;
                  echo '<br>';
                  @unlink($cachedViewsDirectory . $entry);*/
            }

            closedir($handle);
        }
        }

        return $next($request);
}
}