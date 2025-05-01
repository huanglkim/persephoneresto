<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('divisi')->insert([
            [    
                'nama' => 'HRD',
            ],
            [    
                'nama' => 'IT',
            ],
            [    
                'nama' => 'Finance',
            ],
            [    
                'nama' => 'Supervisor',
            ],
            [    
                'nama' => 'Marketing',
            ],
            [    
                'nama' => 'Security',
            ],
            [    
                'nama' => 'Karyawan',
            ]
            ]);
    }
}
