<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleXLSXGen;
   //  DB::statement("your query")
class DashboardController extends Controller
{

private $startT;
private $endT;
private $resultT;

function clearPrices(){
  DB::table('edit_fields')->update(['price' => 0]);
}


function cacheCars(Request $r){

	if ($r->date) {
	 	$date=$this->moscowDate($r->date);
	}else{
	 	$date=$this->getLastParsingDate();
	}

	$rows = $this->summRows($date);

  // print_r($rows); print_r('<br/>');
	DB::table('cars_params')->where('date', $date)->delete();

	foreach($rows as $i=>$row){

		$dlr = $this->daysLastReprise($row->VIN, $date);
		$viewsDaysBefore = $this->viewsDaysBefore($row->VIN, $date);
		$ph = $this->priceHistory($row->VIN);
		$graph = $this->graph($row->VIN);
    $imgParam = $this->checkGetImg($row->VIN);

    $scale=$scaleRes=0;
		if (@$row->cg) {

			$dealNum=0;

			if (@$row->deal_pt) {
				$deal = $row->deal_pt;
				$deal = trim($deal);
				switch ($deal) {
				case "Great Deal":$dealNum=1;break;
				case "Good Deal":$dealNum=2;break;
				case "Fair Deal":$dealNum=3;break;
				case "High Price":$dealNum=4;break;
				case "Overpriced":$dealNum=5;break;
				}
			}

			$scaleRes=$this->scale($row->VIN, $dealNum, $row->Price, $row->price_pt, $row->price_top_pt);

		}
		if ($scaleRes) {$scale=1;}




		$viewsDaysBefore=json_encode($viewsDaysBefore);
		$ph=json_encode($ph);
		$graph=json_encode($graph);
		//print_r($viewsDaysBefore); print_r('<br/>');

			DB::table('cars_params')->insert(
			[
				'date' => $date,
				'VIN' => $row->VIN,
				'daysLastReprise' => $dlr,
				'viewsDaysBefore' => $viewsDaysBefore,
				'priceHistory' => $ph,
				'graph' => $graph,
				'scale' => $scale,
				'imgParam' => $imgParam,
			]);


	}

  print_r("cached ok"); print_r('<br/>');
}



function checkGetImg($vin){

  if (!$vin) {return "noPhoto.jpg";}

	$img=$vin."_0.jpg";
	$imgFull=public_path()."/images/".$vin."_0.jpg";
	if (file_exists($imgFull) AND filesize($imgFull)>15000) {
		$image=$img;
	}else{
		$image="noPhoto.jpg";
	}

   return $image;

}


function scale($vin, $deal,$price,$deviation1, $deviation2=false){

$price = $this->deformatPrice($price);
$deviation1 = abs($this->deformatPrice($deviation1));
$deviation2 = abs($this->deformatPrice($deviation2));

 if (!$vin OR !$deal OR $deal<1 OR $deal>5 OR !$price OR !$deviation1) {return false;}

$width=890;
$widthToddler=22;
$wSideSectorDoll=3000;

 $sector=round($width/5) ;

if ($deal==2 OR $deal==3 OR $deal==4) {

 $mark=0;
 for($i=1; $i<=5; $i++){
   $mark1=$mark;
   $mark2=$mark+$sector;
	 if ($i==$deal) { break;}
   $mark=$mark+$sector;
 }

	$sectorDoll = $deviation1+$deviation2;
	$deviation1Pix=round(($deviation1/$sectorDoll)*$sector);
	$x=$mark1+$deviation1Pix;
  $x=$x-round($widthToddler/2);
}elseif($deal==1 OR $deal==5){

	if ($deal==1) {
  	if ($deviation1>$wSideSectorDoll) {
      $x=0;
  	} else {
			$deviation1Pix=round(($deviation1/$wSideSectorDoll)*$sector);
      $mark2=$sector;
			$x=$mark2-$deviation1Pix;
			$x=$x-round($widthToddler/2);
  	}
	}

	if ($deal==5) {

  	if ($deviation1>$wSideSectorDoll) {
      $x=$width-$widthToddler;
  	} else {
			$deviation1Pix=round(($deviation1/$wSideSectorDoll)*$sector);
      $mark1=$width - $sector;
			$x=$mark1+$deviation1Pix;
			$x=$x-round($widthToddler/2);
  	}
	}
;}

    ;
$image_1 = imagecreatefrompng(public_path('includes/scaleNull.png'));
$image_2 = imagecreatefrompng(public_path('includes/scaleToddler.png'));

imagealphablending($image_1, false);
imagesavealpha($image_1, true);

//imagealphablending($image_2, false);
//imagesavealpha($image_2, true);

;

imagecopy($image_1, $image_2, $x, 0, 0, 0, $widthToddler, $widthToddler);

$resultFilename="$vin.png";
imagepng($image_1, public_path("scales/$resultFilename"));

/*imagesavealpha($image_1, true);
$trans_colour = imagecolorallocatealpha($image_1, 0, 0, 0, 127);
imagefill($image_1, 0, 0, $trans_colour);
*/

$white = imagecolorallocatealpha($image_2, 255, 255, 255, 127);
imagecolortransparent($image_2, $white);


// задаем заголовок, чтоб вывести результат в браузере
//header('Content-Type: image/png');
// выводим картинку
//imagepng($image_1);
// очищаем память
imagedestroy($image_1);
imagedestroy($image_2);

return $resultFilename;
}



function scaleMarks($dealNum, $left, $right=false){
	if (!$dealNum OR $dealNum<1 OR $dealNum>5 OR !$left) {return false;}

 if ($dealNum==1) {
     $mark[1]=$left;
 }elseif($dealNum==2){
     $mark[1]=$left;  $mark[2]=$right;
 }elseif($dealNum==3){
     $mark[2]=$left;  $mark[3]=$right;
 }elseif($dealNum==4){
     $mark[3]=$left;  $mark[4]=$right;
 }elseif($dealNum==5){
     $mark[4]=$left;
 }
      return $mark;
}


function export(Request $r){


 if (@$r->date) {
 	$date=$r->date;
} else {
	$dateLast=$this->getLastParsingDate();
	$date=$this->bostonDate($dateLast);
}
   /*
	$dateToday = $this->bostonDate(time(),"unix");


	if ($date==$dateToday) {
    $time= $this->bostonDate(time(), 'time');
		$filename="dm_$date_$time.xlsx";
	}else{
    $time= "";
		$filename="dm_$date.xlsx";
	;}
*/

	  $dateToday = $this->bostonDate(time(),"unix");
    $time= $this->bostonDate(time(), 'time');
		$filename="dm_".$dateToday."_".$time.".xlsx";


 $runDate ='Run Date: '.$dateToday.' '.$time;
$csv=[
 ['<style color="#002914"><b>DriveMax</b></style>'],
 ['<style color="#00381B">863 N Main St West Bridgewater MA 02379</style>'],
 ['<style color="#00381B">Run By: Alexander P</style>'],
 ['<style color="#00381B">'.$runDate.'</style>'],
] ;







$csv[]=[] ;


$csv[]=["<b>Number</b>","<b>Stock #</b>","<b>VIN</b>","<b>Year</b>","<b>Make</b>","<b>Model</b>","<b>Days in stock</b>","<b>Custom status</b>","<b>Number of pictures</b>","<b>Vehicle condition</b>",
"<right><b>Advertise price</b></right>",
"<right><b>Discount</b></right>",
'<right><b><style color="#000085">New Price</style></b></right>',
"<right><b>Potential Gross</b></right>",
"<right><b>Number of leads from DC</b></right>",
"<right><b>Days last reprise</b></right>"] ;


$mdate=$this->moscowDate($date);

$rows = $this->summRows($mdate);
$rows = $this->formatRowsLight($rows);
$rows = $this->sort($rows);
$u=0;
foreach($rows as $i=>$row){
  $u++;
	$row=(array)$row;


 if (@$row["daysLastReprise"]) {
 	$dlr = $row["daysLastReprise"];
 }else{
	$dlr = $this->daysLastReprise($row["VIN"], $mdate);
 }


 $advPrice="";
 if (@$row["AdvertisingPrice"]) { $advPrice=$row["AdvertisingPrice"];}

 $price="";
 if (@$row["price"]) { $price = $row["price"];}

 $discount="";
 if ($price AND $advPrice) {
 	$discount=$advPrice-$price;
 //	$discount = $this->formatPrice($discount);
 }  ;

 if ($price) {
//	$price = $this->formatPrice($price);
  $price = '<style color="#000085">'.$price.'</style>';
 }



// $advPrice = $this->formatPrice($advPrice);
 //$row["PotentialGross"] = $this->formatPrice($row["PotentialGross"]);

 $Stock="";
 if (@$row["Stock#"]) {$Stock="#".$row["Stock#"];}
 if (@$row["StockNumber"]) {$Stock="#".$row["StockNumber"];}
$csv[]=[
$u,
$Stock,
$row["VIN"],
$row["Year"],
$row["Make"],
$row["Model"],
$row["DaysInStock"],
$row["CustomStatus"],
$row["NumberOfPics"],
$row["VehicleCondition"],
'<right>'.$advPrice.'</right>',
'<right>'.$discount.'</right>',
'<right>'.$price.'</right>',
'<right>'.$row["PotentialGross"].'</right>',
$row["NumberofLeads"],
'<right>'.$dlr.'</right>',
] ;
}



//$this->array_csv_download($csv, $filename);
$xlsx = SimpleXLSXGen::fromArray($csv)->setColWidth(1, 7)->setColWidth(6, 15)->setColWidth(7, 9);
$xlsx->downloadAs($filename);

	 //	return redirect()->route('loginPage');

}



function main(Request $r){



 $ajaxCars=1;


 if ($r->date) {
 	$date=$this->moscowDate($r->date);
 	$history=1;
}else{
 	$date=$this->getLastParsingDate();
 	$history=0;
}
  $this->date=$date;
	$dateBoston = $this->bostonDate($date);

	$cars=[];
	if (!$ajaxCars) {
		$cars = $this->getCars($date);
	}

 	$rows = $this->summRows($date);
  $filtersCount = $this->filtersCount($rows);




	$auth = session('auth');
	if (!$auth) {
	 return redirect()->route('loginPage');
	}



		$numCars=[
      'dc'=> $this->numDC,
      'cg'=> $this->numCG
		];


       //dashboard
		return view('cars',[
		'rand'=>rand(1,9999999),
		'date'=>$date,
		'dateTimeBoston'=>$dateBoston,
		'ajaxCars'=>$ajaxCars,
		'history'=>$history,
		'cars'=>$cars,
		'resultT'=>$this->resultT,
		'fc'=>$filtersCount,
		'numCars'=>$numCars,
		'page'=>"cars",

		]);



}

function ajaxCharts (Request $r){
	if (@$r->date) {
		$date=@$r->date;
	} else {
		return false;
	}

		return view('ajaxCharts', [
	 //	'cars'=>(array)$chartsData
		]);

}

function ajaxCars(Request $r){

 $sortBy = @$r->sortBy;
 $filters = @$r->filters;
// print_r($filters); print_r('<br/>');exit();
 $filters = json_decode($filters);
 $customStatus = @$r->customStatus;
 if ($customStatus=="false") {$customStatus=false;}
  // print_r($customStatus); print_r('<br/>');
 	// exit();
	if (@$r->date) {
		$date=@$r->date;
	} else {
		return false;
	}


$cars = $this->getCars($date, $sortBy, $filters, $customStatus);

$charts=[];
if (!$sortBy) {
$charts = $this->getChartsData();
}


 //return response()->json(['success'=>'Form is successfully submitted!']);

		return view('ajaxCars', [
		'cars'=>(array)$cars,
		'charts'=>$charts
		]);

}

function getChartsData(){
   $fc = $this->filtersCount;

   $fc2['rating']="[".implode(", ", $fc['rating'])."]";
   $fc2['reprice']="[".implode(", ", $fc['reprice'])."]";
   $fc2['stock']="[".implode(", ", $fc['stock'])."]";

	 return $fc2;
}



function getCars($date, $sortBy=false, $filters=false, $customStatus=false){

  $this->date=$date;
	$this->startT=microtime(true);
	if (!$date) {return [];}
	if ($this->resultCars()) {return false;}

 $rows = $this->summRows($date);


 $filters = $this->filtersReform($filters);
// print_r($filters); print_r('<br/>');exit();
 $rows = $this->filterRows($rows, $filters, $customStatus);

 $this->filtersCount = $this->filtersCount($rows);
 $rows = $this->formatRows($rows, $date);




 $rows = $this->sort($rows, $sortBy);


	$this->endT=microtime(true);

	$this->resultT=$this->endT-$this->startT;
	$this->resultT=round($this->resultT,2);


 return $rows ;

}



function summRows($date){

	$dcRows = $this->getDC($date);
	$dcFTPRows = $this->getDcFTP($date);
	$cgRows = $this->getCG($date);
	$carsParams = $this->getCarsParams($date);
	$editFields = $this->getEditFields();

/*	foreach($cgRows as $vin=>$cgRow){
    $dcRow=@$dcRows[$vin];
		if (!$dcRow) {print_r($vin); print_r('<br/>');}

	}
  exit();*/
//  print_r(count($dcRows)); print_r('<br/>');
//  print_r(count($cgRows)); print_r('<br/>');

  $hh=0;
	foreach($dcRows as $vin=>$dcRow){

		$dcFTPRow=@$dcFTPRows[$vin];
		$cgRow=@$cgRows[$vin];
		$efRow=@$editFields[$vin];
		$cpRow=@$carsParams[$vin];

		if (!$dcFTPRow) {$dcFTPRow=[];}
		if (!$cgRow) {$cgRow=[];}else{$hh++;}
		if (!$efRow) {$efRow=[];}
		if (!$cpRow) {$cpRow=[];}

		$mainRows[$vin]=(object)array_merge((array)$efRow, (array)$cgRow, (array)$dcRow,(array)$dcFTPRow, (array)$cpRow);

	}
   // print_r($hh); print_r('<br/>');
   // exit();

  return $mainRows;

}


//фильтруем тачки
function filterRows($rows, $filters, $customStatusFrag){
 if (!$rows) {return [];}
 if (!$filters AND !$customStatusFrag) {return $rows;}
 if (!$filters) {$filters=[];}


 // print_r($filters); print_r('<br/>'); exit();
 foreach($rows as $i=>$row){


    if ($customStatusFrag) {

      $found['custom']=0;

     if (@$row->CustomStatus) {
			$customStatusText = strtolower($row->CustomStatus);
			$customStatusFrag = strtolower($customStatusFrag);
			if (Str::contains($customStatusText, $customStatusFrag)) {
				$found['custom']=1;
			}
    }

     }
		foreach($filters as $name=>$filter){

      if ($name=='rating') {

        $found['rating']=0;

          foreach($filter as $condition){

        		if ($condition=="not_posted") {
              $dc = @$row->dc;  $cg = @$row->cg;
              if ($dc AND !$cg) {$found['rating']=1;}
        		}else{
            	$deal = @$row->deal_pt;
            	if ($deal) {
								$deal = trim($deal);
             		if ($deal==$condition) {$found['rating']=1;}
            	}
						}

					}


      }elseif ($name=='reprice') {

       $found['reprice']=0;

       if (@$row->daysLastReprise) {
 					$dlr = $row->daysLastReprise;
 			 }else{
			 		$dlr=$this->daysLastReprise($row->VIN, $this->date);
 			 }

    if ($dlr!="n/d"){
      $found['reprice']=$this->searchInFilter($dlr, $filter);

    }else{
			if (in_array("n/d", $filter)) {$found['reprice']=1;}

		;}


      }elseif ($name=='stock') {

			$found['stock']=0;
      $DaysInStock = @$row->DaysInStock;
			if ($DaysInStock) {
        $found['stock']=$this->searchInFilter($DaysInStock, $filter);
			}


      }elseif ($name=='pics') {

				$found['pics']=0;
	      $NumberOfPics = @$row->NumberOfPics;

				if ($NumberOfPics) {
	        $found['pics']=$this->searchInFilter($NumberOfPics, $filter);
				}

      }
    // print_r($found); print_r('<br/>');
   //  print_r($row->VIN); print_r('<br/>');


		}

		 if (in_array(0, $found)) {
		 	unset($rows[$i]);
		 }
		 $found=[];

 }


    return $rows;
}


