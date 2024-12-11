<?php

 $i=0;

 //print_r($cars); print_r('<br/>'); exit();
?>

@if (@$charts)
<div class="card charts" >

      <TABLE >
      	<TR>
      		<TD class="firstCh" ><div id="chartRating" ></div></TD>
					<TD ><div id="chartReprice" ></div></TD>
					<TD ><div id="chartStock" ></div></TD>
      	</TR>
      </TABLE>
</div>

<script>
    chartRating({{$charts['rating']}})
    chartReprice({{$charts['reprice']}})
    chartStock({{$charts['stock']}})
</script>
@endif



@if (@$cars &&!count(@$cars))
	<div style="
   text-align:center;
	 font-size:20px;
	 margin-top:60px;
	">
	<i>no results</i>
    </div>
@endif


@foreach ($cars as $car)
<?php
$i++;
?>
 <div class="carsRow main-card mb-12 card" vin="{{$car['VIN']}}" num="{{$i}}" >

<div class="card-header" >
 <div class="row" style="width:100%;padding:0;margin:0;">

<div class="col-md-4" style="padding:0;margin:0;" >
<span class="number" >
№{{$i}}
</span>&nbsp;&nbsp;&nbsp;&nbsp;
<span class="carName">


@if (@$car['link_listing_vdp'])
<a href="{{$car['link_listing_vdp']}}"  target="_blank">{{$car['Year']}} - {{$car['Make']}} - {{$car['Model']}}</a>
@else
 {{$car['Year']}} - {{$car['Make']}} - {{$car['Model']}}
@endif
 </span>
</div>

<div class="col-md-4 linkDCPage"   >
@if (@$car['link_listing_vdp'])
<a href="{{$car['link_listing_vdp']}}"  target="_blank">VIN:{{$car['VIN']}}</a>
@else
 VIN:{{$car['VIN']}}
@endif
 </div>

<div class="notesSection col-md-4"    >

<div class="notesLine" vin="{{$car['VIN']}}"  >
<span>Notes</span>
<input class="notes"  name="notes" сarId="{{$car['id']}}" value="{{@$car['notes']}}">
<button class="notesSave btn btn-success" >Save</button>
</div>

   </div>

	</div>
</div>

<!-- BODY таблицы      -->
<div class="card-body" >
<div class="tab-content" >
<div  class="row1row2" >


 <div class="row columns" >

 <div >
<TABLE class="params" height="100%"   >
	<TR><TD width="100%"  style="text-align: center; " valign="middle">

@if (@$car['image1'])

<a href="{{$car['image1']}}" target="_blank"  >
<img src="{{$car['image1']}}" style="width:80%;margin-bottom:10px;border:1px solid #BDBDBD;">
</a>

@endif

	</TD></TR>
</TABLE>
</div>


 <div >
<TABLE>
	<TR><TD  colspan="2" class="tableHeader" >Car info</TD></TR>

	<TR><TD >Stock #:</TD><TD>{{ @$car['StockNumber'] ? $car['StockNumber'] : @$car['Stock#'] }} </TD></TR>
	<TR><TD >VIN:</TD><TD style="	word-break: break-all;  "  >{{$car['VIN']}}</TD></TR>
	<TR><TD>Trim:</TD><TD>{{Str::limit(@$car['Trim'], 14)}}</TD></TR>
	<TR><TD>Added by:</TD><TD >{{Str::limit(@$car['CreatedByName'], 14)}}</TD></TR>
<TR><TD>Purchase:</TD><TD >{{Str::limit(@$car['PurchaseInfoMemo'], 14)}}</TD></TR>

</TABLE>
</div>


 <div  >
