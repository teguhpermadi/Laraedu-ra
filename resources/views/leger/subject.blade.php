<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leger Subject</title>
</head>
<link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
<style>
    body {
        font-family: 'Poppins';
    }
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }
    th, td {
        padding: 5px;
    }
    .center {
        text-align: center;
    }
    .text-sm {
        font-size: 9pt;
    }
    </style>
<body>
    <h1>Leger {{$data->subject->name}}</h1>
    <p>
        Tahun Pelajaran : {{$data->academic->year}} Semester {{$data->academic->semester}} <br>
        Kelas : {{$data->grade->name}} <br>
        Guru : {{$data->teacher->name}} <br>
        Tanggal cetak leger: {{now()}} 
    </p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Lengkap</th>
                @foreach ($data->competencies as $competency)
                    <th>{{$competency->code}}</th>
                @endforeach
                <th>Rata-rata Kompetensi</th>
                <th>Tengah Semester</th>
                <th>Akhir Semester</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($data->grade->studentGrade as $studentGrade)
            {{-- identitas siswa --}}
               <tr>
                    <td>{{$no++}}</td> 
                    <td>{{$studentGrade->student->nis}}</td>
                    <td class="text-sm">{{$studentGrade->student->name}}</td>

                    {{-- nilai per kompetensi --}}
                    @foreach ($data->competencies as $competency)
                        <td class="center">
                            @livewire('leger.score', [
                                'student_id' => $studentGrade->student->id,
                                'competency_id' => $competency->id
                            ])
                        </td>
                    @endforeach

                    {{-- nilai rata-rata kompetensi --}}
                    <td class="center">
                        @livewire('leger.avg-score', [
                                'student_id' => $studentGrade->student->id,
                                'teacher_subject_id' => $data->id
                            ])
                    </td>

                    {{-- nilai tengah semester --}}
                    <td class="center">
                        @livewire('leger.exam-score', [
                                'student_id' => $studentGrade->student->id,
                                'teacher_subject_id' => $data->id,
                                'caterogy' => 'middle',
                            ])
                    </td>

                    {{-- nilai akhir semester --}}
                    <td class="center">
                        @livewire('leger.exam-score', [
                                'student_id' => $studentGrade->student->id,
                                'teacher_subject_id' => $data->id,
                                'caterogy' => 'last',
                            ])
                    </td>
                    <td>
                        @livewire('leger.na-score', [
                                'student_id' => $studentGrade->student->id,
                                'teacher_subject_id' => $data->id,
                            ])
                    </td>
               </tr> 
            @endforeach
        </tbody>
    </table>

    <h4>Keterangan</h4>
    <ol>
        <li>Nilai yang berwarna kuning yang berada dibawah KKM.</li>
    </ol>

    <h4>Daftar Kompetensi</h4>
    <table>
        <thead>
            <th>No</th>
            <th>Kode</th>
            <th>Deskripsi</th>
            <th>KKM</th>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($data->competencies as $competency)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$competency->code}}</td>
                    <td>{{$competency->description}}</td>
                    <td>{{$competency->passing_grade}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        // Menampilkan dialog cetak saat halaman dimuat
        // window.onload = function() {
        //     window.print(); // Munculkan dialog cetak
        // }
    </script>
</body>
</html>