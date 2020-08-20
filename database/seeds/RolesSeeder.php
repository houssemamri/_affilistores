<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            (object)[
                'name' => 'Administrator',
                'description' => 'Administrator',
            ],
            (object)[
                'name' => 'User',
                'description' => 'User',
            ],
            (object)[
                'name' => 'Member',
                'description' => 'Member',
            ],
            (object)[
                'name' => 'Member Subuser',
                'description' => 'Member Subuser',
            ],
        ];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role->name,
                'description' => $role->description
            ]);
        }
    }
}
