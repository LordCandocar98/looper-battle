<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\PlayerScore;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Team::create([
            'id' => 0,
            'name' => 'N/A',
            'color' => 'gray',
        ]);
        Team::create([
            'name' => 'RED',
            'color' => 'red',
        ]);

        Team::create([
            'name' => 'BLUE',
            'color' => 'blue',
        ]);

        // Actualizo los registros que estén en null a 0 para dejar por default el N/A
        PlayerScore::whereNull('team_id')->update(['team_id' => 0]);
    }
}
