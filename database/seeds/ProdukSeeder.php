<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('produks')->insert([
            [    
                'nama' => 'Ramen',
                'harga' => 40,
                'harga_toppingset' => 2500,
                'topping' => '',
                'menu_id' => 1,
                'is_ready' => 1,
                'jenis' => 'Special',
                'porsi' => 250.50,
                'gambar' => 'ramen.jpeg',

            ],
            [    
                'nama' => 'Ayam Satu Porsi',
                'harga' => 175000,
                'harga_toppingset' => 10000,
                'topping' => '',
                'menu_id' => 1,
                'is_ready' => 1,
                'jenis' => 'Reguler',
                'porsi' => 750.50,
                'gambar' => 'ayam_satu_porsi.jpeg',

            ],
            [    
                'nama' => 'Nasi Bakar',
                'harga' => 35000,
                'harga_toppingset' => 2500,
                'topping' => '',
                'menu_id' => 1,
                'is_ready' => 1,
                'jenis' => 'Reguler',
                'porsi' => 250.50,
                'gambar' => 'nasi_bakar.jpeg',

            ],
            [    
                'nama' => 'Nasi Goreng',
                'harga' => 35000,
                'harga_toppingset' => 2500,
                'topping' => '',
                'menu_id' => 1,
                'is_ready' => 1,
                'jenis' => 'Reguler',
                'porsi' => 250.50,
                'gambar' => 'nasi_goreng.jpeg',

            ],
            [    
                'nama' => 'Nasi Campur',
                'harga' => 30000,
                'harga_toppingset' => 2500,
                'topping' => '',
                'menu_id' => 1,
                'is_ready' => 1,
                'jenis' => 'Reguler',
                'porsi' => 250.50,
                'gambar' => 'nasi_campur.jpeg',

            ],
            [    
                'nama' => 'Pecel Lele',
                'harga' => 35000,
                'harga_toppingset' => 2500,
                'topping' => '',
                'menu_id' => 1,
                'is_ready' => 1,
                'jenis' => 'Reguler',
                'porsi' => 250.50,
                'gambar' => 'pecel_lele.jpeg',

            ],
            [    
                'nama' => 'Soto',
                'harga' => 25000,
                'harga_toppingset' => 2500,
                'topping' => '',
                'menu_id' => 1,
                'is_ready' => 1,
                'jenis' => 'Reguler',
                'porsi' => 250.50,
                'gambar' => 'soto.jpeg',

            ],
            [    
                'nama' => 'Wafel Eskrim',
                'harga' => 35000,
                'harga_toppingset' => 2500,
                'topping' => '',
                'menu_id' => 2,
                'is_ready' => 1,
                'jenis' => 'Special',
                'porsi' => 250.50,
                'gambar' => 'wafel_eskrim.jpeg',

            ],
            [    
                'nama' => 'Pukis',
                'harga' => 35000,
                'harga_toppingset' => 2500,
                'topping' => '',
                'menu_id' => 3,
                'is_ready' => 1,
                'jenis' => 'Reguler',
                'porsi' => 250.50,
                'gambar' => 'pukis.jpeg',

            ],
            [    
                'nama' => 'Rujak Buah Madura',
                'harga' => 35000,
                'harga_toppingset' => 2500,
                'topping' => '',
                'menu_id' => 3,
                'is_ready' => 1,
                'jenis' => 'Special',
                'porsi' => 250.50,
                'gambar' => 'rujak_buah.jpeg',

            ],
            [    
                'nama' => 'Lumpia',
                'harga' => 35000,
                'harga_toppingset' => 2500,
                'topping' => '',
                'menu_id' => 3,
                'is_ready' => 1,
                'jenis' => 'Reguler',
                'porsi' => 250.50,
                'gambar' => 'lumpia.jpeg',

            ],
            [    
                'nama' => 'Es Cincau',
                'harga' => 35000,
                'harga_toppingset' => 2500,
                'topping' => '',
                'menu_id' => 4,
                'is_ready' => 1,
                'jenis' => 'Reguler',
                'porsi' => 250.50,
                'gambar' => 'cincau.jpg',

            ],
            [    
                'nama' => 'Es Campur',
                'harga' => 40000,
                'harga_toppingset' => 7500,
                'topping' => '',
                'menu_id' => 4,
                'is_ready' => 1,
                'jenis' => 'Special',
                'porsi' => 300.50,
                'gambar' => 'es_campur.jpeg',

            ]
        ]);
    }
}
