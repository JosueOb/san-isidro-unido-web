<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\VarDumper;

class GraphicController extends Controller
{
    public function __construct()
    {
    }

    public function api_social_problems()
    {
        //Se obtiene la categoría de problemas sociales
        $social_problem_category = Category::where('slug', 'problema')->first();
        $social_problem_subcategories = $social_problem_category->subcategories;
        $social_problem_graphic = collect();

        foreach ($social_problem_subcategories as $subcategory) {

            $count_posts = $subcategory->posts()
                ->where('additional_data->status_attendance', 'aprobado')->count();

            // $social_problem_graphic[$subcategory->name] = $count_posts;
            $social_problem_graphic->put($subcategory->name, $count_posts);
        }

        return $social_problem_graphic->toArray();
    }

    public function social_problems()
    {
        return view('graphics.socialProblems');
    }

    public function api_emergencies()
    {
        $emergency_category = Category::where('slug', 'emergencia')->first();
        $posts = Post::select('id', 'title', 'created_at')
            ->where('category_id', $emergency_category->id)
            ->whereNotIn('additional_data->status_attendance', ['pendiente'])
            ->latest()
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d'); //agrupando por la fecha
            });
        
        $emergencies = collect();
        foreach ($posts as $date => $value) {
            // dd($date, count($value));
            $emergencies->put($date, count($value));
        }

        return $emergencies->toArray();
    }

    public function emergencies()
    {
        return view('graphics.emergencies');
    }
}
