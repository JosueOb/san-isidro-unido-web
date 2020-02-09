<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ApiMobileNotificationsSeeder extends Seeder
{
    public function limit_string($cadena, $limite, $sufijo)
    {
        // Si la longitud es mayor que el límite...
        if (strlen($cadena) > $limite) {
            // Entonces corta la cadena y ponle el sufijo
            return substr($cadena, 0, $limite) . $sufijo;
        }

        // Si no, entonces devuelve la cadena normal
        return $cadena;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersMobile = User::whereHas('roles', function(Builder $query){
            $query->where('slug', 'morador')
            ->orWhere('slug', 'invitado')
            ->orWhere('slug', 'policia');
        })->select('*')->get();
        $numNoti = 7;
        foreach ($usersMobile->toArray() as $key => $user) {
            for ($i = 1; $i < $numNoti; $i++) {
                $post = Post::with(['category', 'subcategory'])->orderBy(DB::raw('RAND()'))->take(1)->first();
                $date = date("Y-m-d H:i:s");
                $state = rand(0, 1);
                $updated_at = ($state === 1) ? $date : null;
                $newNoti = [
                    'user_id' => $user['id'],
                    'title' => 'NPC: ' . $this->limit_string($post->title, 10, '...'),
                    'message' => $this->limit_string($post->description, 50, '...'),
                    'state' => $state,
                    'additional_data' => json_encode([
                        "post" => [
                            "category" => $post->category->slug,
                            "subcategory" => (!is_null($post->subcategory)) ? $post->subcategory->slug : null,
                            "id" => $post->id
                        ]
                    ]),
                    'created_at' => $date,
                    'updated_at' => $updated_at
                ];
                DB::table('mobile_notifications')->insertGetId($newNoti);
            }
        }
    }
}
