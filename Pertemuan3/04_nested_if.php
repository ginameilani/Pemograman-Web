<?php
declare(strict_types=1);

$sudahLogin = true;
$peran = 'admin';

if ($sudahlogin) {
    if ($peran === 'admin') {
        echo "Selamat datang, Admin. Akses penuh.\n";
    } elseif ($peran === 'operator') {
        echo "Selamat datang, Operator. Akses terbatas.\n";
    } else {
        echo "Peran tidak dikenal.\n";
    }
} else {
        echo "Silahkan login terlebih dahulu.\n";
}

$terverifikasi = true;
$saldo = 120000;
if ($sudahlogin && $terverifikasi && $saldo >= 100000) {
    echo "Transaksi besar diizinkan.\n";
}