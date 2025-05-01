<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jabatan')->insert([
        [    
            'nama_jabatan' => 'Staff HRD',
            'gaji_pokok' => 4000000,
            'tunjangan_jabatan' => 400000,
        ],
        [    
            'nama_jabatan' => 'Staff IT',
            'gaji_pokok' => 4500000,
            'tunjangan_jabatan' => 450000,
        ],
        [    
            'nama_jabatan' => 'Staff Finance',
            'gaji_pokok' => 3500000,
            'tunjangan_jabatan' => 350000,
        ],
        [    
            'nama_jabatan' => 'Supervisor',
            'gaji_pokok' => 3500000,
            'tunjangan_jabatan' => 350000,
        ],
        [    
            'nama_jabatan' => 'Marketing',
            'gaji_pokok' => 3000000,
            'tunjangan_jabatan' => 300000,
        ],
        [    
            'nama_jabatan' => 'Security',
            'gaji_pokok' => 2500000,
            'tunjangan_jabatan' => 250000,
        ],
        [    
            'nama_jabatan' => 'Karyawan',
            'gaji_pokok' => 2000000,
            'tunjangan_jabatan' => 200000,
        ]
        ]);
    }
}
