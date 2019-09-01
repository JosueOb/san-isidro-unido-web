<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Position;
use App\User;
use Illuminate\Database\Eloquent\Builder;

class SearchController extends Controller
{
    public function search(SearchRequest $request){

        $validated = $request->validated();

        $option = $validated['searchOption'];
        $search = $validated['searchValue'];

        $members = User::whereHas('roles', function (Builder $query) {
            $query->whereIn('name', ['Directivo', 'Directiva']);
        });
        
        $membersFound = null;

        switch ($option) {
            case 1:
                //Se busca acorde al nombre ingresado
                $membersFound = $members->where('first_name','LIKE', "%$search%")->paginate();
                break;
            case 2:
                //Se busca acorde al apellido ingresado
                $membersFound = $members->where('last_name','LIKE', "%$search%")->paginate();
                break;
            case 3:
                //Se busca acorde al cargo ingresado
                $membersFound = $members->whereHas('position', function (Builder $query) use($search) {
                    $query->where('name', 'LIKE', "$search%");
                })->paginate();
                break;
            
            default:
                return abort(404);
                break;
        }

        return view('directive.index',[
            'members'=>$membersFound,
        ]);
    }
}
