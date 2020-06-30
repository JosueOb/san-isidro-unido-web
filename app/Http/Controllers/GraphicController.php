<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class GraphicController extends Controller
{
    public function __construct()
    {
    }

    public function api_social_problems(){
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

    public function emergencies()
    {
        // dd('ingresó a gráfico de emergencias');
        return view('graphics.emergencies');
    }
}
