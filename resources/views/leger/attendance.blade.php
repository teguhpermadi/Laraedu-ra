<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leger Attendance</title>
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
    <h1>Leger {{$grade->grade->name}}</h1>
    <p>
        Tahun Pelajaran : {{$grade->academic->year}} Semester {{$grade->academic->semester}} <br>
        Walikelas : {{$grade->teacher->name}} <br>
        Tanggal cetak leger: {{now()}} 
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Lengkap</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Tanpa Keterangan</th>
                <th>Catatan</th>
                <th>Prestasi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($students as $student)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$student->nis}}</td>
                    <td class="text-sm">{{$student->name}}</td>
                    <td class="center">{{$student->attendance->sick}}</td>
                    <td class="center">{{$student->attendance->permission}}</td>
                    <td class="center">{{$student->attendance->absent}}</td>
                    <td>{{$student->attendance->note}}</td>
                    <td>{{$student->attendance->achievement}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>