<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Price Drive Max</title>
					<link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Tabs are used to split content between multiple sections. Wide variety available.">
    <meta name="msapplication-tap-highlight" content="no">
    <!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->
<link href="{{ asset('includes/main.css') }}" rel="stylesheet">
<link href="{{ asset('includes/andrey.css') }}?r={{$rand}}" rel="stylesheet">

</head>

<body  >
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header ">
        <div class="app-header header-shadow">
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">

                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>    <div class="app-header__content">
                <div class="app-header-left">

                    <ul class="header-menu nav">

                         <!--
                        <li class="dropdown nav-item">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-link-icon fa fa-cog"></i>
                                Settings
                            </a>
                        </li>-->

                        <li class="dropdown nav-item">
                            <a href="{{ url('/dashboard') }}" class="nav-link">
                                <i class="nav-link-icon metismenu-icon pe-7s-car"></i>
                                Cars
                            </a>
                        </li>

                        <li class="dropdown nav-item">
                            <a href="{{ url('/dashboard/history') }}" class="nav-link">
                                <i class="nav-link-icon  metismenu-icon pe-7s-display2"></i>
                                History
                            </a>
                        </li>


                    </ul>
																				</div>

                <div class="app-header-right">

																<a href="{{ url('/dashboard/exit') }}" class="nav-link">
                                <i class="nav-link-icon fa fa-exit"></i>
                                Exit
                 </a>


																</div>
            </div>

        </div>










								<div class="pageBody" >


               
                   <!--class="app-main__outer"-->
																<div  >
                    <div class="app-main__inner"  >
                        <div class="app-page-title"  >
                            <div class="page-title-wrapper">
                                <div class="page-title-heading" >
<div class="page-title-icon" style="margin-left:30px;">
<i class="pe-7s-car icon-gradient bg-happy-itmeo"></i>
</div>
                                    <div>Cars
                                        <div class="page-title-subheading">Last stats. Data from {{$lastDateTime}}
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">

  		<!--				position:relative;
									top:-12px;
									margin:0;-->
<div class="sortBy dropdown d-inline-block"
		style="
    margin-right:160px;
		"
>
<button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="mb-2 mr-2 dropdown-toggle btn btn-alternate">Sort by</button>

<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">

<button type="button" id="daysInStock" tabindex="0" class="dropdown-item">Days in stock</button>
<button type="button" id="lastReprise" tabindex="0" class="dropdown-item">Days since last reprice</button>
<button type="button" id="CGRating" tabindex="0" class="dropdown-item">Cargurus rating</button>
<button type="button" id="price" tabindex="0" class="dropdown-item">Price</button>
<button type="button" id="odometer" tabindex="0" class="dropdown-item">Odometer</button>
<button type="button" id="pictures" tabindex="0" class="dropdown-item" >Number Of Pics</button>
<button type="button" id="customStatus" tabindex="0" class="dropdown-item">Custom status</button>
</div>

</div>
   <!--
                                    <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                                        <i class="fa fa-star"></i>
                                    </button>-->

                                </div>    </div>
                        </div>








<!-- если не грузим через аякс, то вставляем шаблон напрямую  -->

@if ($ajaxCars)
	<div class="spinner" >
	<img src="{{ asset('includes/732.gif') }}" alt="">
	</div>
@endif

<div id="cars" >
@if (!$ajaxCars)
	@include('ajaxCars')
@endif
&nbsp;
</div>
                       </div>
        </div>
    </div>

			<script src="{{ asset('loginIncludes/jquery-3.2.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('includes/main.js') }}"></script>
<script type="text/javascript" src="{{ asset('includes/andrey.js') }}?r={{ $rand }}"></script>


   <script>
			var sortBy="";

    function getCars(){

        $.ajax({
          url: "/ajaxCars",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            sortBy:sortBy,
          },
										beforeSend: function() {
        			$('.spinner').css("display", "block");            $('#cars').css("opacity", "0.33");
       /* 				$('#cars').css("display", "none");   */
    						},
          success:function(response){
        			$('.spinner').css("display", "none");
        		/*		$('#cars').css("display", "block");    */
            $('#cars').css("opacity", "1");
										  $('#cars').html(response);
          },
         });


				;}

@if ($ajaxCars)
getCars()
@endif



$('body').on('click', '.priceSave', function(event){

	var vin = $(this).parent("div").attr('vin');
	var price = $(this).parent("div").find(".price");
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

	$(this).parent("div").find("span").css("color", "#3ac47d");
	$(this).parent("div").find(".price").css("border-color", "#3ac47d");

})



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

      </script>

   </div>
</body>
</html>
