<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Elettronica',
            'Abbigliamento',
            'Sport',
            'Giardinaggio',
            'Casa',
            'Libri',
            'Motori',
            'Informatica',
            'Arredamento',
            'Giochi'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category
            ]);
        }
    }
}
