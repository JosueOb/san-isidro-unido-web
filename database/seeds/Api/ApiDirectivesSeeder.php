<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Position;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class ApiDirectivesSeeder extends Seeder
{
    public function cleanSpaces($cadena)
    {
        $cadena = str_replace(' ', '', $cadena);
        return $cadena;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new Faker\Provider\pt_BR\PhoneNumber($faker) );
        $positionsAvalaible = [];
        $positionsFounded = false;
        $numUsuarios = 4;
        while (!$positionsFounded) {
            $position = Position::orderBy(DB::raw('RAND()'))->take(1)->first();
            if ($position->allocation === 'one-person') {
                if (!in_array($position->id, $positionsAvalaible)) {
                    array_push($positionsAvalaible, $position->id);
                }
            } else {
                array_push($positionsAvalaible, $position->id);
            }
            if (count($positionsAvalaible) >= $numUsuarios) {
                $positionsFounded = true;
            }
        }

        for ($indice = 0; $indice < $numUsuarios; $indice++) {
            $posRamdom = rand(0, count($positionsAvalaible)-1);
            $roleDirectivo = Role::where('slug', 'directivo')->first();
            $name = $faker->firstName;
            $email = $this->cleanSpaces(strtolower($name)) . 'gmail.com';
            $lastname = $faker->lastName;
            $posID = $positionsAvalaible[$posRamdom];
            $phone = $faker->cellphone(false);
            
            $newUser = [
                'first_name' => $name,
                'last_name' => $lastname,
                'email' => $email,
                'avatar' => "https://ui-avatars.com/api/?name=$name+$lastname&size=255",
                'password' => password_hash('12345', PASSWORD_DEFAULT),
                'position_id' => $posID,
                "state" => true,
                'number_phone' => $phone,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ];
            //Crear usuario
            // array_push($test, $newUser);
           
            $idUser = DB::table('users')->insertGetId($newUser);
            $user = User::findById($idUser)->first();
            if(!is_null($user)){
                $user->roles()->attach([$roleDirectivo->id], ['state' => true]);
            }
        }
    }
}
