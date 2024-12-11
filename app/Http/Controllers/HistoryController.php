<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{

function main(){

 $lastDate = $this->getLastParsingDate();
	$parsings=$this->getParsings($lastDate);

  return view('history',[
		'rand'=>rand(1,9999999),
		'parsings'=>$parsings,
		'page'=>'history',
		]);
}



function getParsings($lastDate){

  $dates=[];
		$lastDateTime = $lastDate." 1:00:00";
	 $day=60*60*24;

	 $i=-1;
		do{
	  $i++;
	  $days=$day*$i;
			$prevDate = date('Y-m-d',strtotime($lastDateTime)-$days);

	$check = $this->checkParsingForDate($prevDate);

	if ($check) {

   $ncDC=$this->getNumberCars($prevDate,"dc");
   $ncCG=$this->getNumberCars($prevDate,"cg");
   $statusCG=$this->getStatus($prevDate,"cg");
   $statusDcFTP=$this->getStatus($prevDate,"dcFTP");

	 $bdate = $this->bostonDate($prevDate);
		 $dates[]=[
			'date'=>$bdate,
			'ncCG'=>$ncCG,
			'ncDC'=>$ncDC,
			'statusCG'=>$statusCG,
			'statusDcFTP'=>$statusDcFTP,
			];
		}

	if ($i>60) {break ;}


		}while(5==5);

   return $dates;

}



function getNumberCars($date,$vendor){

if ($vendor=="cg") {
	$count = DB::table('car_guru')->where('date', $date)->count();
}

if ($vendor=="dc") {
	$count = DB::table('dealercenter')->where('date', $date)->count();
}

if ($vendor=="dcFTP") {
	$count = DB::table('dc_ftp')->where('date', $date)->count();
}

return $count;

}

function getStatus($date,$vendor){

if ($vendor=="cg") {
	$rowCG = DB::table('car_guru')->where('date', $date)->first();
	if ($rowCG) {return "success";}else{return "noData";}
}


if ($vendor=="dcFTP") {
	$status = DB::table('dc_ftp_days')->where('date', $date)->value("status");
 //	if (!$status) {$status="noData";}
  return $status;  //noFile
}

return $count;

}


function getLastParsingDate(){

	$lastDate = DB::table('dc_days')->orderBy('id', 'desc')->value('date');

	$lastDateTime = $lastDate." 1:00:00";
 $day=60*60*24;

 $i=-1;
	do{
  $i++;
  $days=$day*$i;
		$prevDate = date('Y-m-d',strtotime($lastDateTime)-$days);

$check = $this->checkParsingForDate($prevDate);

if ($check) {return $prevDate;}

if ($i>30) {return false;}

	}while(5==5);

}

function checkParsingForDate($date){

$rowDays = DB::table('dc_days')->where('date', $date)->first();
$rowDC = DB::table('dealercenter')->where('date', $date)->first();

if ($rowDays AND $rowDC) {return true;}else{return false;}

}

function bostonDate($date){

	$hours7=60*60*7;
	$bdate = date('Y-m-d',strtotime($date)-$hours7);
 return $bdate;

}

}