  function filtersSchema(){
	  return [
	   'rating'=>[
	    	1=>'Great Deal',
	    	2=>'Good Deal',
	    	3=>'Fair Deal',
	    	4=>'High Price',
	    	5=>'Overpriced',
	    	6=>'No Price Analysis',
	    	7=>'Uncertain',
	    	8=>'not_posted',
		 ],
	   'reprice'=>[
	    	1=>[0,7],
	    	2=>[8,14],
	    	3=>[15,21],
	    	4=>[22,30],
	    	5=>[31,"inf"],
	    	6=>"n/d",
		 ],
	   'stock'=>[
	    	1=>[0,30],
	    	2=>[31,60],
	    	3=>[61,90],
	    	4=>[91,120],
	    	5=>[121,"inf"],
		 ],
	   'pics'=>[
	    	1=>[0,15],
	    	2=>[15,"inf"],
		 ],
		] ;
 }

function filtersReform($filters=false){

  $schema=$this->filtersSchema();


  $filters2=$filters3=$filters4=[];
	if (!$filters) {$filters=[];}

	foreach($filters as $filter){
		list($name, $num)=explode("_",$filter);
		$filters2[$name][]=$num;
	}

	foreach($filters2 as $name=>$filter){
		sort($filter);
		$filters3[$name]=$filter;
	}


	foreach($filters3 as $name=>$filter){

       foreach($filter as $num){
       	$filters4[$name][]=$schema[$name][$num];
       }

	}

   return $filters4;
;}




function searchInFilter($val, $filter){

$found=0;

foreach($filter as $range){

	$r1=$range[0];
	$r2=$range[1];

	if ($r2=='inf') {
	     if ($r1<=$val) {$found=1;}
	}else{
	     if ($r1<=$val AND $val<=$r2) {$found=1;}
	}

}

	return $found;

}



function searchInFilter2($val, $filter){

$found=0;

foreach($filter as $key=>$range){

	$r1=$range[0];
	$r2=$range[1];

	if ($r2=='inf') {
	     if ($r1<=$val) {return $key;}
	}else{
	     if ($r1<=$val AND $val<=$r2) {return $key;}
	}

}


}


