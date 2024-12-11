<?php

    //  print_r(44); print_r('<br/>'); exit();

				$localServer=0;
				$insertToDB=1;


include_once("dbAndrey.php");

  $db = new dbAndrey([
		'dbname'=>'parsing',
		'user'=>'root',
		'pass'=>($localServer) ?  ("") :  ("Si1jieceaf5yeise"),

		]);

if (!$localServer) {
downloadXlsx();
 }


require_once __DIR__ . '/simplexlsx.class.php';

if ( $xlsx = SimpleXLSX::parse('last.xlsx')) {


 $u=0; $numCar=0;

	foreach($xlsx->rows() as $i=>$row){

  // print_r($row); print_r('<br/>');

		if (strpos($row[0], "Run Date")!==false) {
    // print_r($row[0]); print_r('<br/>');

      $shabl="/([0-9]{1,2})\/([0-9]{2})\/([0-9]{4})/";
      preg_match($shabl, $row[0], $date);

					//	$month = $matches[1]+0;
						$month = $date[1];
						$day = $date[2];
						$year = $date[3];

		}



			 $shabl="/[a-zA-Z0-9]{8,40}/";
    preg_match($shabl, $row[21], $matchesVin);

			 $shabl="/^[0-9]{1,3}-[0-9]{3,5}$/";
    preg_match($shabl, trim($row[0]), $matchesStockNumber);

			if ($matchesVin OR $matchesStockNumber) {



   if (!$day OR !$month OR !$year) {
      print_r("No date, Parser is exit"); print_r('<br/>'); exit();
    }


     $numCar++;

     $dateMail="$year-$month-$day";

			 $StockNumber =$row[0];
			 $Year=$row[1];
			 $Make=$row[2];
			 $Model=$row[3];
			 $Odometer=$row[5];
			 $DaysInStock=$row[6];
			 $CustomStatus=$row[7];
			 $VehicleCondition=$row[8];
			 $AdvertisingPrice=$row[9];
			 $NumberOfPics=$row[10];
			 $PotentialGross=$row[13];
			 $NumberofLeads=$row[14];
			 $PurchaseInfoMemo=$row[15];
			 $Rank=$row[16];
			 $DealStatus=$row[17];
			 $InspectionDate=$row[18];
			 $HasActiveDeposit=$row[20];
			 $VIN=$row[22];
			 $CreatedByName=$row[23];
			 $AskingPrice=$row[24];
			 $JDPowerAuctionAverage=$row[25];
			 $TotalCost=$row[26];
			 $VehicleCost=$row[27];
			 $TotalAdds=$row[30];
			 $Trim=$row[32];
			 $AutoTraderPublishDate=$row[33];


  // print_r($row); print_r('<br/>');
  // print_r($Trim); print_r('<br/>');exit();


				$q = $db->genInsert('dealercenter', [
				'number'=>$numCar,
				'dateMail'=>$dateMail,
				'date'=>date("y-m-d"),
				'time'=>date("H:i:s"),
				'StockNumber'=>$StockNumber,
				'VIN'=>$VIN,
				'CreatedByName'=>$CreatedByName,  //
				'Year'=>$Year,
				'Make'=>$Make,
				'Model'=>$Model,
				'Odometer'=>$Odometer,//
				'DaysInStock'=>$DaysInStock,
				'CustomStatus'=>$CustomStatus,//
				'VehicleCondition'=>$VehicleCondition,
				'AdvertisingPrice'=>$AdvertisingPrice,
				'NumberOfPics'=>$NumberOfPics,//
				'PotentialGross'=>$PotentialGross,
				'NumberofLeads'=>$NumberofLeads,
				'PurchaseInfoMemo'=>$PurchaseInfoMemo, ////
				'Rank'=>$Rank,
				'DealStatus'=>$DealStatus,
				'InspectionDate'=>$InspectionDate,
				'HasActiveDeposit'=>$HasActiveDeposit, //
				'AskingPrice'=>$AskingPrice,
				'JDPowerAuctionAverage'=>$JDPowerAuctionAverage,
				'TotalCost'=>$TotalCost,
				'VehicleCost'=>$VehicleCost,
				'TotalAdds'=>$TotalAdds,//
				'Trim'=>$Trim,
				'AutoTraderPublishDate'=>$AutoTraderPublishDate,
				]);

				if ($insertToDB) {
					$db->q($q);
    }

				}





	 }


    $status="success";
    if (!($day AND $month AND $year)) {
    		$status="no date in excel";
    }
    if (!$numCar) {
    		$status="no cars";
    }
    if (!$i) {
    		$status="no rows";
    }


    $q = $db->genInsert('dc_days', [
				'dateMail'=>$dateMail,
				'date'=>date("y-m-d"),
				'time'=>date("H:i:s"),
				'status'=>$status,
				'carsNumber'=>$numCar,
				'rowsNumber'=>$i,
				]);
    	$db->q($q);

    print_r("Всего cтрок: $i"); print_r('<br/>');
    print_r("Всего авто: $numCar"); print_r('<br/>');
/*	echo '<h1>$xlsx->rowsEx()</h1>';
	echo '<pre>';
	print_r( $xlsx->rowsEx() );
	echo '</pre>';*/
} else {
	echo SimpleXLSX::parse_error();
}



