<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'Sekolah ku',
            'address' => 'Jalan sekolah ku',
            'nsm' => '1234567890',
            'npsn' => '123456',
            'email' => 'sekolah@sekolah.sch.id',
            'phone' => '0123456789',
            'website' => 'www.sekolah.sch.id',
            'logo' => '',
            'village_id' => '',
            'foundation' => '',
        ];

        School::create($data);
    }
}
