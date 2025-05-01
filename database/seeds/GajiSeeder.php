<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gaji')->insert([
            [    
                'karyawan_id' => 1,
                'absensi_id' => 1,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 1,
            ],
            [    
               'karyawan_id' => 2,
                'absensi_id' => 2,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 2,
            ],
            [    
                'karyawan_id' => 3,
                'absensi_id' => 3,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 3,
            ],
            [    
               'karyawan_id' => 4,
                'absensi_id' => 4,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 4,
            ],
            [    
                'karyawan_id' => 5,
                'absensi_id' => 5,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 5,
            ],
            [    
               'karyawan_id' => 6,
                'absensi_id' => 6,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 5,
            ],
            [    
                'karyawan_id' => 7,
                'absensi_id' => 7,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 6,
            ],
            [    
               'karyawan_id' => 8,
                'absensi_id' => 8,
                'tanggal' => '06-02-2025',
                'jabatan_id' => 6,
            ],
            [    
                'karyawan_id' => 9,
                'absensi_id' => 9,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 7,
            ],
            [    
               'karyawan_id' => 2,
                'absensi_id' => 2,
                'tanggal' => '01-02-2025',
               'jabatan_id' => 7,
            ],
            [    
                'karyawan_id' => 1,
                'absensi_id' => 1,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 7,
            ],
            [    
               'karyawan_id' => 2,
                'absensi_id' => 2,
                'tanggal' => '01-02-2025',
               'jabatan_id' => 7,
            ],
            [    
                'karyawan_id' => 1,
                'absensi_id' => 1,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 7,
            ],
            [    
               'karyawan_id' => 2,
                'absensi_id' => 2,
                'tanggal' => '01-02-2025',
               'jabatan_id' => 7,
            ],
            [    
                'karyawan_id' => 1,
                'absensi_id' => 1,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 7,
            ],
            [    
               'karyawan_id' => 2,
                'absensi_id' => 2,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 7,
            ],
            [    
                'karyawan_id' => 2,
                 'absensi_id' => 2,
                 'tanggal' => '01-02-2025',
                 'jabatan_id' => 7,
             ],
            [    
                'karyawan_id' => 18,
                'absensi_id' => 18,
                'tanggal' => '01-02-2025',
                'jabatan_id' => 7,
            ]
            ]);
    }
}
