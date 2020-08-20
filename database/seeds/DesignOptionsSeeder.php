<?php

use Illuminate\Database\Seeder;
use App\DesignOption;

class DesignOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = [
            'img/options/option_1',
            'img/options/option_2',
            'img/options/option_3',
            'img/options/option_4',
            'img/options/option_5'
        ];

        foreach ($options as $option) {
            DesignOption::create([
                'img_path' => $option
            ]);
        }
    }
}