function downloadXlsx(){
             // imap
$hostname = '{imap-mail.outlook.com:993/imap/ssl}INBOX';
$username = 'drivemax23@outlook.com';
$password = '863Drivemax';

 /*
$hostname = '{imap.yandex.ru:993/imap/ssl}INBOX';
$username = 'andrey.bolshakov.dev@yandex.ru';
$password = 'esfesc!21';
*/
/*$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'parcer.drivemax@gmail.com';
$password = '863Drivemax';
*/

/* try to connect */
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect: ' . imap_last_error());


/* get all new emails. If set to 'ALL' instead
 * of 'NEW' retrieves all the emails, but can be
 * resource intensive, so the following variable,
 * $max_emails, puts the limit on the number of emails downloaded.
 *
 */
$emails = imap_search($inbox,'FROM "dealer-mail@dealercenter.net"');
   // print_r($emails); print_r('<br/>');
		//		exit();
/* useful only if the above search is set to 'ALL' */
$max_emails = 16;


/* if any emails found, iterate through each email */
if($emails) {

    $count = 1;

    /* put the newest emails on top */
    rsort($emails);

    /* for every email... */
				$y=0;
    foreach($emails as $email_number)
    {
      $y++;
						if ($y==2) {break;}
        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox,$email_number,0);

        /* get mail message */
        $message = imap_fetchbody($inbox,$email_number,2);

        /* get mail structure */
        $structure = imap_fetchstructure($inbox, $email_number);

        $attachments = array();

        /* if any attachments found... */
        if(isset($structure->parts) && count($structure->parts))
        {
            for($i = 0; $i < count($structure->parts); $i++)
            {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );

                if($structure->parts[$i]->ifdparameters)
                {
                    foreach($structure->parts[$i]->dparameters as $object)
                    {
                        if(strtolower($object->attribute) == 'filename')
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }

                if($structure->parts[$i]->ifparameters)
                {
                    foreach($structure->parts[$i]->parameters as $object)
                    {
                        if(strtolower($object->attribute) == 'name')
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }

                if($attachments[$i]['is_attachment'])
                {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);

                    /* 4 = QUOTED-PRINTABLE encoding */
                    if($structure->parts[$i]->encoding == 3)
                    {
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 3 = BASE64 encoding */
                    elseif($structure->parts[$i]->encoding == 4)
                    {
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }

        /* iterate through each attachment and save it */
        foreach($attachments as $attachment)
        {
            if($attachment['is_attachment'] == 1)
            {
                $filename = $attachment['name'];
                if(empty($filename)) $filename = $attachment['filename'];

                if(empty($filename)) $filename = time() . ".dat";

                /* prefix the email number to the filename in case two emails
                 * have the attachment with the same file name.
                 */

																	$fn=$email_number . "-" . $filename;
																	$fn="last.xlsx";
                $fp = fopen($fn, "w+");
                fwrite($fp, $attachment['attachment']);
                fclose($fp);
            }

        }

        if($count++ >= $max_emails) break;
    }

}

/* close the connection */
imap_close($inbox);

return 1;
;}

 //////////////////////////////////////




?>