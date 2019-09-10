<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Position;
use App\User;
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
}
