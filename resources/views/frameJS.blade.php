
<script src="{{ asset('loginIncludes/jquery-3.2.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('includes/main.js') }}"></script>
<script type="text/javascript" src="{{ asset('includes/andrey.js') }}?r={{ $rand }}"></script>




<script type="text/javascript" src="{{asset('modalBootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('modalBootstrap/js/bootstrap.bundle.min.js')}}"></script>



<script>
  // Replace Math.random() with a pseudo-random number generator to get reproducible results in e2e tests
  // Based on https://gist.github.com/blixt/f17b47c62508be59987b
  var _seed = 42;
  Math.random = function() {
    _seed = _seed * 16807 % 2147483647;
    return (_seed - 1) / 2147483646;
  };
</script>



<script>
 var isDesktop=0;
@if((new \Jenssegers\Agent\Agent())->isDesktop())
isDesktop=1;
@endif



@if ($page=="cars")




/*

*/


/*
 var xhr2;

function getCharts(){

	if(xhr2 && xhr2.readyState != 4){
		xhr2.abort();
	}


	xhr2 = $.ajax({

		url: "/ajaxCharts",
		type:"POST",
		data:{
			"_token": "{{ csrf_token() }}",
			date:"{{$date}}",
		},
		beforeSend: function() {
		},
		success:function(response){

			$('#charts').html(response);

		},

	});

;}
*/






var sortBy=""; var xhr;

function getCars(){

	if(xhr && xhr.readyState != 4){
		xhr.abort();
	}

  
	xhr = $.ajax({

		url: "/ajaxCars",
		type:"POST",
 /*		async: false, */
		data:{
			"_token": "{{ csrf_token() }}",
			sortBy:sortBy,
			filters:JSON.stringify(filters),
			customStatus:customStatus,
			date:"{{$date}}",
		},
		beforeSend: function() {
			$('.spinner').css("display", "block");            		$('#cars').css("opacity", "0.33");
		/* 	$('#cars').css("display", "none");   */
		},
		success:function(response){

			$('.spinner').css("display", "none");
			/*		$('#cars').css("display", "block");    */
			$('#cars').css("opacity", "1");
			$('#cars').html(response);
     // loadPricesLeads();
		},

	});

;}


@if ($ajaxCars)
getCars()
@endif






function loadPricesLeads(){
  var vin; var ind;
	$(".carsRow").each(function( index ) {
		ind = index+1;
		vin = $(this).attr("vin");
		console.log(vin);
		getPricesHistory(vin, $(this));
  // return false;

	});

;}


function getPricesHistory(vin, selector){

	 getPricesLeads(vin, "history", selector);;

}

function getPricesHistory2(prHistory, selector){


    if (prHistory!="nodata") {

    	var html="";
    	var i=0;
			for (var key in prHistory) {
			  var row = prHistory[key];

        if (!row.priceF) {continue;}
				i++;

			  //  if (v.hasOwnProperty(key)) {
			   // }


				html = html + "<TR><TD>&nbsp;&nbsp;&nbsp;&nbsp;"+row.date+":</TD><TD>&nbsp;&nbsp;&nbsp;"+row.priceF+"</TD></TR>";

        if (i==6) {break;}
			}

      selector.find(".prHistory").append(html);
		 //	console.log(html);

    } else {

    }


}


function getPricesLeads(vin, type, selector){

$.ajax({

		url: "/dashboard/pricesLeadsAjax",
		type:"POST",
		data:{
			"_token": "{{ csrf_token() }}",
			type:type,
			vin:vin,
		},
		success:function(json){
    if (json!="nodata") {
				var result = JSON.parse(json);
			} else{
				var result = json;
			;}

      if (type=="history") {
				getPricesHistory2(result, selector);
      } else if(type=="graph")  {
				getPricesGraph2(vin, selector);
      }





		},

	});


}




$('body').on('click', '.priceSave', function(event){

   priceSave($(this));

})


