<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Dosens first
        echo "Creating Dosens...\n";
        
        $dosen1 = Dosen::updateOrCreate(
            ['email' => 'dosen@dosen.com'],
            [
                'name' => 'Dr. Budi Santoso, M.Kom',
                'password' => Hash::make('dosen123'),
            ]
        );

        $dosen2 = Dosen::updateOrCreate(
            ['email' => 'siti.aminah@dosen.com'],
            [
                'name' => 'Dr. Siti Aminah, M.T',
                'password' => Hash::make('dosen123'),
            ]
        );

        echo "Created " . Dosen::count() . " dosens\n\n";

        // Create Mahasiswas
        echo "Creating Mahasiswas...\n";

        $mahasiswaData = [
            [
                'name' => 'Andi Pratama',
                'nim' => '210101001',
                'email' => 'mahasiswa@mahasiswa.com',
                'ipk' => 3.50,
                'jumlah_sks' => 120,
                'dosens' => $dosen1->id,
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Budi Setiawan',
                'nim' => '210101002',
                'email' => 'budi.setiawan@student.ac.id',
                'ipk' => 3.65,
                'jumlah_sks' => 125,
                'dosens' => $dosen1->id,
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Citra Dewi',
                'nim' => '210101003',
                'email' => 'citra.dewi@student.ac.id',
                'ipk' => 3.75,
                'jumlah_sks' => 130,
                'dosens' => $dosen2->id,
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Dinda Puspita',
                'nim' => '210101004',
                'email' => 'dinda.puspita@student.ac.id',
                'ipk' => 3.40,
                'jumlah_sks' => 118,
                'dosens' => $dosen2->id,
                'password' => Hash::make('mahasiswa123'),
            ],
            [
                'name' => 'Eka Putri',
                'nim' => '210101005',
                'email' => 'eka.putri@student.ac.id',
                'ipk' => 3.55,
                'jumlah_sks' => 122,
                'dosens' => $dosen1->id,
                'password' => Hash::make('mahasiswa123'),
            ],
        ];

        foreach ($mahasiswaData as $data) {
            $mahasiswa = Mahasiswa::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            // Sync with users table
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $data['password'],
                    'role' => 'mahasiswa',
                ]
            );

            echo "- Created: " . $mahasiswa->name . " (NIM: " . $mahasiswa->nim . ")\n";
        }

        echo "\nSuccess! Created:\n";
        echo "- " . Dosen::count() . " dosens\n";
        echo "- " . Mahasiswa::count() . " mahasiswas\n";
        echo "- " . User::where('role', 'mahasiswa')->count() . " mahasiswa users\n";
    }
}