<TABLE  >
	<TR><TD  colspan="2"  class="tableHeader" style="text-align: center; "  >Car info</TD></TR>
 	<TR><TD>Odometer:</TD><TD>{{ @$car['Odometer'] }}</TD></TR>
	<TR><TD>Days In Stock:</TD><TD>{{ @$car['DaysInStock'] }}</TD></TR>
	<TR><TD>CG days on market:</TD><TD><b>{!!@$car['days_pt']!!}</b></TD></TR>
	<TR><TD>Vehicle Condition:</TD><TD>{{ $car['VehicleCondition'] }}</TD></TR>
</TABLE>
</div>



 <div>
<TABLE  >
	<TR><TD  colspan="2" class="tableHeader" style="text-align: center; "  >Cost numbers</TD></TR>
	<TR><TD  >Custom Status:</TD><TD>{{@$car['CustomStatus'] }}</TD></TR>
	<TR><TD>Number Of Pics:</TD><TD>{!!@$car['NumberOfPics']!!}</TD></TR>
	<TR><TD>Vehicle Cost:</TD><TD>{{$car['VehicleCost'] }}</TD></TR>
	<TR><TD>Total Adds:</TD><TD>{{@$car['TotalAdds'] }}</TD></TR>
	<TR><TD>Total Cost:</TD><TD>{{$car['TotalCost']}}</TD></TR>

</TABLE>
</div>


 <div>
<TABLE  >
	<TR><TD colspan="2" class="tableHeader" style="text-align: center; "  >Price numbers</TD></TR>
	<TR><TD >Advertising Price:</TD><TD class="advPrice" >{{$car['AdvertisingPrice'] }}</TD></TR>
	<TR><TD>Potential Gross:</TD><TD>{{$car['discount']}}</TD></TR>
	<TR><TD>Deal Status:</TD><TD>{!!$car['DealStatus']!!}</TD></TR>
	<TR><TD>Suggestion price:</TD><TD></TD></TR>
	<TR><TD>Discount:</TD><TD class="discount" >{{@$car['discount']}}</TD></TR>
	<TR><TD colspan="2">


<div class="newPriceLine" vin="{{$car['VIN']}}" >
<span>NEW PRICE&nbsp;</span>
<input  class="price"
 newPriceId="{{$i}}"
 vin="{{$car['VIN']}}"
 warnings="{{@$car['warnings']}}"
 repriceDays="{!!@$car['daysLastRepriseOrig']!!}"
 nLeadsMonth="{!!@$car['nLeadsMonth']!!}"
 warningsNoticed="0"
 value="{{@$car['price']}}"
 >
<button class="priceSave btn btn-success" >Save</button>
</div>




</TD></TR>

</TABLE>
</div>


 <div>
<TABLE  >
	<TR><TD  colspan="2" class="tableHeader" style="text-align: center; "  >Price numbers</TD></TR>
	<TR><TD  >Leads in CRM:</TD><TD>{{@$car['NumberofLeads']}}</TD></TR>
	<TR><TD>Leads in {{@$car['daysLDiff']}} days:</TD><TD>{{@$car['leadsDiff']}}</TD></TR>
	<TR><TD>Rank:</TD><TD>{{$car['Rank']}}</TD></TR>
	<TR><TD>Rank Mileage:</TD><TD>50</TD></TR>
	<TR><TD>Days last reprise:</TD><TD><b>{!!@$car['daysLastReprise']!!}</b></TD></TR>
	<TR><TD>Has Active Deposit:</TD><TD>{{@$car['HasActiveDeposit']}}</TD></TR>

</TABLE>
</div>
<?php

 //@if (@$car['cg'])

?>
<div>
<TABLE >
	<TR><TD  colspan="2" class="tableHeader" style="text-align: center; " >Cargurus</TD></TR>
   	<TR><TD>Connections:</TD><TD>{{@$car['connections_pt']}}</TD></TR>
	<TR><TD>Views:</TD><TD>{{@$car['view_vdp']}}</TD></TR>
@if (@$car['viewsPerDay'])
	<TR><TD>Views p/d:</TD><TD>{{$car['viewsPerDay']}}</TD></TR>
 @endif
