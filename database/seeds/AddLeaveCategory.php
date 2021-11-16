<?php

use Illuminate\Database\Seeder;
use App\Models\LevelCategory;

class AddLeaveCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'category' => 'Full Day',
                'type' => 'day',
            ],
            [
                'category' => 'Half Day',
                'type' => 'day',
            ]
        ];

        foreach ($categories as $category) {
            LevelCategory::create($category);
        }
    }
}
