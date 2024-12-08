<?php

namespace App\Imports;

use App\Models\DataStudent;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class StudentImport implements ToCollection, WithHeadingRow, WithUpserts
{
    use Importable;
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        // $data = [];
        foreach ($rows as $row) 
        {
            // $data = [
            //     'nisn' => $row['nisn'],
            //     'nis' => $row['nis'],
            //     'name' => $row['nama_lengkap'],
            //     'nick_name' => $row['nama_panggilan'],
            //     'gender' => Str::lower($row['jenis_kelamin']),
            //     'city_born' => $row['tempat_lahir'],
            //     'birthday' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'], 'Asia/Jakarta'),
            // ];

            $student = Student::updateOrCreate([
                'nisn' => $row['nisn'],
                'nis' => $row['nis'],
                'name' => $row['nama_lengkap'],
                'nick_name' => $row['nama_panggilan'],
                'gender' => Str::lower($row['jenis_kelamin']),
                'city_born' => $row['tempat_lahir'],
                'birthday' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'], 'Asia/Jakarta'),
            ]);

            DataStudent::updateOrCreate([
                'student_id' => $student->id,
                'student_address' => $row['alamat_siswa'],
                'student_province'=> $row['provinsi_siswa'],
                'student_city'=> $row['kota_siswa'],
                'student_district'=> $row['kecamatan_siswa'],
                'student_village'=> $row['kelurahan_siswa'],
                'religion' => $row['agama'],
                'previous_school' => $row['asal_sekolah'],
                'date_received' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_diterima'], 'Asia/Jakarta'),
                'grade_received' => $row['diterima_kelas'],
                'father_name' => $row['nama_ayah'],
                'father_education' => $row['pendidikan_ayah'],
                'father_occupation' => $row['pekerjaan_ayah'],
                'father_phone' => $row['telp_ayah'],
                'mother_name' => $row['nama_ibu'],
                'mother_education' => $row['pendidikan_ibu'],
                'mother_occupation' => $row['pekerjaan_ibu'],
                'mother_phone' => $row['telp_ibu'],
                'guardian_name' => $row['nama_wali'],
                'guardian_education' => $row['pendidikan_wali'],
                'guardian_occupation' => $row['pekerjaan_wali'],
                'guardian_phone' => $row['telp_wali'],
                'guardian_address' => $row['alamat_wali'],
                // 'guardian_village' => $row['kelurahan_wali'],
                'parent_address' => $row['alamat_orangtua'],
                'parent_village' => $row['kelurahan_orangtua'],
                'parent_address' => $row['alamat_orangtua'],
                'parent_province'=> $row['provinsi_orangtua'],
                'parent_city'=> $row['kota_orangtua'],
                'parent_district'=> $row['kecamatan_orangtua'],
                'parent_village'=> $row['kelurahan_orangtua'],
                'height' => $row['tinggi_badan'],
                'weight' => $row['berat_badan'],
            ]);
        }

        // dd($data);
    }

    public function uniqueBy()
    {
        return ['nisn', 'nis'];
    }

    public function upsertColumns()
    {
        return ['name', 'gender'];
    }
}