function priceSave(selector){

	var vin = selector.parent("div").attr('vin');
	var price = selector.parent("div").find(".price");
	var priceVal = price.val();

	$.ajax({
	  url: "/dashboard/savePriceAjax",
	  type:"POST",
	  data:{
	    "_token": "{{ csrf_token() }}",
	    vin:vin,
	    price:priceVal,
	  },
	  success:function(response){

	  },
	 });


	selector.parent("div").find("span").css("color", "#3ac47d");
	selector.parent("div").find(".price").css("border-color", "#3ac47d");

;}


$('body').on('click', '.notesSave', function(event){

	var vin = $(this).parent("div").attr('vin');
	var notes = $(this).parent("div").find(".notes");
	var notesVal = notes.val();

	$.ajax({
	  url: "/dashboard/saveNotesAjax",
	  type:"POST",
	  data:{
	    "_token": "{{ csrf_token() }}",
	    vin:vin,
	    notes:notesVal,
	  },
	  success:function(response){

	  },
	 });

	$(this).parent("div").find("span").css("color", "#3ac47d");
	$(this).parent("div").find(".notes").css("border-color", "#3ac47d");

})

function clearPricesDB(){

	$.ajax({
	  url: "/dashboard/clearPrices",
	  type:"POST",
	  data:{
	    "_token": "{{ csrf_token() }}",

	  },
	  success:function(response){
   
	  },
	 });



}


 /*  /////////////////////////////////////////////// */
/////////////»—“Œ–»ﬂ ÷≈Õ///////////
 /*  /////////////////////////////////////////////// */
$('body').on('click', '.priceHistory', function(){

    	var vin = $(this).attr('vin');
    	var sel = $(this);

	$.ajax({
	  url: "/dashboard/priceHistoryAjax",
	  type:"POST",
	  data:{
	    "_token": "{{ csrf_token() }}",
	    vin:vin
	  },
	  success:function(ph_area_html){
      if (ph_area_html=="nodata") {
         sel.parent().find(".ph_area").css("height",30);
         sel.parent().find(".ph_area").css("padding-top",6);
				 ph_area_html = "No data"
      } else {
         sel.parent().find(".ph_area").css("height",113);
         sel.parent().find(".ph_area").css("padding-top",0);
      }
			sel.parent().find(".ph_area").html(ph_area_html);
    	sel.parent().find(".ph_area").fadeToggle( 100, function() { });
	  },
	 });


			return false;

})

/////////////»—“Œ–»ﬂ ÷≈Õ ÌÓ‚‡ˇ///////////
$('body').on('click', '.phMore', function(){

	$(this).closest('.prHistory').find('.prHistoryArea1').css('display','none');
	$(this).closest('.prHistory').find('.prHistoryArea2').css('display','block');

return false;

})



/*„‡ÙËÍ */



var options = {
			series: [

    {name: "Price",
       data: [
 			 /*	[ "02-10-2017 GMT",2 ],*/
			]
    },
		{  name: "Views",
      data: [


			]
    },
        ],
          chart: {
          offsetX: 0,
          offsetY: 0,
					 parentHeightOffset: 0,
          height: 300,
          width: "100%",
          type: 'line',
          zoom: {
            enabled: true
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
					width: 2,
        },
        title: {
          text: 'Price and views dynamics over time',
          align: 'left',
					offsetX: 40,
        },

		yaxis: [
    {
      title: {
        text: "Price"
      },
	 /*	 tickAmount: 3,*/
      labels: {
        offsetX: -15,
      },
  /*    max: 30000,
			    min: 15000,*/
    },
    {
      opposite: true,
      title: {
        text: "Views"
      },

      labels: {
        offsetX: -15,
      },
    }
  ],
        xaxis: {
     	type: 'datetime',
/*			labels: {
        show: false,
      },
			axisBorder: {
        show: false,
      },*/
        } ,

		  grid: {
        padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: -5
        },

    },

        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);

        chart.render();





   /*
$(document).scroll(function() {
 <?php
$i=0;
?>
@foreach ($cars as $car)
<?php
$i++;
?>
     if (div_top{{$i}} <= $(document).scrollTop()) {
                alert({{$i}});
     }

@endforeach
});
*/

@endif





</script>