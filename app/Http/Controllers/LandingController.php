<?php

namespace App\Http\Controllers;

use App\Category;
use App\Position;
use App\Post;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class LandingController extends Controller
{
    public function index(){

        //DIRECTORIO DE LA DIRECTIVA
        $directive_role = Role::where('slug', 'directivo')->first();
        $vocal_position = Position::where('name', 'Vocal')->first();
        $directive_members = $directive_role->users;
        //Se filtra de la colección los miembros de la directiva que se encuentren activos y no tengan el cargo de vocal
        $directive_members_active = $directive_members->filter(function($member, $key) use($directive_role, $vocal_position) {
            return $member->getRelationshipStateRolesUsers($directive_role->slug) and $member->position_id !== $vocal_position->id;
        });

        //NOTICIAS-REPORTE DE ACTIVIDADES
        $report_category = Category::where('slug', 'informe')->first();

        $news = Post::where('category_id', $report_category->id)
                                ->where('state', true)
                                ->latest()
                                ->take(10)
                                ->get();

        //EVENTOS
        $event_category = Category::where('slug', 'evento')->first();

        $events = Post::where('category_id', $event_category->id)
                            ->where('state', true)
                            ->latest()
                            ->take(10)
                            ->get();
        
        return view('landing',[
            'directiveMembers'=>$directive_members_active,
            'news'=>$news,
            'events'=>$events,
        ]);
    }
}