@if (@$car['viewsDiff'])
	<TR><TD>Views {{$car['daysDiff']}} days:</TD><TD>{{$car['viewsDiff']}}</TD></TR>
	<TR><TD>Views {{$car['daysDiff']}}d p/d:</TD><TD>{{$car['viewsPD2']}}</TD></TR>
 @endif


</TABLE>
</div>
 <?php

 // @endif

 ?>


@if (@$car['priceHistory'])
<div class="prHistory" >

<div class="tableHeader" style="width:100%; " >
<b>Price history</b>
</div>

{!!$car['priceHistoryArea']!!}


<div class="graphButton" vin="{{$car['VIN']}}" >
<button class=" btn btn-success" >&nbsp;&nbsp;&nbsp;Open chart&nbsp;&nbsp;&nbsp;</button>
</div>


</div>
@endif

</div><!--конец columns  -->



<div class="section2">

	<div class="col1">
	@if (@$car['scaleFileName'])
		<div class="scale_info_top">
			<div ><span style='color:#016B01;{{ @$car['dealNum']==1 ? "font-weight: bold;" : "" }}' >Great Deal</span></div>
			<div ><span style='color:#009900;{{ @$car['dealNum']==2 ? "font-weight: bold;" : "" }}' >Good Deal</span></div>
			<div ><span style='color:#02BD00;{{ @$car['dealNum']==3 ? "font-weight: bold;" : "" }}' >Fair Deal</span></div>
			<div ><span style='color:#FF8501;{{ @$car['dealNum']==4 ? "font-weight: bold;" : "" }}' >High Price</span></div>
			<div ><span style='color:#F50100;{{ @$car['dealNum']==5 ? "font-weight: bold;" : "" }}' >Overpriced</span></div>
		</div>
		<div class="scale">
			<img src="{{ asset('scales/'.$car['scaleFileName']) }}" alt="">
		</div>
		<div class="scale_info">

     <div class="si">&nbsp;</div>
     <div class="si"><span>{{@$car['mark'][1]}}&nbsp;</span></div>
     <div class="si"><span>{{@$car['mark'][2]}}&nbsp;</span></div>
     <div class="si"><span>{{@$car['mark'][3]}}&nbsp;</span></div>
     <div class="si"><span>{{@$car['mark'][4]}}&nbsp;</span></div>
     <div class="si"></div>
		</div>
		@endif
	</div>

	<div class="col2">
      <div class="col2Header">Main factors</div>

    <div class="col2Body">


		 <div>
		<TABLE >
			<TR><TD>Leads in CRM:</TD><TD>{{@$car['NumberofLeads']}}</TD></TR>
			<TR><TD>Views p/d:</TD><TD>{{@$car['viewsPerDay']}}</TD></TR>
			<TR><TD>Deal Status:</TD><TD>{!!$car['DealStatus']!!}</TD></TR>
		</TABLE>
		</div>

		 <div>
		<TABLE  >
			<TR><TD  >Days last reprise:</TD><TD>{!!@$car['daysLastReprise']!!}</TD></TR>
			<TR><TD>Potential Gross:</TD><TD>{{$car['PotentialGross']}}</TD></TR>
		</TABLE>
		</div>

		   @if (@$car['cg'])
		 <div>
		<TABLE  >
			<TR><TD  >CG days on market:</TD><TD>{!!@$car['days_pt']!!}</TD></TR>
			<TR><TD>Rating:</TD><TD>{!!@$car['deal']!!}</TD></TR>
		</TABLE>
		</div>
  @endif

  	</div>

	</div>

</div>





