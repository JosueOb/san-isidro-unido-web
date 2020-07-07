<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\OnesignalNotification;
use App\Http\Middleware\OnlyActivities;
use App\Http\Requests\ReportRequest;
use App\Post;
use App\Resource;
use App\Http\Middleware\PotectedEventPosts;
use App\Notifications\PublicationReport;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Arr;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(OnlyActivities::class)->only('show', 'edit', 'update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::where('slug', 'informe')->first();
        $reports = $category->posts()->latest()->paginate(9);

        return view('reports.index', [
            'reports' => $reports,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportRequest $request)
    {
        $validated = $request->validated();

        //Se obtiene a la categoría de informe
        $category = Category::where('slug', 'informe')->first();
        //Se crea un nuevo objeto Post
        $report = new Post();
        $report->title = $validated['title'];
        $report->description = $validated['description'];
        $report->state = true;
        $report->user_id = $request->user()->id;
        $report->category_id = $category->id;
        $report->save();

        //Se guardan la imagenes del post, en caso de que se hayan selecionado
        if ($request->file('new_images')) {
            foreach ($request->file('new_images') as $image) {
                Resource::create([
                    'url' => $image->store('report_images', 's3'),
                    'post_id' => $report->id,
                    'type' => 'image',
                ]);
            }
        }
        //Se guardan los documentos del reporte de actividad
        if ($request->file('new_documents')) {
            foreach ($request->file('new_documents') as $document) {
                Resource::create([
                    'url' => $document->store('report_documents', 's3'),
                    'post_id' => $report->id,
                    'type' => 'document',
                ]);
            }
        }

        /**
         * Notificación push - database
         */
        //Se obtiene a los usuarios moradores con estado activo
        $neighbor_role = Role::where('slug', 'morador')->first();
        $neighbors = $neighbor_role->users()->wherePivot('state', true)->get();
        //Se describe el título y descipción para la notificación
        $n_title = 'Nuevo reporte de actividad registrado';
        $n_description = 'Escrito por: ' . $request->user()->getFullName();
        //Se obtiene solo a los usuarios con dispositivos registrados
        $users_devices = array();
        foreach ($neighbors as $neighbor) {
            $user_devices = OnesignalNotification::getUserDevices($neighbor->id);
            if (!is_null($user_devices) && count($user_devices) > 0) {
                array_push($users_devices, $user_devices);
                // Se registra una noficitación en la bdd
                $neighbor->notify(new PublicationReport(
                    'activity_reported', //tipo de la notificación
                    $n_title, //título de la notificación
                    $n_description, //descripcción de la notificación
                    $report, // post que almacena la notificación
                    $request->user() //directivo que reportó la actividad
                ));
            }
        }
        //Se envía la notificación push a los usuario con dispositivos registrados
        $users_devices = Arr::collapse($users_devices);//se convierte un array de multinivel a un solo nivel
        OnesignalNotification::sendNotificationByPlayersID(
            $n_title,
            $n_description,
            ["post" => [
                'id' => $report->id,
                'category_slug' => $report->category->slug,
            ]],
            $users_devices
        );

        session()->flash('success', 'Informe registrado con éxito');
        return response()->json(['success' => 'Datos recibidos correctamente', 'data' => $validated]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        // $images= $report->images()->get();
        $images = $post->resources()->where('type', 'image')->get();
        $documents = $post->resources()->where('type', 'document')->get();

        return view('reports.show', [
            'report' => $post,
            'images' => $images,
            'documents' => $documents,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // $images = $report->images()->get();
        $images = $post->resources()->where('type', 'image')->get();
        $documents = $post->resources()->where('type', 'document')->get();
        return view('reports.edit', [
            'report' => $post,
            'images' => $images,
            'documents' => $documents,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReportRequest $request, Post $post)
    {
        $validated = $request->validated();

        //Se actualiza al reporte
        $post->title = $validated['title'];
        $post->description = $validated['description'];
        //Se mantiene el id del usuario que publicó el informe
        $post->save();

        //Se verifica si alguna imagen del reporte registrado anteriormenete, haya sido eliminada
        $oldReportImages = $request['old_images'];
        $oldCollectionReportImages = $post->resources()->where('type', 'image')->get();

        if ($oldReportImages) {
            foreach ($oldCollectionReportImages as $oldImageReport) {
                $oldImageUrl = $oldImageReport->url;

                if ($this->searchDeletedImages($oldImageUrl, $oldReportImages)) {
                    //Eliminar a la imagen de la bdd y del local storage
                    $post->resources()->where('type', 'image')
                        ->where('url', $oldImageUrl)->delete();
                    if (Storage::disk('s3')->exists($oldImageUrl)) {
                        Storage::disk('s3')->delete($oldImageUrl);
                    }
                }
            }
        } else {
            //En caso no recibir el arreglo de las imagenes registradas con el reporte,
            //se verifica si el reporte contiene imágenes
            if (count($oldCollectionReportImages) > 0) {
                //Si el reporte contiene imágenes, se procede a eliminar todas las imágenes
                foreach ($oldCollectionReportImages as $oldImage) {
                    $oldImageUrl = $oldImage->url;
                    if (Storage::disk('s3')->exists($oldImageUrl)) {
                        Storage::disk('s3')->delete($oldImageUrl);
                    }
                }
                $post->resources()->where('type', 'image')->delete();
            }
        }

        if ($request->file('new_images')) {
            foreach ($request->file('new_images') as $image) {

                Resource::create([
                    'url' => $image->store('report_images', 's3'),
                    'post_id' => $post->id,
                    'type' => 'image',
                ]);
            }
        }

        //Se verifica si algún documento del reporte registrado anteriormenete, haya sido eliminado
        $oldReportDocuments = $request['old_documents'];
        $oldCollectionReportDocuments = $post->resources()->where('type', 'document')->get();

        if ($oldReportDocuments) {
            foreach ($oldCollectionReportDocuments as $oldDocumentReport) {
                $oldDocumentUrl = $oldDocumentReport->url;

                if ($this->searchDeletedDocuments($oldDocumentUrl, $oldReportDocuments)) {
                    //Eliminar al documento de la bdd y del storage
                    $post->resources()->where('type', 'document')
                        ->where('url', $oldDocumentUrl)->delete();
                    if (Storage::disk('s3')->exists($oldDocumentUrl)) {
                        Storage::disk('s3')->delete($oldDocumentUrl);
                    }
                }
            }
        } else {
            //En caso no recibir el arreglo de los documentos registradas con el reporte,
            //se verifica si el reporte contiene documentos
            if (count($oldCollectionReportDocuments) > 0) {
                //Si el reporte contiene documentos, se procede a eliminar todas los documentos
                foreach ($oldCollectionReportDocuments as $oldDocument) {
                    $oldDocumentUrl = $oldDocument->url;
                    if (Storage::disk('s3')->exists($oldDocumentUrl)) {
                        Storage::disk('s3')->delete($oldDocumentUrl);
                    }
                }
                $post->resources()->where('type', 'document')->delete();
            }
        }
        if ($request->file('new_documents')) {
            foreach ($request->file('new_documents') as $document) {

                Resource::create([
                    'url' => $document->store('report_documents', 's3'),
                    'post_id' => $post->id,
                    'type' => 'document',
                ]);
            }
        }

        session()->flash('success', 'Informe actualizado con éxito');

        return response()->json([
            'success' => 'Reporte actualizado con exito',
            'redirect' => route('reports.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $message = '';
        if ($post->state) {
            $post->state = false;
            $message = 'desactivado';
        } else {
            $post->state = true;
            $message = 'activado';
        }
        $post->save();

        return back()->with('success', "Informe $message con éxito");
    }
    /**
     * Check if any images were deleted
     *
     * @param  string  $search
     * @param  array  $array
     * @return boolean $imageIsDeleted
     */

    public function searchDeletedImages($search, $array)
    {
        $imageIsDeleted = true;
        foreach ($array as $image) {
            if ($image === $search) {
                $imageIsDeleted = false;
            }
        }
        return $imageIsDeleted;
    }
    /**
     * Check if any documents were deleted
     *
     * @param  string  $search
     * @param  array  $array
     * @return boolean $imageIsDeleted
     */

    public function searchDeletedDocuments($search, $array)
    {
        $documentIsDeleted = true;
        foreach ($array as $document) {
            if ($document === $search) {
                $documentIsDeleted = false;
            }
        }
        return $documentIsDeleted;
    }
}