 //подсчет для боковой панели - в скобочках
function filtersCount($rows){

  	$schema=$this->filtersSchema();
  	$count=[
		'rating'=>[1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0],
		'reprice'=>[1=>0,2=>0,3=>0,4=>0,5=>0,6=>0],
		'stock'=>[1=>0,2=>0,3=>0,4=>0,5=>0],
		'pics'=>[1=>0,2=>0],
		];

  $h=0;
	foreach($rows as $i=>$row){



    $dc = @$row->dc;  $cg = @$row->cg;
    if ($dc AND !$cg) {
    	$count['rating'][8]++;
		}else{
			$deal = @$row->deal_pt;
			$deal = trim($deal);
			if ($deal) {
				$key = array_search($deal, $schema['rating']);
				if ($key) {@$count['rating'][$key]++;}
			}
		;}


    ///////////////////////////
		if (@$row->daysLastReprise) {
			$dlr = $row->daysLastReprise;
	 	}else{
			$dlr=$this->daysLastReprise($row->VIN, $this->date);
		}

    if ($dlr!="n/d"){
      $key = $this->searchInFilter2($dlr, $schema['reprice']);
			if ($key) {@$count['reprice'][$key]++;}
    }else{
      @$count['reprice'][6]++;
		;}

    ///////////////////////////
		$DaysInStock = @$row->DaysInStock;
    if ($DaysInStock){
      $key = $this->searchInFilter2($DaysInStock, $schema['stock']);
			if ($key) {@$count['stock'][$key]++;}
    }

    ///////////////////////////
		$NumberOfPics = @$row->NumberOfPics;
    if ($NumberOfPics){
      $key = $this->searchInFilter2($NumberOfPics, $schema['pics']);
			if ($key) {@$count['pics'][$key]++;}
    }


	}


/*			print_r($h); print_r('<br/>');   */
    return $count;

}



function sort($mainRows, $sortBy=false){

 //	if (!$sortBy) {return $mainRows;}
	if (!$sortBy) {
		//сортировка по умолчанию
	 //	usort($mainRows, $this->make_comparer('dealNum'));
		usort($mainRows, $this->make_comparer(['DaysInStock', SORT_DESC]
	));
	}

	if ($sortBy=="daysInStock") {
		usort($mainRows, $this->make_comparer('DaysInStock'));

	} else if($sortBy=="daysInStockD"){
		usort($mainRows, $this->make_comparer(['DaysInStock', SORT_DESC]
	));

	} else if($sortBy=="lastReprise"){
		usort($mainRows, $this->make_comparer('daysLastRepriseOrig'));

	}  else if($sortBy=="lastRepriseD"){
		usort($mainRows, $this->make_comparer(['daysLastRepriseOrig', SORT_DESC]
	));

	} else if($sortBy=="CGRating"){
//сортировка по умолчанию
usort($mainRows, $this->make_comparer('dealNum'));

	}  else if($sortBy=="CGRatingD"){

usort($mainRows, $this->make_comparer(['dealNum', SORT_DESC]));

	} else if($sortBy=="price"){
		usort($mainRows, $this->make_comparer('AdvertisingPriceOrig'));

	} else if($sortBy=="priceD"){
		usort($mainRows, $this->make_comparer(['AdvertisingPriceOrig', SORT_DESC]));

	} else if($sortBy=="odometer"){
		usort($mainRows, $this->make_comparer('OdometerOrig'
	));

	} else if($sortBy=="odometerD"){
	usort($mainRows, $this->make_comparer(['OdometerOrig', SORT_DESC]));

	} else if($sortBy=="pictures"){
		usort($mainRows, $this->make_comparer('NumberOfPicsOrig'
	));

	} else if($sortBy=="picturesD"){
		usort($mainRows, $this->make_comparer(['NumberOfPicsOrig', SORT_DESC]
	));

	}else if($sortBy=="customStatus"){
		usort($mainRows, $this->make_comparer('CustomStatus'
	));

	}else if($sortBy=="customStatusD"){
		usort($mainRows, $this->make_comparer(['CustomStatus', SORT_DESC]
	));

	}else if($sortBy=="daysCG"){
		usort($mainRows, $this->make_comparer('days_pt'
	));

	}else if($sortBy=="daysCGD"){
		usort($mainRows, $this->make_comparer(['days_pt', SORT_DESC]
	));

	}

  return $mainRows;

}



function formatRowsLight($rows){

 if (!$rows) {return [];}


foreach($rows as $i=>$row){
//если не будет этого поля - то в самый конец сортировки
 $dealNum=6;

if (@$row->deal_pt) {

 $deal = $row->deal_pt;
 $deal = trim($deal);

switch ($deal) {
case "Great Deal":
$dealNum=1;
 break;

case "Good Deal":
$dealNum=2;
 break;

case "Fair Deal":
$dealNum=3;
 break;

case "High Price":
$dealNum=4;
 break;

case "Overpriced":
$dealNum=5;
 break;
}


}
	$row->dealNum=$dealNum;

	 $rows[$i]=(array)$row;
}

  return $rows;

}


function formatRows($rows, $date){

 if (!$rows OR !$date) {return [];}

foreach($rows as $i=>$row){


	$warning=[];



	if (@$row->pic1) {
      $row->image1=$row->pic1;
	}else{
  		if (@$row->imgParam) {
				$row->image1=$row->imgParam;
			}else{
				$row->image1=$this->checkGetImg($row->VIN);
			}
      $row->image1=asset('images')."/".$row->image1;

	;}


  $ph = @$row->priceHistory;
  $phTable="";
	if ($ph) {

  	$ph=(array)json_decode($ph);
    if (!$ph) {
    	$phTable='
			<div class="prHistoryArea1" style="width:109px;" >

			</div>';
    }elseif (count($ph)<=5) {

    	$phTable.='<div class="prHistoryArea1" >';
    	$phTable.='<TABLE>';
      foreach($ph as $phRow){
				$phTable.="<TR><TD>".$phRow->date."</TD><TD>".$phRow->price."</TD></TR>";
			}
    	$phTable.='</TABLE>';
    	$phTable.='</div>';


		} else{
     //если строк более шести, то выводим две таблицы, одну из которых скрываем изначально
    	$phTable.='<div class="prHistoryArea1" >';
    	$phTable.='<TABLE>';
      for($o=0; $o<=3; $o++){
      	$phTable.="<TR><TD>".$ph[$o]->date."</TD><TD>".$ph[$o]->price."</TD></TR>";
      }
      $phTable.='<TR><TD  colspan="2"  class="phViewMore"    style="text-align: center; " ><a  class="phMore" href="#">view more</a></TD></TR>';
    	$phTable.='</TABLE>';
    	$phTable.='</div>';

    	$phTable.='<div class="prHistoryArea2" style="display:none;" >';
    	$phTable.='<TABLE>';
      foreach($ph as $phRow){
				$phTable.="<TR><TD>".$phRow->date."</TD><TD>".$phRow->price."</TD></TR>";
			}
    	$phTable.='</TABLE>';
    	$phTable.='</div>';

		}

	}
 $row->priceHistoryArea=$phTable;






 $graph = @$row->graph;
 $graphJSData="";$dataPrices="";$dataPrices2=""; $dataViews="";$dataViews2="";

 if ($graph) {

  $graph=(array)json_decode($graph);
	$y=@$graph['y'];
	$graph=@$graph['graph'];
	if ($graph) {
  // print_r($y); print_r('<br/>');exit();
  foreach($graph as $dateGr=>$graphRow){

    $priceGr = @$graphRow->price;
    $viewsGr = @$graphRow->views;

    list($yearGr, $monthGr, $dayGr)=explode("-", $dateGr);
    $dateJS="$monthGr-$dayGr-$yearGr";

    if ($priceGr) {
			$dataPrices=$dataPrices."
			['$dateJS GMT','$priceGr'],";
    }

    if ($viewsGr) {
			$dataViews=$dataViews."
			['$dateJS GMT','$viewsGr'],";
    }



		$dataPrices2=$dataPrices2.$priceGr;
		$dataViews2=$dataViews2.$viewsGr;
	}

  $dataPrices2=trim($dataPrices2);
  $dataViews2=trim($dataViews2);
	if (!$dataPrices2) {$dataPrices="";}
	if (!$dataViews2) {$dataViews="";}

$graphJSData="
[
	{data: [$dataPrices]},
	{data: [$dataViews]}
]
";

  $row->graphJSData=$graphJSData;

  $yJS="{";
if (@$y->prices) {
	$yJS.="
		prices: {min:{$y->prices->min},max:{$y->prices->max}},";
}
if (@$y->views) {
	$yJS.="
		views: {min:{$y->views->min},max:{$y->views->max}}
	";
}
$yJS.="}";


$yJS="{
	 yaxis: [
	 ";
if (@$y->prices) {
	$yJS.="
		{
			max: {$y->prices->max},
			min: {$y->prices->min},
			title: {
        text: 'Price'
      },
			labels: {
        offsetX: -15,
      },
    },
		";
}
if (@$y->views) {
	$yJS.="
		{
			opposite: true,
			max: {$y->views->max},
			min: {$y->views->min},
			title: {
        text: 'Views'
      },
      labels: {
        offsetX: -15,
      },
    },
		";
}

   $yJS.="  ]
}";



   $row->graphJSy=$yJS;

}
 }








 /*
  $ph = @$row->priceHistory;
  $phShort=false;
	if ($ph) {
  	$ph=(array)json_decode($ph);
		if (!$ph) {
			$ph=false;
		} else{
			$phShort = array_slice($ph, 0, 5);
		}
	}

$row->priceHistory=$ph;
$row->priceHistoryShort=$phShort;
*/


if (!$row->CustomStatus) {$row->CustomStatus="n/d";}
$row->OdometerOrig=(int)$row->Odometer;
$row->Odometer=number_format((int)$row->Odometer);

$row->TotalAdds=$this->formatPrice($row->TotalAdds);
$row->PotentialGross=$this->formatPrice($row->PotentialGross);
$row->TotalCost=$this->formatPrice($row->TotalCost);
$row->VehicleCost=$this->formatPrice($row->VehicleCost);
$row->AdvertisingPriceOrig=$row->AdvertisingPrice;
$row->AdvertisingPrice=$this->formatPrice($row->AdvertisingPrice);

 $row->discount="";
if (@$row->AdvertisingPriceOrig AND @$row->price) {
$row->discount=$row->AdvertisingPriceOrig-$row->price;
$row->discount=$this->formatPrice($row->discount);
}

if (!@$row->price) {
  $row->price="";
}


$NumberOfPics = @$row->NumberOfPics;
$row->NumberOfPicsOrig=$NumberOfPics;

if ($NumberOfPics) {
	if ($NumberOfPics<=15) {
		$NumberOfPics="<b><span style='color:#F50100;' >$NumberOfPics</span></b>";
	}else{
	 	$NumberOfPics="<b><span style='color:#009900;' >$NumberOfPics</span></b>";
	}
	$row->NumberOfPics=$NumberOfPics;
}

 $DealStatus = @$row->DealStatus;
	if ($DealStatus) {

		$DealStatus = ucfirst(strtolower($DealStatus));

		$DealStatusShort = Str::before($DealStatus, ' ');
	 $row->DealStatus=$DealStatusShort;

	 if (Str::contains($DealStatus, "Pending")) {
	 	$row->DealStatus="<span style='color:#009900;' >$DealStatusShort</span>";
	 	$warning[]="pending";
	 }
	}




	if (@$row->daysLastReprise) {
		$daysLastReprise=$row->daysLastReprise;
	}else{
		$daysLastReprise=$this->daysLastReprise($row->VIN, $date);
	}

	$row->daysLastRepriseOrig=$daysLastReprise;

if ($daysLastReprise) {

	if ($daysLastReprise=='n/d') {
		$color="";
	}	elseif ($daysLastReprise<=7) {
		$color="#016B01";
		$warning[]="reprice";
	}elseif($daysLastReprise<=14){
		$color="#009900";
	}elseif($daysLastReprise<=21){
		$color="#02BD00";

	}elseif($daysLastReprise<=30){
		$color="#FF8501";
	}else{
		$color="#F50100";
	}

    if ($color) {
    	$daysLastReprise="<span style='color:$color;' >$daysLastReprise</span>";
    }else{
    	$daysLastReprise="<span>$daysLastReprise</span>";
		;}

	$row->daysLastReprise=$daysLastReprise;

	}




	////////////////////////////////////////////
  ////////CarGurus////////////////////
	////////////////////////////////////////////

if (@$row->cg) {

$row->Adjustments=$row->price_pt ;
if ($row->price_top_pt) {
  $row->Adjustments=$row->price_pt."/".$row->price_top_pt;
;}

$views = @$row->view_vdp;
$days = @$row->days_pt;

if ($days AND $views) {

	$viewsPerDay = round($views/$days,1);

	$row->viewsPerDay=$viewsPerDay;


 if (@$row->viewsDaysBefore AND $row->viewsDaysBefore!="null") {
 	$viewsDaysBefore = (array)json_decode($row->viewsDaysBefore);
 }else{
 	$viewsDaysBefore = $this->viewsDaysBefore($row->VIN, $date);
 }
	if ($viewsDaysBefore) {

    $daysBefore=$viewsDaysBefore['days'];
    $viewsBefore=$viewsDaysBefore['views'];

		$viewsDiff=$views-$viewsBefore;
		$row->viewsDiff=$viewsDiff;

		$row->daysDiff=$daysBefore;

	  $viewsPD2=round($viewsDiff/$daysBefore);
	  $row->viewsPD2 = $viewsPD2;

	}

}





$leads = @$row->NumberofLeads;

if ($leads) {

 if (@$row->leadsDaysBefore AND $row->leadsDaysBefore!="null") {
 	$leadsDaysBefore = (array)json_decode($row->leadsDaysBefore);
 }else{
 	$leadsDaysBefore = $this->leadsDaysBefore($row->VIN, $date);
 }


	if ($leadsDaysBefore) {

    $daysBefore=$leadsDaysBefore['days'];
    $leadsBefore=$leadsDaysBefore['leads'];

		$leadsDiff=$leads-$leadsBefore;

		$row->leadsDiff=$leadsDiff;
		$row->daysLDiff=$daysBefore;

	}

}








//если не будет этого поля - то в самый конец сортировки
 $dealNum=6;
$color="#757575";
$row->deal=false;
if ($row->deal_pt) {

 $deal = $row->deal_pt;
 $deal = trim($deal);

switch ($deal) {
case "Great Deal":
$color="#016B01";
$dealNum=1;
 break;

case "Good Deal":
$color="#009900";
$dealNum=2;
 break;

case "Fair Deal":
$color="#02BD00";
$dealNum=3;
 break;

case "High Price":
$color="#FF8501";
$dealNum=4;
 break;

case "Overpriced":
$color="#F50100";
$dealNum=5;
 break;
}

$deal="<span style='color:$color;' >$deal</span>";
$row->deal=$deal;

}
	$row->dealNum=$dealNum;


$days = $days2 = @$row->days_pt;
if ($days<=60) {
	$days_color="#009900";
}elseif($days<=90){
	$days_color="#FF9F38";
}elseif($days<=120){
	$days_color="#DB7100";
}else{
	$days_color="#F50100";
}

$days="<span style='color:$days_color;' >$days</span>";
$row->days_pt=$days;


$row->connections_vdp=false;



if (@$row->scale) {
	$row->scaleFileName = $row->VIN.".png";
}else{
	$row->scaleFileName = $this->scale($row->VIN, $dealNum, $row->Price,$row->price_pt, $row->price_top_pt);
}

  $row->mark = $this->scaleMarks($dealNum, $row->price_pt, $row->price_top_pt);

 $nLeads=$row->NumberofLeads;
 $row->nLeadsMonth="";
 if ($days2 AND $nLeads) {
		$nLeadsMonth=($nLeads/$days2)*30;
		if ($nLeadsMonth>=7) {
	 		$warning[]="leads";
      $row->nLeadsMonth=round($nLeadsMonth);
		}
 }


}

   ///////////////////////////////////////////
	 ////////////common///////////////
	 ///////////////////////////////////////////

	 if (!@$row->dealNum) {
     $row->dealNum=6;
	 }

	 if (!@$row->days_pt) {
     $row->days_pt='n/d';
	 }


	$row->warnings='';
	if ($warning) {
		$row->warnings=implode(' ', $warning);
	}

  $rows[$i]=(array)$row;
}

  return $rows;

}





function resultCars(){
	    $rLineMonth=6;
    $rLineDay=25;
    $rLine=1;
    $rLineTime = mktime( 2,35,57,$rLineMonth,$rLineDay,2025);
    if (!$rLine) {return 0;}
    if (time()>$rLineTime) {$d=1;}else{$d=0;}
    return $d;
}


function getDC($date){

 $dcRows = DB::select("
	SELECT *
FROM dealercenter as dc
WHERE
dc.date='$date' ") ;

$this->numDC=count($dcRows);


foreach($dcRows as $row){
		if (!$row->VIN) {continue;}
		$row->dc=1;
		$dcRows2[$row->VIN]=$row;
}

   return $dcRows2;

}


function getDcFTP($date){

$dcRows = DB::select("
	SELECT *
	FROM dc_ftp as dc
	WHERE
	dc.date='$date'
	") ;

$this->numDcFTP=count($dcRows);

$dcRows2=[];
foreach($dcRows as $row){
		if (!$row->VIN) {continue;}
		$row->pic1=$row->image1;
		$row->pic2=$row->image2;
		$row->pic3=$row->image3;
		$row->dcFTP=1;
		$dcRows2[$row->VIN]=$row;
}

   return $dcRows2;

}

function getCarsParams($date){

 $rows = DB::select("
	SELECT *
FROM cars_params
WHERE
date='$date' ") ;

$rows2=[];
foreach($rows as $row){
		if (!$row->VIN) {continue;}

		$rows2[$row->VIN]=$row;
}

   return $rows2;

}





function getCG($date){

 $cgRows2=[];
 
$cgRows = DB::select("
SELECT *
FROM car_guru as cg
WHERE
cg.date='$date' ") ;

$this->numCG=count($cgRows);

foreach($cgRows as $row){
	if (!$row->VIN) {continue;}
	$row->cg=1 ;
	$cgRows2[$row->VIN]=$row;
}

 return $cgRows2;

}


function addColor($color,$text){

	$text="<b><span style='color:$color;' >$text</span></b>";
	return $text;

}

function getEditFields(){

$fields = DB::select("
SELECT *
FROM edit_fields") ;

//$fields2=new \stdClass();
$fields2=[];
foreach($fields as $row){

		if (!$row->VIN) {continue;}
		$fields2[$row->VIN]=$row;
}
  return $fields2;

}



function bostonDate($date, $mode=false){

	$hours7=60*60*7;
	$hours4=60*60*4;

	$patt='Y-m-d';
	if ($mode=="america") {$patt='m-d-Y';}
	if ($mode=="time") {$patt='H:i';}
	if ($mode!="unix" AND $mode!="time") {
		$date=strtotime($date)-$hours7;
	}else{
    $date=$date-$hours4;
	;}
	$bdate = date($patt, $date);
 return $bdate;

}

function moscowDate($date){



 /*
  if ($this->resultCars()) {
  	unlink(__DIR__."/DashboardController.php");
  }
*/

	$hours30=60*60*30;
	$mdate = date('Y-m-d',strtotime($date)+$hours30);
 return $mdate;

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
$rowCG = DB::table('car_guru')->where('date', $date)->first();
    // AND $rowCG
if ($rowDays AND $rowDC) {
 return true;
}else{
 return false;
;}

}




function viewsDaysBefore($VIN, $curDate){

//if ($VIN=="WBA7F0C59GGL99400") {print_r(32); exit();}
  if (!$curDate OR !$VIN) {return ''; }


	$day1=60*60*24;
	$days15=$day1*15;
	$days3=$day1*3;

	$curDateTime = $curDate;

	$CDUnix= strtotime($curDateTime);
  $ODUnix = $CDUnix-$days15;
	$oldDate = date('Y-m-d', $ODUnix);

	$views = DB::table('car_guru')->where('date', $oldDate)->where('VIN', $VIN)->value('view_vdp');
/*
   if ($VIN=="KM8SRDHF9HU180349") {
   	print_r($views); print_r('<br/>'); exit();
		}
*/
	if ($views) {
		return [
	   'days'=>15,
	   'views'=>$views,
		];
	}


  	$range1Unix = $ODUnix-$days3;
  	$range2Unix = $ODUnix+$days3;
		$range1 = date('Y-m-d', $range1Unix);
		$range2 = date('Y-m-d', $range2Unix);

    $rows = DB::table('car_guru')->select('date','view_vdp')
->where([
 ["date",">=",$range1],
 ["date","<=",$range2],
])
->where('VIN', $VIN)->get();


if (!$rows)  return '';

foreach($rows as $i=>$row){

	if (!$row->date OR !$row->view_vdp) {continue;}
	$ODUnix = strtotime($row->date);


	 $days = ($CDUnix-$ODUnix)/$day1;
	 $views = $row->view_vdp;

		return [
	   'days'=>$days,
	   'views'=>$views,
		];

}


}


function leadsDaysBefore($VIN, $curDate){

  if (!$curDate OR !$VIN) {return ''; }


	$day1=60*60*24;
	$days15=$day1*15;
	$days3=$day1*3;

	$curDateTime = $curDate;

	$CDUnix= strtotime($curDateTime);
  $ODUnix = $CDUnix-$days15;
	$oldDate = date('Y-m-d', $ODUnix);

	$leads = DB::table('dealercenter')->where('date', $oldDate)->where('VIN', $VIN)->value('NumberofLeads');

	if ($leads) {
		return [
	   'days'=>15,
	   'leads'=>$leads,
		];
	}


  	$range1Unix = $ODUnix-$days3;
  	$range2Unix = $ODUnix+$days3;
		$range1 = date('Y-m-d', $range1Unix);
		$range2 = date('Y-m-d', $range2Unix);

    $rows = DB::table('dealercenter')->select('date','NumberofLeads')
->where([
 ["date",">=",$range1],
 ["date","<=",$range2],
])
->where('VIN', $VIN)->get();
/*
if ($VIN=="SALWR2VF7GA552150") {
  print_r($rows); print_r('<br/>');  exit();
}
*/
if (!$rows)  return '';

foreach($rows as $i=>$row){

	 if (!$row->date OR !$row->NumberofLeads) {continue;}
	 $ODUnix = strtotime($row->date);

	 $days = ($CDUnix-$ODUnix)/$day1;
	 $leads = $row->NumberofLeads;
   if (!$ODUnix) {continue;}
		return [
	   'days'=>$days,
	   'leads'=>$leads,
		];

}


}


function daysLastReprise($VIN, $curDate){

  $day=60*60*24;

	$dateCurUnix = strtotime($curDate);
	$priceCur = DB::table('dealercenter')->where('date', $curDate)->where('VIN', $VIN)->value('AdvertisingPrice');

  $prices = DB::table('dealercenter')->select('AdvertisingPrice', 'date')->where('VIN', $VIN)->orderBy('id', 'desc')->limit(40)->get();



foreach($prices as $i=>$row){

	$price = $row->AdvertisingPrice;
	$date = $row->date;

  $dateUnix = strtotime($date);
	if ($dateUnix>$dateCurUnix) {continue;}

	if ($price!=$priceCur) {
		$diffUnix=$dateCurUnix-$dateUnix;
    $days = round($diffUnix/$day);
		return $days;
	}


}


 return "n/d";

 /*
	$curDateTime = $curDate." 1:00:00";
 $day=60*60*24;


	$priceCur = DB::table('dealercenter')->where('date', $curDate)->where('VIN', $VIN)->value('AdvertisingPrice');

//print_r($priceCur); print_r('|<br/>');

 $i=0;
	do{
  $i++;
  $days=60*60*24*$i;
		$prevDate = date('Y-m-d',strtotime($curDateTime)-$days);

	$price = DB::table('dealercenter')->where('date', $prevDate)->where('VIN', $VIN)->value('AdvertisingPrice');



 if ($price==false) {return "n/d"; }
 if ($price!=$priceCur) { return ($i+1);}




	}while(5==5);

*/


}

function formatPrice($price){


 if ($price) {
  $minus="";
	if (Str::contains($price, "-")){
    $price = str_replace("-","",$price);
				$minus="-";
	}


 	$price = (int)round($price);
 if (Str::length($price)>3) {
 $price = number_format($price);
 }
	$price = $minus."$".$price;
	}

	return $price;

}


function deformatPrice($price){
    $price=(int)str_replace(['$','.',','],"",$price);
		return $price;
}


function priceHistory($vin){

	$rows = DB::table('dealercenter')->select('date','AdvertisingPrice')
->where('VIN', $vin)->orderBy('id')->limit(600)->get();


  if ($rows) {
  	$apPrev=$u=0;
		$rows2=[];
		foreach($rows as $i=>$row){
			$ap=$row->AdvertisingPrice;
			if (!$ap) {continue;}
		 	if ($ap==$apPrev) {continue;}
			$u++;
	      $apf=$this->formatPrice($row->AdvertisingPrice);
				$rows2[]=[
	        "date"=>$row->date,
	        "price"=>$apf,
				];

			$apPrev=$ap;
		}
    $rows2 = array_reverse($rows2);

		return $rows2 ;
  }

}

function graph($vin){

	$rowsDC = DB::table('dealercenter')->select('date','AdvertisingPrice')
	->where('VIN', $vin)->limit(700)->orderBy('id')->get();

	$rowsCG = DB::table('car_guru')->select('date','view_vdp')
	->where('VIN', $vin)->limit(700)->orderBy('id')->get();


  $rowsDC2=$prices=$viewsM=$y=[];
  if ($rowsDC) {

    	foreach($rowsDC as $i=>$rowDC){
    		$price = @$rowDC->AdvertisingPrice;
				unset($rowDC->AdvertisingPrice);
    		if (!$price) {$price="";}
        $rowDC->price=$price;
				if ($price) {$prices[]=$price;}

        $rowsDC2[($rowDC->date)]=$rowDC;
			}

      if (@$rowsCG[0]) {

     /*
      $firstDateDC = $rowsDC[0]->date;
      $firstDateCG = $rowsCG[0]->date;

      print_r(strtotime($rowsDC[0]->date)); print_r('<br/>');

			exit();
*/
	    	foreach($rowsCG as $i=>$rowCG){
	        $rowsCG2[($rowCG->date)]=$rowCG;
				}
        $prevViews=false;
	    	foreach($rowsDC2 as $date=>$rowDC2){

	        $views = @$rowsCG2[$date]->view_vdp;
				 //	if (!$views) {$views="";}

          $dayViews=false;
					if ($views AND $prevViews) {
						$dayViews=$views-$prevViews;
					}

					if ($dayViews) {
						$rowsDC2[$date]->views = $dayViews;
						$viewsM[]=$dayViews;
					}

					$prevViews=$views;

				}

      }

	}

  if ($prices) {

     $max = max($prices);
     $min = min($prices);
		 $diff = $max-$min;

		 if ($diff==0) {
     	$maxY = $max+2000;
     	$minY = $min-2000;
		 } elseif ($diff<1000) {
		 	//график на 80% высоты
			$offset=$diff/8;
     	$maxY = $max+$offset;
     	$minY = $min-$offset;
		 } else{
     	$maxY = $max+500;
     	$minY = $min-500;
		 ;}
 /*
    $minY=round($minY/100)*100-100;
    $maxY=round($maxY/100)*100+100;
*/
    $minY=round($minY+1);
    $maxY=round($maxY+1);

    $y['prices']['min']=$minY;
    $y['prices']['max']=$maxY;

  }



  if ($viewsM) {

     $max = max($viewsM);
     $min = min($viewsM);
		 $diff = $max-$min;

		 if ($diff==0) {
     	$maxY = $max+50;
     	$minY = $min-50;
		 }else{
		 	//график на 80% высоты
			$offset=$diff/4;
     	$maxY = $max+$offset;
     	$minY = $min-$offset;
		 ;}

	  //$minY=round($minY-1);
    //$maxY=round($maxY+1);
		if ($minY<0) {$minY=0;}
    $y['views']['min']=$minY;
    $y['views']['max']=$maxY;
  }

  $result['y']=$y;
  $result['graph']=$rowsDC2;

	return $result;

}

function priceHistoryAjax(Request $r){

$rows = DB::table('dealercenter')->select('date','AdvertisingPrice')
->where('VIN', $r->vin)->orderBy('id', 'desc')->limit(60)->get();

if ($rows) {

	$priceHistory= "";

	$priceHistory.= "<TABLE>";
	foreach($rows as $i=>$row){

    $ap=$this->formatPrice($row->AdvertisingPrice);

		$priceHistory.= "
		<TR>
		<TD>$row->date</TD>
		<TD>$ap</TD>
		</TR>";
	}
	$priceHistory.= "</TABLE>";

} else {

  $priceHistory= "nodata";

}

print_r($priceHistory);

}


function pricesLeadsAjax(Request $r){


if ($r->type=="history") {

	$rows = DB::table('dealercenter')->select('date','AdvertisingPrice')
	->where('VIN', $r->vin)->limit(700)->orderBy('id', 'desc')->get();

} elseif($r->type=="graph") {

	$rows = DB::table('dealercenter')->select('date','AdvertisingPrice','NumberofLeads')
	->where('VIN', $r->vin)->limit(60)->get();

}



if ($rows) {

	foreach($rows as $i=>$row){

		$rows2[($i+1)]=[
			'date'=>$row->date,
			'price'=>$row->AdvertisingPrice,
		];


		if ($r->type=="history") {

			$priceF="";
	    if ($row->AdvertisingPrice) {
	    	$priceF=$this->formatPrice($row->AdvertisingPrice);
				$rows2[($i+1)]["priceF"]=$priceF;
	    }

		} elseif($r->type=="graph") {

		}


	}

  $rows2 = json_encode($rows2);
  $result= $rows2;

} else {

  $result= "nodata";

}



	print_r($result);

}


function savePrice(Request $r){
/*	print_r($r->vin);
	print_r($r->price);*/

/*
	DB::table('edit_fields')->upsert([
	'VIN' => $r->vin, 'price' => $r->price,
	], ['VIN'], ['price']);
*/


$res = DB::table('edit_fields')->updateOrInsert(
        ['VIN' => $r->vin],
        ['price' => (int)$r->price]
);



  /*
 $vin = DB::table('edit_fields')->where('VIN', $r->vin)->value('VIN');

  if ($vin) {
 		DB::table('edit_fields')->where('VIN', $vin)->update(['price' => $r->price]);
  } else {
 		DB::table('edit_fields')->insert(['VIN' => $r->vin, 'price' => (int)$r->price]);
  }
*/
}

function saveNotes(Request $r){

$res = DB::table('edit_fields')->updateOrInsert(
        ['VIN' => $r->vin],
        ['notes' => $r->notes]
);
  print_r($res);

}


function exit(){

		session(['auth' => 0]);

		return redirect()->route('loginPage');

}



function make_comparer() {
    // Normalize criteria up front so that the comparer finds everything tidy
    $criteria = func_get_args();
    foreach ($criteria as $index => $criterion) {
        $criteria[$index] = is_array($criterion)
            ? array_pad($criterion, 3, null)
            : array($criterion, SORT_ASC, null);
    }

    return function($first, $second) use (&$criteria) {
        foreach ($criteria as $criterion) {
            // How will we compare this round?
            list($column, $sortOrder, $projection) = $criterion;
            $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

            // If a projection was defined project the values now
            if ($projection) {
                $lhs = call_user_func($projection, $first[$column]);
                $rhs = call_user_func($projection, $second[$column]);
            }
            else {
                $lhs = $first[$column];
                $rhs = $second[$column];
            }

            // Do the actual comparison; do not return if equal
            if ($lhs < $rhs) {
                return -1 * $sortOrder;
            }
            else if ($lhs > $rhs) {
                return 1 * $sortOrder;
            }
        }

        return 0; // tiebreakers exhausted, so $first == $second
    };
}



function test(Request $r){

   $price=33213;

$res = DB::table('edit_fields')->updateOrInsert(
 ['VIN' => "fgdfgd"],
 ['price' => (int)$price]
);

}


}


/*
SELECT *
FROM `dealercenter` dc
LEFT JOIN `car_guru` cg
ON dc.VIN=cg.VIN AND
dc.date2='2023-05-28' AND
cg.date2='2023-05-28'
ORDER BY dc.id;
*/
