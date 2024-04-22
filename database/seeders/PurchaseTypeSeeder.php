<?php

namespace Database\Seeders;

use App\Models\PurchaseType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tipo de compra: Recompensa (Reward)
        PurchaseType::create([
            'name' => 'Reward',
        ]);
        // Tipo de compra: Compra en Tienda (Store Purchase)
        PurchaseType::create([
            'name' => 'Store Purchase',
        ]);
    }
}
