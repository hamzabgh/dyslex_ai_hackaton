<?php

namespace Modules\Auth\Database\Seeders;

use App\Models\Letter;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SeedFakeLetterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define letters to be seeded
        $letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

        // Seed the letters table
        foreach ($letters as $letter) {
            Letter::create(['letter' => $letter]);
        }
    }
}
