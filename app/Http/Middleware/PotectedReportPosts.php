<?php

namespace App\Http\Middleware;

use App\Category;
use Closure;

class PotectedReportPosts
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
        $category_report = Category::where('slug', 'informe')->first();
        $getPost = $request->route('post');

        if($getPost->category_id === $category_report->id){
            return abort(403, 'Acción no autorizada');
        }
        return $next($request);
    }
}
