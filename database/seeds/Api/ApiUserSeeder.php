<?php

use App\User;
use App\Role;
use Illuminate\Database\Seeder;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ApiUserSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
        $faker = \Faker\Factory::create();
        $provider_options = ['facebook', 'google'];
        $roleMorador = Role::where('slug', 'morador')->first();
        $roleInvitado= Role::where('slug', 'invitado')->first();
        $rolePolicia= Role::where('slug', 'policia')->first();
		//TODO: Primer Usuario
		$idOne = DB::table('users')->insertGetId([
			'first_name' => 'Jose',
			'last_name' => 'Maza',
			'email' => 'jose@hotmail.com',
            'avatar' => "https://ui-avatars.com/api/?name=Jose+Maza&size=255",
            'basic_service_image' => "https://ui-avatars.com/api/?name=basicserviceimage&size=400",
            'state'=>true,
            "cedula" => "1102655386",
            'password' => password_hash('12345', PASSWORD_DEFAULT),
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        for($i = 0; $i < 2; $i++){
            DB::table('social_profiles')->insert([
                'user_id' => $idOne,
                'social_id' => '487asasd8a7ddldskfkds4',
                "provider" =>  $provider_options[array_rand($provider_options)],
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        for($i = 0; $i < 2; $i++){
            DB::table('devices')->insert([
                "phone_id" => $faker->creditCardNumber,
                "phone_model" => $faker->name,
                "description" => $faker->sentence(6,true),
                'user_id' => $idOne,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        $user_one = User::findById($idOne)->first();
        $user_one->roles()->attach([$roleMorador->id],['state'=>true]);
		//TODO:Segundo Usuario
		$idTwo = DB::table('users')->insertGetId([
			'first_name' => 'Ana',
			'last_name' => 'Jimenez',
            'email' => 'ana@hotmail.com',
            'state'=>true,
			'avatar' => "https://ui-avatars.com/api/?name=Ana+Jimenez&size=255",
            'password' => password_hash('12345', PASSWORD_DEFAULT),
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        for($i = 0; $i < 2; $i++){
            DB::table('social_profiles')->insert([
                'user_id' => $idTwo,
                'social_id' => '487asasd8a7ddldskfkds4',
                "provider" =>  $provider_options[array_rand($provider_options)],
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        for($i = 0; $i < 2; $i++){
            DB::table('devices')->insert([
                "phone_id" => $faker->creditCardNumber,
                "phone_model" => $faker->name,
                "description" => $faker->sentence(6,true),
                'user_id' => $idTwo,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        $user_two = User::findById($idTwo)->first();
        $user_two->roles()->attach([$roleInvitado->id],['state'=>true]);
        //TODO:Tercer Usuario
		$idThree = DB::table('users')->insertGetId([
			'first_name' => 'Ramiro',
			'last_name' => 'Gonzales',
            'email' => 'ramiro@hotmail.com',
            'state'=>true,
			'avatar' => "https://ui-avatars.com/api/?name=Ramiro+Gonzales&size=255",
            'password' => password_hash('12345', PASSWORD_DEFAULT),
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
		]);
        
        for($i = 0; $i < 2; $i++){
            DB::table('social_profiles')->insert([
                'user_id' => $idThree,
                'social_id' => '487asasd8a7ddldskfkds4',
                "provider" =>  $provider_options[array_rand($provider_options)],
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        for($i = 0; $i < 2; $i++){
            DB::table('devices')->insert([
                "phone_id" => $faker->creditCardNumber,
                "phone_model" => $faker->name,
                "description" => $faker->sentence(6,true),
                'user_id' => $idThree,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        $user_three = User::findById($idThree)->first();
        $user_three->roles()->attach([$roleInvitado->id],['state'=>true]);
        //TODO:Cuarto Usuario
        $idFour = DB::table('users')->insertGetId([
			'first_name' => 'Bolivar',
			'last_name' => 'Cumbicus',
			'email' => 'bolo@hotmail.com',
            'avatar' => "https://ui-avatars.com/api/?name=Bolivar+Cumbicus&size=255",
            'state'=>true,
            'basic_service_image' => "https://ui-avatars.com/api/?name=basicserviceimage&size=400",
            "cedula" => "1103078059",
            'password' => password_hash('12345', PASSWORD_DEFAULT),
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        for($i = 0; $i < 2; $i++){
            DB::table('social_profiles')->insert([
                'user_id' => $idFour,
                'social_id' => '487asasd8a7ddldskfkds4',
                "provider" =>  $provider_options[array_rand($provider_options)],
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        for($i = 0; $i < 2; $i++){
            DB::table('devices')->insertGetId([
                "phone_id" => $faker->creditCardNumber,
                "phone_model" => $faker->name,
                "description" => $faker->sentence(6,true),
                'user_id' => $idFour,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        $user_four = User::findById($idFour)->first();
        $user_four->roles()->attach([$rolePolicia->id],['state'=>true]);
        //TODO:Quinto Usuario
		$idFive = DB::table('users')->insert([
			'first_name' => 'Rodrigo',
            'last_name' => 'Sanchez',
            "cedula" => "1725452609",
            'email' => 'rodrigo@yahoo.com',
            'basic_service_image' => "https://ui-avatars.com/api/?name=basicserviceimage&size=400",
            'state'=>true,
			'avatar' => "https://ui-avatars.com/api/?name=Rodrigo+Sanchez&size=255",
            'password' => password_hash('12345', PASSWORD_DEFAULT),
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        for($i = 0; $i < 2; $i++){
            DB::table('social_profiles')->insert([
                'user_id' => $idFive,
                'social_id' => '487asasd8a7ddldskfkds4',
                "provider" =>  $provider_options[array_rand($provider_options)],
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        for($i = 0; $i < 2; $i++){
            DB::table('devices')->insert([
                "phone_id" => $faker->creditCardNumber,
                "phone_model" => $faker->name,
                "description" => $faker->sentence(6,true),
                'user_id' => $idFive,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        $user_five = User::findById($idFive)->first();
        $user_five->roles()->attach([$roleMorador->id],['state'=>true]);
	}
}
