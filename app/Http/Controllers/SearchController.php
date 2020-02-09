<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\SearchRequest;
use App\Position;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function searchMembers(SearchRequest $request){

        $validated = $request->validated();

        $option = $validated['searchOption'];
        $search = $validated['searchValue'];

        // Inicializa @rownum
        DB::statement(DB::raw('SET @rownum = 0'));
        $members = User::whereHas('roles', function (Builder $query) {
            $query->whereIn('name', ['Directivo', 'Directiva']);
        });
        
        $membersFound = null;

        switch ($option) {
            case 1:
                //Se busca acorde al nombre ingresado
                $membersFound = $members->where('first_name','LIKE', "%$search%")->select('*',DB::raw('@rownum := @rownum + 1 as rownum'))->paginate();
                break;
            case 2:
                //Se busca acorde al apellido ingresado
                $membersFound = $members->where('last_name','LIKE', "%$search%")->select('*',DB::raw('@rownum := @rownum + 1 as rownum'))->paginate();
                break;
            case 3:
                //Se busca acorde al cargo ingresado
                $membersFound = $members->whereHas('position', function (Builder $query) use($search) {
                    $query->where('name', 'LIKE', "$search%");
                })->select('*',DB::raw('@rownum := @rownum + 1 as rownum'))->paginate();
                break;
            
            default:
                return abort(404);
                break;
        }

        return view('directive.index',[
            'members'=>$membersFound,
        ]);
    }
    public function searchNeighbors(SearchRequest $request){

        $validated = $request->validated();

        $option = $validated['searchOption'];
        $search = $validated['searchValue'];

        // Inicializa @rownum
        DB::statement(DB::raw('SET @rownum = 0'));

        $neighbors = User::whereHas('roles', function(Builder $query){
            $query->where('slug', 'morador');
        })->whereDoesntHave('roles', function (Builder $query) {
            $query->where('slug', 'admin');
        });

        $neighborsFound = null;

        switch ($option) {
            case 1:
                //Se busca acorde al nombre ingresado
                $neighborsFound = $neighbors->where('first_name','LIKE', "%$search%")->select('*',DB::raw('@rownum := @rownum + 1 as rownum'))->paginate();
                break;
            case 2:
                //Se busca acorde al apellido ingresado
                $neighborsFound = $neighbors->where('last_name','LIKE', "%$search%")->select('*',DB::raw('@rownum := @rownum + 1 as rownum'))->paginate();
                break;
            
            default:
                return abort(404);
                break;
        }

        return view('neighbors.index',[
            'neighbors'=>$neighborsFound,
        ]);
    }
    public function searchReports(SearchRequest $request){
        $validated = $request->validated();

        $option = $validated['searchOption'];
        $value = $validated['searchValue'];

        
        switch ($option) {
            case 1:
                //Se buscan los reportes acorde el título ingresado
                $category = Category::where('slug', 'informe')->first();
                $reports = $category->posts()->where('title', 'LIKE', "%$value%")->paginate(6);
                break;
            
            // case 2:
            //     //Se divide un string en varios string, se devuelve un arreglo de string
            //     $search = explode(' ', $value);

            //     //Se obtiene el rol de directivo
            //     $role = Role::where('slug', 'directivo')->first();
            //     //Se obtienen a los usuarios con el rol de directivo
            //     $users = $role->users;
            //     // dd($users);

            //     foreach($search as $name){
            //         $filtered = $users->where('first_name', 'LIKE', "%$name%");
            //     }

            //     dd($filtered->all());

            //     // dd($test);
            //     // $test = $users->where('first_name', 'LIKE', "%$value%")->get();
            //     // $users = $role->users()->where('first_name', 'LIKE', "%$value%")
            //     //                        ->orWhere('last_name', 'LIKE', "%$value%")->get();
            //     // $users = User::where('first_name', 'like', "%$value%")
            //     //             ->orwhere('last_name', 'like', "%$value%")->get();

            //     dd($users);
            //     break;
            
            default:
                return abort(404);
                break;
        }

        return view('reports.index',[
            'reports'=>$reports,
        ]);
    }
}
