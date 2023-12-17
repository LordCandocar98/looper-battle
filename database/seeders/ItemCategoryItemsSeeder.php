<?php

namespace Database\Seeders;

use App\Models\ItemCategoryItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemCategoryItemsSeeder extends Seeder
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
                'item_id' => 1,
                'item_category_id' => 1,
            ],
            [
                'item_id' => 1,
                'item_category_id' => 2,
            ],
            [
                'item_id' => 2,
                'item_category_id' => 1,
            ],
            [
                'item_id' => 2,
                'item_category_id' => 2,
            ],
            [
                'item_id' => 3,
                'item_category_id' => 3,
            ],
            [
                'item_id' => 4,
                'item_category_id' => 1,
            ],
            [
                'item_id' => 4,
                'item_category_id' => 2,
            ],
            [
                'item_id' => 5,
                'item_category_id' => 4,
            ],
            [
                'item_id' => 6,
                'item_category_id' => 3,
            ],
            [
                'item_id' => 7,
                'item_category_id' => 3,
            ],
            [
                'item_id' => 8,
                'item_category_id' => 1,
            ],
            [
                'item_id' => 8,
                'item_category_id' => 2,
            ],
        ];
        foreach ($items as $item) {
            ItemCategoryItem::create($item);
        }
    }
}
