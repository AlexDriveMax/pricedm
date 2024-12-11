<?php

include_once("CsvReaderWriter.php");
include_once("dbAndrey.php");

function genFileName($daysBefore){
  //за сегодняшний день нет, только за вчерашний
	//присылает после полуночи по Бостону


	$time = time();
	$day = 60*60*24;
	$time = $time - $day*$daysBefore;

	return  "DealerCenter_".date("dmy",$time).".csv";

;}

  $local_file = "dcFTP.csv";
	$daysBefore = 1;

	$db = new dbAndrey([
	'dbname'=>'parsing',
	'user'=>'root',
	'pass'=>"Si1jieceaf5yeise",

	]);


  //начинаем 1. скачиваем файл

$connect = ftp_connect("138.197.13.44", 21, 30);
if (!ftp_login($connect, "ftpuser", "lDpLPv1B4ZsRJhu2")) exit("Не могу соединиться");
  ftp_pasv($connect,TRUE);


  $remote_file = genFileName($daysBefore);
	//FTP_ASCII


  print_r($remote_file);print_r('<br/>');

$ftpget=ftp_get( $connect, $local_file, $remote_file, FTP_BINARY );
if (!$ftpget) {
	print_r("Файл с таким названием не найден"); print_r('<br/>');

    $q = $db->genInsert('dc_ftp_days', [
				'date'=>date("y-m-d"),
				'time'=>date("H:i:s"),
				'status'=>"noFile",
				'fileName'=>$remote_file,
				]);
    	$db->q($q);


	exit();
}



 //2. парсим файл

$read = new CsvReader($local_file,',');
$rows = $read->GetCsv();



$i=0;$numCar=0;
foreach($rows as $i=>$row){

	$i++;if ($i==1) {continue;}

  $VIN = $row[8];

	if (!$VIN) {continue;}

  $imagesCol = $row[18];
  $images = explode(",",$imagesCol);
  $image1 = $images[0];
  $image2 = $images[1];
  $image3 = $images[2];
/*
	print_r($VIN); print_r('<br/>');
	print_r($image1); print_r('<br/>');
	print_r($image2); print_r('<br/>');
	print_r($image3); print_r('<br/>'); print_r('<br/>'); print_r('<br/>');
*/


				$q = $db->genInsert('dc_ftp', [
				'date'=>date("y-m-d"),
				'time'=>date("H:i:s"),
				'VIN'=>$VIN,
				'image1'=>$image1,  //
				'image2'=>$image2,  //
				'image3'=>$image3,
				]);

        $db->q($q);
        $numCar++;

}

	print_r("Количество машин: $numCar"); print_r('<br/>');


$q = $db->genInsert('dc_ftp_days', [
	'date'=>date("y-m-d"),
	'time'=>date("H:i:s"),
	'status'=>"success",
	'carsNumber'=>$numCar,
	'fileName'=>$remote_file,
]);
$db->q($q);
 //3. парсим файл



           /*
           [0] => AccountID
            [1] => DCID
            [2] => DealerName
            [3] => Address
            [4] => City
            [5] => State
            [6] => Zip
            [7] => StockNumber
            [8] => VIN
            [9] => Year
            [10] => Make
            [11] => Model
            [12] => Trim
            [13] => Odometer
            [14] => ExteriorColor
            [15] => InteriorColor
            [16] => Transmission
            [17] => VehiclePrice
            [18] => Images
            [19] => VDP URL
            [20] => Photo URLs

*/


?>