<!--
<div class="row2" >

	<div class="col1" >

		<div class="row1" >

	    <div class="mainFactors">

	   @if (@$car['cg'])
			 <div>
			<TABLE  >
				<TR><TD  >CG days on market:</TD><TD>{!!@$car['days_vdp']!!}</TD></TR>
				<TR><TD>Rating:</TD><TD>{!!@$car['deal_pt']!!}</TD></TR>
			</TABLE>
			</div>
	  @endif
			 <div>
			<TABLE >
				<TR><TD>Leads in CRM:</TD><TD>{{@$car['NumberofLeads']}}</TD></TR>
				<TR><TD>Views p/d:</TD><TD>{{@$car['viewsPerDay']}}</TD></TR>
			</TABLE>
			</div>

			 <div>
			<TABLE  >
				<TR><TD  >Days last reprise:</TD><TD>{!!@$car['daysLastReprise']!!}</TD></TR>
				<TR><TD>Potential Gross:</TD><TD>{{$car['PotentialGross']}}</TD></TR>
			</TABLE>
			</div>

	  	</div>
		</div>
		<div class="row2" >

				@if (@$car['scaleFileName'])
		 <div  class="scale_main">
		<div class="scale_info_top">
			<div ><span style='color:#016B01;{{ @$car['dealNum']==1 ? "font-weight: bold;" : "" }}' >Great Deal</span></div>
			<div ><span style='color:#009900;{{ @$car['dealNum']==2 ? "font-weight: bold;" : "" }}' >Good Deal</span></div>
			<div ><span style='color:#02BD00;{{ @$car['dealNum']==3 ? "font-weight: bold;" : "" }}' >Fair Deal</span></div>
			<div ><span style='color:#FF8501;{{ @$car['dealNum']==4 ? "font-weight: bold;" : "" }}' >High Price</span></div>
			<div ><span style='color:#F50100;{{ @$car['dealNum']==5 ? "font-weight: bold;" : "" }}' >Overpriced</span></div>
		</div>
		<div class="scale">
			<img src="{{ asset('scales/'.$car['scaleFileName']) }}" alt="">
		</div>
		<div class="scale_info">
     <div class="si">&nbsp;</div>
     <div class="si"><span>{{@$car['mark'][1]}}&nbsp;</span></div>
     <div class="si"><span>{{@$car['mark'][2]}}&nbsp;</span></div>
     <div class="si"><span>{{@$car['mark'][3]}}&nbsp;</span></div>
     <div class="si"><span>{{@$car['mark'][4]}}&nbsp;</span></div>
     <div class="si"></div>
		</div>
     </div>
		@endif

		</div>
	</div>

	<div class="col2" >

<div id="chart{{$i}}"></div>


	</div>

</div>
-->


</div>
                                            </div>




 <!--
<div class="d-block text-left card-footer">

  <div class="row">

 <div class="col-md-3">
	<TABLE width="100%">
	<TR>
	<TD width="50%" class="inputAn" style="text-align:right;" >New price:&nbsp;</TD>
	<TD width="50%" style="text-align:left;" ><input name="newPrice" class=" form-control" value="321"></TD>
	</TR>
</TABLE>
		</div>

 <div class="col-md-3">
	<TABLE width="100%">
	<TR>
	<TD width="50%" style="text-align:right;">Value1:&nbsp;</TD>
	<TD width="50%" style="text-align:left;" ><input name="newPrice" class=" form-control" value="321"></TD>
	</TR>
</TABLE>
		</div>

 <div class="col-md-3">
	<TABLE width="100%">
	<TR>
	<TD width="50%" style="text-align:right;" >Value2:&nbsp;</TD>
	<TD width="50%" style="text-align:left;" ><input name="newPrice" class=" form-control" value="321"></TD>
	</TR>
</TABLE>
		</div>
	</div>

  </div>
-->





                                        </div>
</div>

 <br>

<script>
@if (@$car['graphJSData'])
graphJSData[{{$i}}]={!!$car['graphJSData']!!}
@endif
@if (@$car['graphJSy'])
graphY[{{$i}}]= new Object({!!$car['graphJSy']!!});
@endif

</script>

@endforeach