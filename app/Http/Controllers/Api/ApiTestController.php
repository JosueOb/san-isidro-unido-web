<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiBaseController;
use Illuminate\Support\Facades\Storage;
use App\Role;
use Illuminate\Http\Response;
use App\User;
use App\Resource;
use App\Post;
use App\Category;
use App\PublicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use \GuzzleHttp\Exception\ClientException;
use Carbon\CarbonImmutable;
use App\Helpers\JwtAuth;
use App\Helpers\OnesignalNotification;
use App\Helpers\Utils;
use DateInterval;
use Exception;
use Illuminate\Support\Facades\DB;

// Exception

class ApiTestController extends ApiBaseController
{
    //
    public  $baseUrl;

    public function __construct(){
        $utils = new Utils();
        $this->baseUrl = $utils->getAppURL() . "/api/v1";
    }

    public function resourceLink($resource_id){
        // $urlLink = "https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/alarm.svg?";
        $urlLink = "juan-jose.jpg";
        // $urlLink = "https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/alarm.svg?sanitize=true";
        if(preg_match(
            "/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/", $urlLink
        )){
            dd($urlLink, 'is url valid');
        }else{
            dd($urlLink, 'is url invalid');
        }
        $resource = Resource::where('id', $resource_id)->first();
        if(!$resource){

            return response()->json([
                'msg' => "Resource no existe"
            ], 400);
        }
        dd($resource, $resource->getApiLink());
    }

    public function indexTest(){
        $immutable_current_date = CarbonImmutable::now();
        $numDays = 4;
        $future_date = $immutable_current_date->add(new DateInterval( "P".$numDays."D" ), $numDays);
        return response()->json([
            "immutable_current_date" => $immutable_current_date->toDateTimeString(),
            "future_date" => $future_date->toDateTimeString(),
            'date' => $future_date->toDateString(),
            'time' => $future_date->toTimeString(),
            'random' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ],200);
    }
    

    public function CheckPass()
    {
        $user = User::email('jose@hotmail.com')->rolActive()->with("social_profiles", "roles")->first();
        $passwordEquals = password_verify('ramiro1234', '$2y$10$cXPiWWDT1PlKLKAvMz4yy.QRpjtABjb68wADgluuHlHhsMj/pGsam');
        dd($passwordEquals);
    }
    public function CheckCors(Request $request)
    {
        $passwordEquals = 'LAOLSLSLSLSLS';
        dd(apache_response_headers());
    }
    public function EncriptarPass($pass_plane)
    {
        $pass_encripted = password_hash($pass_plane, PASSWORD_DEFAULT);
        $pass = ['pass_planed' => $pass_plane, 'pass_encripted' => $pass_encripted];
        dd($pass);
    }
    public function decodedToken(Request $request)
    {
        try {
            $jwtAuth = new JwtAuth();
            $token = $request->header('authorization');
            $tokenDecoced = $jwtAuth->testDecoded($token);
            return response()->json(['token' => $tokenDecoced], 200);
        } catch (Exception $e) {
            return response()->json([
                'msg' => "Error en el Servidor",
                "errors" => $e->getMessage(),
            ], 500);
        }
    }
    //Function Probar
    public function receiveImage(Request $request)
    {
        dd($request->all());
    }

    public function mapUser()
    {
        // $userFilter = $user->map(function ($value, $key) {
        $user = User::findById(5)->mobileRol()->first();

        $validate = Validator::make($user->toArray(), [
            'avatar' => 'url',
        ]);
        echo 'avatar validate' . ($validate->fails()) ? 'fallo' : 'no fallo';
        //  echo $value;
        //    return $value;
        // });
        dd($user->toArray());
    }

    public function getGuzzleRequest()
    {
        $client = new \GuzzleHttp\Client();

        // use GuzzleHttp\Exception\ClientException;

        try {
            $request = $client->get( $this->baseUrl . '/directivos');
        } catch (ClientException $e) {
            dd($e->getRequest());
        }

        $response = $request->getBody()->getContents();

        $data = json_decode($response);
        //dd(gettype($data));
        dd($data);
    }

