<?php

namespace Database\Seeders;

use Domain\Form\Models\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Form::updateOrCreate(
            ['name' => 'Contact'],
            ['fields' => ['name', 'email', 'message']],
        );
    }
}
