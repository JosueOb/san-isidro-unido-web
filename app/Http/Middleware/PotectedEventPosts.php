<?php

namespace App\Http\Middleware;

use App\Category;
use Closure;

class PotectedEventPosts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $category_event = Category::where('slug', 'evento')->first();
        $getPost = $request->route('post');

        if($getPost->category_id === $category_event->id){
            return abort(403, 'Acción no autorizada');
        }
        return $next($request);
    }
}
