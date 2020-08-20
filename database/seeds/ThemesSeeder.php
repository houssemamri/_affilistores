<?php

use Illuminate\Database\Seeder;
use App\Theme;

class ThemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $themes =  [
            0 => (object) [
                "name" => "Default Theme",
                "slug" => "default"
            ],
            1 => (object) [
                "name" => "Theme 1",
                "slug" => "template-one"
            ],
            2 => (object) [
                "name" => "Theme 2",
                "slug" => "template-two"
            ],
            3 => (object) [
                "name" => "Theme 3",
                "slug" => "template-three"
            ],
            4 => (object) [
                "name" => "Theme 4",
                "slug" => "template-four"
            ],
            5 => (object) [
                "name" => "Theme 5",
                "slug" => "template-five"
            ],
            6 => (object) [
                "name" => "Theme 6",
                "slug" => "template-six"
            ],
            7 => (object) [
                "name" => "Theme 7",
                "slug" => "template-seven"
            ],
            8 => (object) [
                "name" => "Theme 8",
                "slug" => "template-eight"
            ],
            9 => (object) [
                "name" => "Theme 9",
                "slug" => "template-nine"
            ]
        ];

        foreach ($themes as $theme) {
            Theme::create([
                'name' => $theme->name,
                'slug' => $theme->slug
            ]);
        }
    }
}