    public function postOnesignalGuzzle()
    {
        // $post = Post::with(['category', 'subcategory'])->orderBy(DB::raw('RAND()'))->take(1)->first();
        $post = Post::whereHas('category', function (Builder $query) {
            $query->where('slug', 'reportes');
        })->first();

        $bodyUniqueNoti = [
            "data" => [
                "post" => [
                    "category" => $post->category->slug,
                    // "subcategory" => (!is_null($post->subcategory)) ? $post->subcategory->slug : null,
                    "id" => $post->id
                ]
            ],
            "contents" => [
                "es" => "El contenido pertenece al post con categoria: " . $post->category->slug,
                "en" => "The Content belongs to category: " . $post->category->slug
            ],
            "headings" => [
                "en" => "Hi Friend, i´m a notification of post id " . $post->id,
                "es" => "Hola Amigo soy una notificación del post con id: " . $post->id,
            ],
            // "include_player_ids" => ["e635009c-7705-42bd-9156-25f5127edbcd", "2f205913-9884-4911-a4a5-2ec5501cb8ab", "4aa4ff25-8369-4f9f-963e-4833abeea38b"],
            "included_segments" => ["Active Users"], //All, Active Users || Inactive Users 
        ];

        $bodyMultiNoti = [
            "included_segments" => ["All"],
            "data" => [
                "userId" => "Postman-Lolita",
            ],
            "contents" => [
                "en" => "Test Grupal Notifications APP Test Finished 4",
                "es" => "Probar notificationes grupales en la APP con pruebas finalizadas 4",
            ],
            "title" => [
                "en" => "Puengasi Notification Test Final Debug 4 ",
                "es" => "Puengasi Test Notificacion Final Debug 4",
            ],
        ];

        try {
            // $content_request = OnesignalNotification::sendOnesignalNotification($bodyUniqueNoti);
            $content_request = OnesignalNotification::sendNotificationBySegments('Hola', 'Lorem Ipsum');
            // dd($content_request);
            // dd(['title' => $post->title, 'id' => $post->id]);
            return $this->sendDebugResponse(['title' => $post->title, 'id' => $post->id, 'content' => $content_request], 200);
        } catch (Exception $e) {
            // echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return $this->sendDebugResponse([$e->getMessage()], 500);
        }
    }

    /**
     * Retorna el listado de roles de un usuario
     * @param integer $id
     *
     * @return array
     */
    public function rolesXUser($id)
    {
        try {
            $user = User::findById($id)->with(['roles'])->first();
            //Validar si el usuario existe
            if (!is_null($user)) {
                $roles = $user->roles;
                return $this->sendResponse(200, 'success', $roles);
            }
            //Si el usuario no existe enviar error
            return $this->sendError(404, 'usuario no existe', ['user' => 'usuario no existe']);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * Retorna el detalle de un Usuario
     * @param integer $id
     *
     * @return array
     */
    public function detail($id)
    {
        try {
            $user = User::findById($id)->first();
            //Validar si el usuario existe
            if (!is_null($user)) {;
                return $this->sendResponse(200, 'success', $user);
            }
            //Si no existe envio error
            return $this->sendError(404, 'usuario no existe', ['user' => 'usuario no existe']);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    public function createDevicesTest()
    {
        $faker = \Faker\Factory::create();
        $devices = [];
        for ($i = 0; $i < 12; $i++) {
            $device = [
                "created_at" => $faker->dateTime()->format('Y-m-d H:i:s'),
                "description" => $faker->sentence(6, true),
                "id" => $faker->numberBetween(100, 500),
                "phone_id" => $faker->creditCardNumber,
                "phone_model" => $faker->name,
                "updated_at" => $faker->dateTime()->format('Y-m-d H:i:s'),
                "user_id" => 3,
            ];
            array_push($devices, $device);
            // $devices[]= $faker->unique()->randomDigit;
        }
        // dd($devices);
        DB::table('devices')->insert($devices);
    }

    public function createSocialProfilesTest()
    {
        $faker = \Faker\Factory::create();
        $provider_options = ['facebook', 'google'];
        $social_profiles = [];
        for ($i = 0; $i < 12; $i++) {
            $social_profile = [
                "created_at" => $faker->dateTime()->format('Y-m-d H:i:s'),
                "id" => $faker->numberBetween(100, 500),
                "provider" => $provider_options[array_rand($provider_options)],
                "social_id" => $faker->creditCardNumber,
                "updated_at" => $faker->dateTime()->format('Y-m-d H:i:s'),
                "user_id" => 3,
            ];
            array_push($social_profiles, $social_profile);
        }
        // dd($social_profiles);
        DB::table('social_profiles')->insert($social_profiles);
    }

    public function attachRoles()
    {
        $adminRole = Role::where('slug', 'directivo')->first();
        $adminRole->permissions()->attach([4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]);
        echo "directivo permisos added correct";
    }

    public function showMaptest()
    {
        return view('map.example', []);
    }

    public function receiveMapData(Request $request)
    {
        return response()->json([
            'request' => $request->all(),
            'type' => 'map_created'
        ], 200);
    }
    public function servePDF($filename) {
        $diskImage = 'documents';
        if (!Storage::disk($diskImage)->exists($filename)) {
            return response()->json(['code' => 404, "message" => 'File Not Found']);
        }
        $pdfContent = Storage::disk($diskImage)->get($filename);
        $type = Storage::disk($diskImage)->mimeType($filename);
        return (new Response($pdfContent, 200))->header('Content-Type',[
            'Content-Type' => $type
        ]);
        //http://127.0.0.1/github/sanisidroapi/public/api/v1/pdf/mcflurry_regalo.pdf
    }

    public function getNameModel(){
        // return response()->json(['name' => User::class]);
        $publicServicesCategories = Category::findByType(PublicService::class)->get();
        return $this->sendDebugResponse($publicServicesCategories);
    }
}
