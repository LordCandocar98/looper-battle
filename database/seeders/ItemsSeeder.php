<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemsSeeder extends Seeder
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
                'code' => 0,
                'name' => 'Rifle',
                'description' => 'Un rifle versátil que ofrece precisión y potencia de fuego. Ideal para enfrentamientos a larga distancia, este arma es esencial para controlar áreas abiertas y mantener a raya a tus oponentes.'
            ],
            [
                'code' => 1,
                'name' => 'Pistol',
                'description' => 'Una pistola confiable y ágil que se adapta bien a combates cercanos. Aunque no tiene el alcance de otras armas, su velocidad y maniobrabilidad la convierten en una elección inteligente para enfrentamientos rápidos.'
            ],
            [
                'code' => 3,
                'name' => 'Grenade',
                'description' => 'Un explosivo letal diseñado para cambiar el curso de la batalla. Úsala estratégicamente para desalojar a enemigos de sus escondites o para crear distracciones tácticas en el campo de juego.'
            ],
            [
                'code' => 4,
                'name' => 'Sniper',
                'description' => 'Un rifle de precisión letal que destaca en disparos a larga distancia. Con un alcance extraordinario, este francotirador es la elección perfecta para eliminar enemigos desde las sombras sin ser detectado.'
            ],
            [
                'code' => 5,
                'name' => 'Knife',
                'description' => 'Un arma cuerpo a cuerpo silenciosa y letal. Útil para ataques sorpresa y eliminaciones sigilosas, el cuchillo es esencial para jugadores que prefieren acercarse sin hacer ruido.'
            ],
            [
                'code' => 6,
                'name' => 'Molotov',
                'description' => 'Una herramienta incendiaria que puede cambiar drásticamente el curso de una confrontación. Lanza el cóctel Molotov para crear zonas de fuego que obligarán a tus enemigos a reubicarse y reevaluar su estrategia.'
            ],
            [
                'code' => 7,
                'name' => 'Grenade Launcher',
                'description' => 'Este poderoso lanzador dispara granadas explosivas a distancia, perfecto para limpiar áreas pobladas o para atacar a enemigos resguardados tras coberturas. Maneja con cuidado, ¡pues también puedes ser víctima de tus propias explosiones!'
            ],
            [
                'code' => 8,
                'name' => 'Rifle02',
                'description' => 'Una versión mejorada del rifle estándar, el Rifle02 combina potencia y velocidad de fuego. Con capacidades mejoradas, este rifle es la elección preferida de los jugadores que buscan un equilibrio entre versatilidad y letalidad en cualquier situación.'
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
