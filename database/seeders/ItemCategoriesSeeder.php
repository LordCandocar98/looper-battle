<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => 'Primary',
            ],
            [
                'name' => 'Secundary',
            ],
            [
                'name' => 'Perks',
            ],
            [
                'name' => 'Letal',
            ],
        ];

        foreach ($items as $item) {
            ItemCategory::create($item);
        }
    }
}
