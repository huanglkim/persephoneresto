<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            [    
                'nama' => 'Makanan Berat',
                'kategori' => 'Makanan',
                'gambar' => 'makananberat.jpg',
            ],
            [    
                'nama' => 'Dessert',
                'kategori' => 'Makanan',
                'gambar' => 'dessert.jpeg',
            ],
            [    
                'nama' => 'Snack',
                'kategori' => 'Makanan',
                'gambar' => 'snack.jpg',
            ],
            [    
                'nama' => 'Minuman',
                'kategori' => 'Minuman',
                'gambar' => 'minuman.jpeg',
            ]
        ]);
    }
}
