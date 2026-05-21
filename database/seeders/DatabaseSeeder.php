<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Obat;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User & Profil ADMIN
        $userAdmin = User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Admin::create([
            'id_user' => $userAdmin->id_user,
            'nama_admin' => 'Administrator SIRS',
        ]);

        // 2. Buat User & Profil DOKTER
        $userDokter = User::create([
            'username' => 'dokter1',
            'password' => Hash::make('password'),
            'role' => 'dokter',
        ]);

        Dokter::create([
            'id_user' => $userDokter->id_user,
            'nama_dokter' => 'dr. Budi Santoso, Sp.A',
            'spesialisasi' => 'Anak',
            'no_telp' => '081234567890',
        ]);

        // 3. Buat User & Profil PASIEN
        $userPasien = User::create([
            'username' => 'pasien1',
            'password' => Hash::make('password'),
            'role' => 'pasien',
        ]);

        Pasien::create([
            'id_user' => $userPasien->id_user,
            'no_rekam_medis' => 'RM-2026-001',
            'nama_lengkap' => 'Andi Darmawan',
            'tanggal_lahir' => '1995-08-17',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Merdeka No. 45, Yogyakarta',
        ]);

        // 4. Buat Master Data OBAT
        $dataObat = [
            [
                'kode_obat' => 'OBT-001',
                'nama_obat' => 'Paracetamol 500mg',
                'kategori' => 'Tablet',
                'stok' => 100,
                'harga' => 5000,
            ],
            [
                'kode_obat' => 'OBT-002',
                'nama_obat' => 'Amoxicillin 500mg',
                'kategori' => 'Kapsul',
                'stok' => 50,
                'harga' => 12000,
            ],
            [
                'kode_obat' => 'OBT-003',
                'nama_obat' => 'Sirup OBH Combi',
                'kategori' => 'Sirup',
                'stok' => 20,
                'harga' => 25000,
            ]
        ];

        foreach ($dataObat as $obat) {
            Obat::create($obat);
        }
    }
}