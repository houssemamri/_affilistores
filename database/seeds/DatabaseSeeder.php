<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            SetupSeeder::class,
            PagesSeeder::class,
            FeaturesSeeder::class,
            MemberMenuSeeder::class,
            ThemesSeeder::class,
            ColorSchemesSeeder::class,
            EmailRespondersSeeder::class,
            DesignOptionsSeeder::class,
        ]);
    }
}
