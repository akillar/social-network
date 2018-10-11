<?php

use App\User;
use App\Models\Status;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        Status::truncate();

        factory(User::class)->create([
            'name' => 'Alberto',
            'email' => 'alberto@social-app.com'
        ]);

        factory(Status::class, 10)->create();
    }
}
