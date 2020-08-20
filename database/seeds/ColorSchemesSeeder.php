<?php

use Illuminate\Database\Seeder;
use App\ColorScheme;
use App\Theme;

class ColorSchemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $themes = Theme::all();

        $colorSchemes = [
            'default' => 'Default Theme',
            'template-one' => 'Theme 1',
            'template-two' => 'Theme 2',
            'template-three' => 'Theme 3',
            'template-four' => 'Theme 4',
            'template-five' => 'Theme 5',
            'template-six' => 'Theme 6',
            'template-seven' => 'Theme 7',
            'template-eight' => 'Theme 8',
            'template-nine' => 'Theme 9',
        ];

        foreach ($themes as $theme) {
            foreach ($colorSchemes as $key => $colorScheme) {
                ColorScheme::create([
                    'theme_id' => $theme->id, 
                    'name' => $key,
                    'slug' => $colorScheme
                ]);
            }
        }
    }
}
