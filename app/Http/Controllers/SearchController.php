<?php

namespace App\Http\Controllers;

use App\Category;
use App\Subcategory;
use App\Http\Requests\QueryRequest;
use App\PublicService;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function subcategories(QueryRequest $request)
    {
        $validated = $request->validated();
        $option = $request->has('searchOption') ? $validated['searchOption'] : null;
        $value = $request->has('searchValue') ? $validated['searchValue'] : null;
        $filter = $request->query('filterOption'); //obtiene la variable enviado en la petición GET
        $subcategories = null;

        if ($option && $value) {
            switch ($option) {
                case 1:
                    //Se busca en base al nombre
                    $subcategories = Subcategory::where('name', 'LIKE', "$value%");
                    break;
                default:
                    return abort(404);
                    break;
            }
        }

        if ($filter) {
            switch ($filter) {

                case 1:
                    //  Se filtrar las subcategorías de servicios públicos
                    $category_slug = 'servicio-publico';
                    break;
                case 2:
                    $category_slug = 'evento';
                    break;
                case 3:
                    $category_slug = 'problema';
                    break;

                default:
                    return abort(404);
                    break;
            }

            $category = Category::where('slug', $category_slug)->first();

            if ($subcategories) {
                $subcategories = $subcategories->where('category_id', $category->id)->orderBy('name', 'asc')->paginate(10);
            } else {
                $subcategories = Subcategory::where('category_id', $category->id)->orderBy('name', 'asc')->paginate(10);
            }
        } else {
            $subcategories = $subcategories->orderBy('name', 'asc')->paginate(10);
        }

        return view('subcategories.index', [
            'subcategories' => $subcategories,
        ]);
    }

    //Se realiza lá busqueda y filtrado de miembros de la directiva
    public function members(QueryRequest $request)
    {
        $validated = $request->validated();
        $option = $request->has('searchOption') ? $validated['searchOption'] : null;
        $value = $request->has('searchValue') ? $validated['searchValue'] : null;
        $filter = $request->query('filterOption'); //obtiene la variable enviado en la petición GET

        $rol_directive = Role::where('slug', 'directivo')->first();
        $members = $rol_directive->users();
        $members_found = null;

        if ($option && $value) {
            switch ($option) {
                case 1:
                    //Se busca acorde al nombre ingresado
                    $members_found = $members->where('first_name', 'LIKE', "$value%");
                    break;
                case 2:
                    //Se busca acorde al apellido ingresado
                    $members_found = $members->where('last_name', 'LIKE', "$value%");
                    break;
                case 3:
                    //Se busca acorde el cargo seleccionado
                    $members_found = $members->whereHas('position', function (Builder $query) use ($value) {
                        $query->where('name', 'LIKE', "$value%");
                    });
                    break;
                default:
                    return abort(404);
                    break;
            }
        }

        if ($filter) {
            switch ($filter) {
                case 1:
                    $state = true;
                    break;
                case 2:
                    $state = false;
                    break;

                default:
                    return abort(404);
                    break;
            }

            if ($members_found) {
                $members_found = $members_found->wherePivot('state', $state)->orderBy('last_name', 'asc')->paginate(10);
            } else {
                $members_found = $members->wherePivot('state', $state)->orderBy('last_name', 'asc')->paginate(10);
            }
        } else {
            $members_found = $members_found->orderBy('last_name', 'asc')->paginate(10);
        }

        return view('directive.index', [
            'members' => $members_found,
        ]);
    }

    public function neighbors(QueryRequest $request)
    {
        $validated = $request->validated();
        $option = $request->has('searchOption') ? $validated['searchOption'] : null;
        $value = $request->has('searchValue') ? $validated['searchValue'] : null;
        $filter = $request->query('filterOption'); //obtiene la variable enviado en la petición GET

        $neighbor_role = Role::where('slug', 'morador')->first();
        $neighbors = $neighbor_role->users()->whereDoesntHave('roles', function (Builder $query) {
            $query->where('slug', 'admin');
        });
        $neighbors_found = null;

        if ($option && $value) {
            switch ($option) {
                case 1:
                    //Se busca acorde al nombre ingresado
                    $neighbors_found = $neighbors->where('first_name', 'LIKE', "$value%");
                    break;
                case 2:
                    //Se busca acorde al apellido ingresado
                    $neighbors_found = $neighbors->where('last_name', 'LIKE', "$value%");
                    break;
                default:
                    return abort(404);
                    break;
            }
        }

        if ($filter) {
            switch ($filter) {
                case 1:
                    $state = true;
                    break;
                case 2:
                    $state = false;
                    break;

                default:
                    return abort(404);
                    break;
            }

            if ($neighbors_found) {
                $neighbors_found = $neighbors_found->wherePivot('state', $state)->orderBy('last_name', 'asc')->paginate(10);
            } else {
                $neighbors_found = $neighbors->wherePivot('state', $state)->orderBy('last_name', 'asc')->paginate(10);
            }
        } else {
            $neighbors_found = $neighbors_found->orderBy('last_name', 'asc')->paginate(10);
        }

        return view('neighbors.index', [
            'neighbors' => $neighbors_found,
        ]);
    }

    public function reports(QueryRequest $request)
    {
        $validated = $request->validated();
        $option = $request->has('searchOption') ? $validated['searchOption'] : null;
        $value = $request->has('searchValue') ? $validated['searchValue'] : null;
        $filter = $request->query('filterOption'); //obtiene la variable enviado en la petición GET

        $report_category = Category::where('slug', 'informe')->first();
        $reports = $report_category->posts();
        $report_found = null;

        if ($option && $value) {
            switch ($option) {
                case 1:
                    //Se busca acorde al título
                    $report_found = $reports->where('title', 'LIKE', "%$value%");
                    break;
                case 2:
                    //Se buscas con respecto al autor
                    $autors = User::whereIn('first_name', explode(' ', $value))
                        ->orWhereIn('last_name', explode(' ', $value))
                        ->get();

                    $users_id = array();
                    foreach ($autors as $autor) {
                        array_push($users_id, $autor->id);
                    }

                    $report_found = $reports->whereIn('user_id', $users_id);
                    break;

                default:
                    return abort(404);
                    break;
            }
        }

        if ($filter) {
            switch ($filter) {
                case 1:
                    $state = true;
                    break;
                case 2:
                    $state = false;
                    break;
                default:
                    return abort(404);
                    break;
            }

            if ($report_found) {
                $report_found = $report_found->where('state', $state)->latest()->paginate(9);
            } else {
                $report_found = $reports->where('state', $state)->latest()->paginate(9);
            }
        } else {
            $report_found = $report_found->latest()->paginate(9);
        }

        return view('reports.index', [
            'reports' => $report_found,
        ]);
    }

    public function publicServices(QueryRequest $request)
    {
        $validated = $request->validated();
        $option = $request->has('searchOption') ? $validated['searchOption'] : null;
        $value = $request->has('searchValue') ? $validated['searchValue'] : null;
        $filter = $request->query('filterOption'); //obtiene la variable enviado en la petición GET

        // $publicServices = PublicService::all();//no devuelve un objeto Illuminate\Database\Query\Builder pra construir la consulta
        // $publicServices = DB::table('public_services');
        $publicServices_found = null;

        if ($option && $value) {
            switch ($option) {
                case 1:
                    //Se busca acorde al nombre ingresado
                    $publicServices_found = PublicService::where('name', 'LIKE', "$value%");

                    break;
                case 2:
                    //Se busca acorde la subcategoría seleccionada
                    $publicService_category = Category::where('slug', 'servicio-publico')->first();
                    //Se realiza la búsqueda solo de las subcategoría de servicio público
                    $subcategories = $publicService_category->subcategories()->where('name', 'LIKE', "$value%")->get();

                    $subcategories_id = array();
                    foreach ($subcategories as $subcategory) {
                        array_push($subcategories_id, $subcategory->id);
                    }

                    $publicServices_found = PublicService::whereIn('subcategory_id', $subcategories_id);

                    break;
                default:
                    return abort(404);
                    break;
            }
        }

        if ($filter) {
            #código para un posible filtro de servicios públicos
            // return abort(404);
        } else {
            $publicServices_found = $publicServices_found->orderBy('name', 'asc')->paginate(10);
        }

        return view('public-services.index', [
            'publicServices' => $publicServices_found,
        ]);
    }

    public function events(QueryRequest $request)
    {
        $validated = $request->validated();
        $option = $request->has('searchOption') ? $validated['searchOption'] : null;
        $value = $request->has('searchValue') ? $validated['searchValue'] : null;
        $filter = $request->query('filterOption'); //obtiene la variable enviado en la petición GET

        $event_category = Category::where('slug', 'evento')->first();
        $events = $event_category->posts(); //se obtiene los eventos registrados
        $events_found = null;

        if ($option && $value) {
            switch ($option) {
                case 1:
                    //Se busca acorde al título
                    $events_found = $events->where('title', 'LIKE', "%$value%");
                    break;
                case 2:
                    //Se realiza la búsqueda solo de las subcategorias de eventos
                    $subcategories = $event_category->subcategories()->where('name', 'LIKE', "$value%")->get();

                    $subcategories_id = array();
                    foreach ($subcategories as $subcategory) {
                        array_push($subcategories_id, $subcategory->id);
                    }

                    $events_found = $events->whereIn('subcategory_id', $subcategories_id);
                    break;

                default:
                    return abort(404);
                    break;
            }
        }

        if ($filter) {
            switch ($filter) {
                case 1:
                    $state = true;
                    break;
                case 2:
                    $state = false;
                    break;
                default:
                    return abort(404);
                    break;
            }

            if ($events_found) {
                $events_found = $events_found->where('state', $state)->latest()->paginate(10);
            } else {
                $events_found = $events->where('state', $state)->latest()->paginate(10);
            }
        } else {
            $events_found = $events_found->latest()->paginate(10);
        }

        return view('events.index', [
            'events' => $events_found,
        ]);
    }

    public function policemen(QueryRequest $request)
    {
        $validated = $request->validated();
        $option = $request->has('searchOption') ? $validated['searchOption'] : null;
        $value = $request->has('searchValue') ? $validated['searchValue'] : null;
        $filter = $request->query('filterOption'); //obtiene la variable enviado en la petición GET

        $police_role = Role::where('slug', 'policia')->first();
        $policemen = $police_role->users();
        $policemen_found = null;

        if ($option && $value) {
            switch ($option) {
                case 1:
                    //Se busca acorde al nombre ingresado
                    $policemen_found = $policemen->where('first_name', 'LIKE', "$value%");
                    break;
                case 2:
                    //Se busca acorde al apellido ingresado
                    $policemen_found = $policemen->where('last_name', 'LIKE', "$value%");
                    break;
                default:
                    return abort(404);
                    break;
            }
        }

        if ($filter) {
            switch ($filter) {
                case 1:
                    $state = true;
                    break;
                case 2:
                    $state = false;
                    break;

                default:
                    return abort(404);
                    break;
            }

            if ($policemen_found) {
                $policemen_found = $policemen_found->wherePivot('state', $state)->orderBy('last_name', 'asc')->paginate(10);
            } else {
                $policemen_found = $policemen->wherePivot('state', $state)->orderBy('last_name', 'asc')->paginate(10);
            }
        } else {
            $policemen_found = $policemen_found->orderBy('last_name', 'asc')->paginate(10);
        }

        return view('policemen.index', [
            'policemen' => $policemen_found,
        ]);
    }

    public function assign(QueryRequest $request)
    {
        $validated = $request->validated();
        $option = $request->has('searchOption') ? $validated['searchOption'] : null;
        $value = $request->has('searchValue') ? $validated['searchValue'] : null;
        $filter = $request->query('filterOption'); //obtiene la variable enviado en la petición GET

        $neighbor_role = Role::where('slug', 'morador')->first();
        // $neighbors = $neighbor_role->users()->whereDoesntHave('roles', function (Builder $query) {
        //     $query->where('slug', 'admin');
        // });
        $neighbors = $neighbor_role->users()->whereDoesntHave('roles', function (Builder $query) {
            //Se evita que se listen a los regitros de administrador, directivo y moderadores asignados
            $query->whereIn('slug', ['admin', 'directivo', 'moderador']);
        });
        // ->wherePivot('state', true)
        $neighbors_found = null;

        if ($option && $value) {
            switch ($option) {
                case 1:
                    //Se busca acorde al nombre ingresado
                    $neighbors_found = $neighbors->where('first_name', 'LIKE', "$value%");
                    break;
                case 2:
                    //Se busca acorde al apellido ingresado
                    $neighbors_found = $neighbors->where('last_name', 'LIKE', "$value%");
                    break;
                default:
                    return abort(404);
                    break;
            }
        }

        if ($filter) {
            switch ($filter) {
                case 1:
                    $state = true;
                    break;
                case 2:
                    $state = false;
                    break;

                default:
                    return abort(404);
                    break;
            }

            if ($neighbors_found) {
                $neighbors_found = $neighbors_found->wherePivot('state', $state)->orderBy('last_name', 'asc')->paginate(10);
            } else {
                $neighbors_found = $neighbors->wherePivot('state', $state)->orderBy('last_name', 'asc')->paginate(10);
            }
        } else {
            $neighbors_found = $neighbors_found->orderBy('last_name', 'asc')->paginate(10);
        }

        return view('moderators.assign', [
            'neighbors' => $neighbors_found,
        ]);
    }

    public function moderators(QueryRequest $request)
    {
        $validated = $request->validated();
        $option = $request->has('searchOption') ? $validated['searchOption'] : null;
        $value = $request->has('searchValue') ? $validated['searchValue'] : null;
        $filter = $request->query('filterOption'); //obtiene la variable enviado en la petición GET

        $rol_moderator = Role::where('slug', 'moderador')->first();
        $moderators = $rol_moderator->users();
        $moderators_found = null;

        if ($option && $value) {
            switch ($option) {
                case 1:
                    //Se busca acorde al nombre ingresado
                    $moderators_found = $moderators->where('first_name', 'LIKE', "$value%");
                    break;
                case 2:
                    //Se busca acorde al apellido ingresado
                    $moderators_found = $moderators->where('last_name', 'LIKE', "$value%");
                    break;
                default:
                    return abort(404);
                    break;
            }
        }

        if ($filter) {
            switch ($filter) {
                case 1:
                    $state = true;
                    break;
                case 2:
                    $state = false;
                    break;

                default:
                    return abort(404);
                    break;
            }

            if ($moderators_found) {
                $moderators_found = $moderators_found->wherePivot('state', $state)->orderBy('last_name', 'asc')->paginate(10);
            } else {
                $moderators_found = $moderators->wherePivot('state', $state)->orderBy('last_name', 'asc')->paginate(10);
            }
        } else {
            $moderators_found = $moderators_found->orderBy('last_name', 'asc')->paginate(10);
        }

        return view('moderators.index', [
            'moderators' => $moderators_found,
        ]);
    }

    public function socialProblems(QueryRequest $request)
    {
        $validated = $request->validated();
        $option = $request->has('searchOption') ? $validated['searchOption'] : null;
        $value = $request->has('searchValue') ? $validated['searchValue'] : null;
        $filter = $request->query('filterOption'); //obtiene la variable enviado en la petición GET

        $social_problem_category = Category::where('slug', 'problema')->first();
        $social_problems = $social_problem_category->posts()->whereNotIn('additional_data->status_attendance', ['pendiente']); //se obtiene los problemas registrados
        $social_problems_found = null;

        if ($option && $value) {
            switch ($option) {
                case 1:
                    //Se busca acorde al título
                    $social_problems_found = $social_problems->where('title', 'LIKE', "%$value%");
                    break;
                case 2:
                    //Se realiza la búsqueda solo de las subcategorias de problema
                    $subcategories = $social_problem_category->subcategories()->where('name', 'LIKE', "$value%")->get();

                    $subcategories_id = array();
                    foreach ($subcategories as $subcategory) {
                        array_push($subcategories_id, $subcategory->id);
                    }

                    $social_problems_found = $social_problems->whereIn('subcategory_id', $subcategories_id);
                    break;

                default:
                    return abort(404);
                    break;
            }
        }

        if ($filter) {
            switch ($filter) {
                case 1:
                    $status_attendance = 'aprobado';
                    break;
                case 2:
                    $status_attendance = 'atendido';
                    break;
                case 3:
                    $status_attendance = 'rechazado';
                    break;
                default:
                    return abort(404);
                    break;
            }

            if ($social_problems_found) {
                $social_problems_found = $social_problems_found->where('additional_data->status_attendance', $status_attendance)->latest()->paginate(10);
            } else {
                $social_problems_found = $social_problems->where('additional_data->status_attendance', $status_attendance)->latest()->paginate(10);
            }
        } else {
            $social_problems_found = $social_problems_found->latest()->paginate(10);
        }

        return view('social-problems.index', [
            'socialProblems' => $social_problems_found,
        ]);
    }

    public function emergencies(QueryRequest $request)
    {
        $validated = $request->validated();
        $option = $request->has('searchOption') ? $validated['searchOption'] : null;
        $value = $request->has('searchValue') ? $validated['searchValue'] : null;
        $filter = $request->query('filterOption'); //obtiene la variable enviado en la petición GET

        $emergency_category = Category::where('slug', 'problema')->first();
        //se obtiene las emergencias abordadas por la policía
        $emergencies = $emergency_category->posts()->whereNotIn('additional_data->status_attendance', ['pendiente']);
        $emergencies_found = null;

        if ($option && $value) {
            switch ($option) {
                case 1:
                    //Se busca acorde al título
                    $emergencies_found = $emergencies->where('title', 'LIKE', "%$value%");
                    break;

                default:
                    return abort(404);
                    break;
            }
        }

        if ($filter) {
            switch ($filter) {
                case 1:
                    $status_attendance = 'atendido';
                    break;
                case 2:
                    $status_attendance = 'rechazado';
                    break;
                default:
                    return abort(404);
                    break;
            }

            if ($emergencies_found) {
                $emergencies_found = $emergencies_found->where('additional_data->status_attendance', $status_attendance)->latest()->paginate(10);
            } else {
                $emergencies_found = $emergencies->where('additional_data->status_attendance', $status_attendance)->latest()->paginate(10);
            }
        } else {
            $emergencies_found = $emergencies_found->latest()->paginate(10);
        }

        return view('emergencies.index', [
            'emergencies' => $emergencies_found,
        ]);
    }
}
