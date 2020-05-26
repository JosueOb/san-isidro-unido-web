<?php

use App\Category;
use App\Phone;
use App\Post;
use App\Resource;
use App\User;
use Faker\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $members = User::whereHas('roles', function(Builder $query){
            $query->where('slug','directivo');
        })->get();
        
        //Reporte de actividades
        $category_report = Category::where('slug', 'informe')->first();
        $reports = factory(Post::class, 25)
                    ->create()
                    ->each(function(Post $post) use($members, $category_report, $faker){
                        $member = $members->random();
                        $post->user_id = $member->id;
                        $post->category_id = $category_report->id;
                        $post->save();
                        for ($i=0; $i < rand(0, 2) ; $i++) { 
                            Resource::create([
                                'url'=>$faker->imageUrl($width = 640, $height = 480,'nature'),
                                'post_id'=>$post->id,
                                'type'=>'image'
                            ]);
                        }
                    });

        // Eventos
        $category_event = Category::where('slug', 'evento')->first();
        $subcategories_event = $category_event->subcategories;

        $events = factory(App\Post::class, 25)
                    ->create()
                    ->each(function(Post $post) use($members, $category_event, $subcategories_event, $faker){
                        $member = $members->random();
                        $post->user_id = $member->id;
                        $post->category_id = $category_event->id;
                        $post->subcategory_id = $subcategories_event->random()->id;

                        $additional_data = [
                            'event'=>[
                                'responsible'=> $faker->name(),
                                'range_date' => [
                                    // 'start_date' => $faker->date($startDate = '-1 years',$format = 'Y-m-d', $max = 'now'),
                                    // 'end_date' => $faker->date($startDate = '-1 years', $format = 'Y-m-d', $max = 'now'),
                                    'start_date' => '2020-05-20',
                                    'end_date' => '2020-05-26',
                                    'start_time' => $faker->time($format = 'H:i', $max = 'now'),
                                    'end_time' => $faker->time($format = 'H:i', $max = 'now'),
                                ]
                            ]
                        ];
                        $ubication = [
                            'lat'=>$faker->latitude($min = -90, $max = 90),
                            'lng'=>$faker->longitude($min = -180, $max = 180),
                            'address'=>$faker->address,
                            'description'=>$faker->text($maxNbChars = 30),
                        ];

                        $post->additional_data = json_encode($additional_data);
                        $post->ubication = json_encode($ubication);
                        $post->save();

                        for ($i=0; $i < rand(1, 3) ; $i++) {
                            $phone_number = new Phone(['phone_number' => '09'.rand(10000000, 99999999)]);
                            $post->phones()->save($phone_number);
                        }
                        for ($i=0; $i < rand(0, 2) ; $i++) { 
                            Resource::create([
                                'url'=>$faker->imageUrl($width = 640, $height = 480,'nature'),
                                'post_id'=>$post->id,
                                'type'=>'image'
                            ]);
                        }
                    });
    }
}
