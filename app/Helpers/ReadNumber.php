<?php

/**
 * Membaca angka sebagai kata-kata dalam bahasa Indonesia.
 *
 * @param  int|float  $number
 * @return string
 */
function readNumber($number)
{
    $decimalSeparator = 'koma';
    $result = '';

    // Pisahkan bagian bilangan bulat dan desimal
    $parts = explode('.', $number);

    // Baca bagian bilangan bulat
    $result .= readIntegerPart($parts[0]);

    // Tambahkan kata "koma" jika ada bagian desimal
    if (isset($parts[1])) {
        $result .= ' ' . $decimalSeparator . ' ' . readDecimalPart($parts[1]);
    }

    return $result;
}

/**
 * Membaca bagian bilangan bulat sebagai kata-kata dalam bahasa Indonesia.
 *
 * @param  int  $integerPart
 * @return string
 */
function readIntegerPart($integerPart)
{
    // Daftar kata angka dalam bahasa Indonesia
    $words = [
        0 => 'nol',
        1 => 'satu',
        2 => 'dua',
        3 => 'tiga',
        4 => 'empat',
        5 => 'lima',
        6 => 'enam',
        7 => 'tujuh',
        8 => 'delapan',
        9 => 'sembilan',
    ];

    $result = '';
    $length = strlen($integerPart);

    for ($i = 0; $i < $length; $i++) {
        $digit = (int)$integerPart[$i];

        if ($digit === 0 && $length > 1 && $i < $length - 1) {
            // Skip nol di tengah-tengah
            continue;
        }

        // $result .= $words[$digit] . ' ';
        if ($digit === 1 && $length - $i === 3) {
            // Kasus khusus ketika digit pertama adalah 1 (seratus, seribu, dst)
            $result .= 'seratus ';
        } else {
            $result .= $words[$digit] . ' ';
        }

        // Tambahkan satuan (puluh, ratus) jika diperlukan
        if ($length - $i > 1) {
            $exponent = $length - $i - 1;

            switch ($exponent % 3) {
                case 0:
                    $result .= 'ribu ';
                    break;
                case 1:
                    $result .= 'puluh ';
                    break;
                case 2:
                    $result .= 'ratus ';
                    break;
            }
        }
    }

    return trim($result);
}

/**
 * Membaca bagian desimal sebagai kata-kata dalam bahasa Indonesia.
 *
 * @param  int  $decimalPart
 * @return string
 */
function readDecimalPart($decimalPart)
{
    // Daftar kata angka dalam bahasa Indonesia
    $words = [
        0 => 'nol',
        1 => 'satu',
        2 => 'dua',
        3 => 'tiga',
        4 => 'empat',
        5 => 'lima',
        6 => 'enam',
        7 => 'tujuh',
        8 => 'delapan',
        9 => 'sembilan',
    ];

    $result = '';

    for ($i = 0; $i < strlen($decimalPart); $i++) {
        $digit = (int)$decimalPart[$i];
        $result .= $words[$digit] . ' ';
    }

    return trim($result);
}