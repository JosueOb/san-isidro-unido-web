<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Middleware\OnlyEmergencies;
use App\Post;
use App\User;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    public function __construct()
    {
        $this->middleware(OnlyEmergencies::class)->only('show');
    }
    public function index()
    {
        //Se obtiene todos las emergencias que hayan sido abordadas por la policía
        $emergency_category = Category::where('slug', 'emergencia')->first();
        $emergencies = $emergency_category->posts()
            ->whereNotIn('additional_data->status_attendance', ['pendiente'])
            ->paginate(10);

        return view('emergencies.index', [
            'emergencies' => $emergencies,
        ]);
    }
    public function show(Post $post)
    {
        //Se obtiene a la emgernecia, como objeto Post
        $emergency = $post;
        //Se obtiene la ubicación de la emergencia
        $ubication = $emergency->ubication;
        //Se obtiene las imagenes de la emergencia
        $images = $emergency->resources()->where('type', 'image')->get();
        //Se obtiene el estado de la emergencia
        $emergency_status_attendance = $emergency->additional_data['status_attendance'];
        //Se obtiene información del morador que reportó la emergencia, como objeto User
        $neighbor = User::findOrFail($emergency->user_id);

        return view('emergencies.show', [
            'emergency' => $emergency,
            'ubication' => $ubication,
            'images' => $images,
            'neighbor' => $neighbor,
            'emergency_status_attendance' => $emergency_status_attendance,
        ]);
    }
}
