<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GraphicController extends Controller
{
    public function __construct()
    {
        
    }

    public function socialProblems(){
        // dd('ingresó a gráfico de problemas sociales');
        return view('graphics.socialProblems');
    }

    public function emergencies(){
        // dd('ingresó a gráfico de emergencias');
        return view('graphics.emergencies');
    }
}
