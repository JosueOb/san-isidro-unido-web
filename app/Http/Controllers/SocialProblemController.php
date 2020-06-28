<?php

namespace App\Http\Controllers;

use App\Category;
use App\HelpersClass\AdditionalData;
use App\Http\Middleware\AllowToAttendOrRejectProblemsAddressedByModerator;
use App\Http\Middleware\OnlySocialProblems;
use App\Http\Middleware\RejectSocialProblemsAddressedByDirective;
use App\Http\Requests\RejectReportRequest;
use App\Post;
use App\User;
use Illuminate\Http\Request;

class SocialProblemController extends Controller
{
    public function __construct()
    {
        $this->middleware(OnlySocialProblems::class)->only('show', 'attend', 'showReject', 'reject');
        // $this->middleware(RejectSocialProblemsAddressedByDirective::class)->only('attend', 'showReject', 'reject');
        //Se permite atender o rechazar problemas sociales abordador por el usuario moderador
        $this->middleware(AllowToAttendOrRejectProblemsAddressedByModerator::class)->only('attend', 'showReject', 'reject');
    }

    public function index()
    {
        //Se obtiene todos los problemas sociales con estado true, eso quiere decir que son públicos (aprobados por los moderadores)
        $social_problem_category = Category::where('slug', 'problema')->first();
        $social_problems = $social_problem_category->posts()
            ->whereNotIn('additional_data->status_attendance', ['pendiente'])
            ->paginate(10);

        return view('social-problems.index', [
            'socialProblems' => $social_problems,
        ]);
    }

    public function show(Post $post)
    {
        $social_problem = $post;
        //Se obtiene la ubicación del problema social
        $ubication = $social_problem->ubication;
        //Se obtiene las imagenes del problema social
        $images = $social_problem->resources()->where('type', 'image')->get();
        //Se obtiene el estado del problema social
        $social_problem_status_attendance = $social_problem->additional_data['status_attendance'];
        //Se obtiene información del morador que reportó el problema social como objeto User
        $neighbor = User::findOrFail($social_problem->user_id);

        return view('social-problems.show', [
            'social_problem' => $social_problem,
            'ubication' => $ubication,
            'images' => $images,
            'neighbor' => $neighbor,
            'social_problem_status_attendance' => $social_problem_status_attendance,
        ]);
    }
    public function attend(Post $post, Request $request)
    {
        //Se obtiene al post de problema social
        $social_problem = $post;
        //Se obtiene información del directivo que atendió al problema social
        $directive = $request->user();

        //Datos de attención del problema social
        $attention = new AdditionalData();
        $attention->setInfoSocialProblem([
            "attended" => [
                'who' => $directive,
                'date' => now()->toDateTimeString(),
            ],
            "status_attendance" => 'atendido'
        ]);

        //Se actualiza el registro del problema social, con los datos de atención
        $social_problem->additional_data = $attention->getInfoSocialProblem();
        $social_problem->save();

        return redirect()->route('socialProblems.show', [
            'post' => $social_problem->id
        ])->with('success', 'Problema social atendido exitosamente');
    }
    public function showReject(Post $post)
    {
        return view('social-problems.reject', [
            'social_problem' => $post
        ]);
    }
    public function reject(RejectReportRequest $request, Post $post){
        $validated = $request->validated();
        //Se obtiene el problema social a rechazar
        $social_problem = $post;
        //se obtiene el directivo que está rechazando el problema social
        $directive = $request->user();

        //Datos del rechazo del problem social
        $rejection = new AdditionalData();
        $rejection->setInfoSocialProblem([
            "rechazed" => [
                'who' => $directive, //usuario que rechazó el problema social
                'reason' => $validated['description'], //razón del rechazo del problema social
                'date' => now()->toDateTimeString(), //fecha de rechado
            ],
            "status_attendance" => 'rechazado'
        ]);

         //Se actualiza el registro del problema social, con los datos de rechazo
         $social_problem->additional_data = $rejection->getInfoSocialProblem();
         //Se cambia su estado, para que no sea visiable en la aplicacón móvil
         $social_problem->state = false;
         $social_problem->save();

         return redirect()->route('socialProblems.show', [
            'post' => $social_problem->id
        ])->with('success', 'Problema social rechazado exitosamente');
    }
}
