<?php

$conn = pg_connect("
host=localhost
port=5432
dbname=gse_db
user=postgres
password=postgres123
");

if($conn){
    echo "Koneksi berhasil";
}else{
    echo "Koneksi gagal";
}

?>