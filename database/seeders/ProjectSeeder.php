<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Domain\Project\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory(3)->hasSupportedTranslations()->create();
    }
}
