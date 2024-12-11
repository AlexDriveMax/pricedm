@extends('layouts.main')

@section('content')

<div class="pageBody" >


<div class="sideBar sidebar-shadow" >
            <!--
        <div class="app-header__logo">
            <div class="logo-src"></div>
            <div class="header__pane ml-auto">
                <div>
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
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
                    </div>
-->
       <!-- scrollbar-sidebar -->


<div class="inSideBar" >

<div class="close" >
	<img src="{{ asset('includes/close.png') }}" alt="">
</div>

<div class="filter_header_main">FILTER</div>

<div class="filter" >
	<div  class="filter_header" >Cargurus rating</div>
	<div class="filter_box" >

		<div class="box_line" >
			<div class="box_line_left" >
				<input type="checkbox" id="rating_1" >
			</div>
			<div class="box_line_right" ><span style='color:#016B01;' >Great Deal</span> ({{$fc['rating'][1]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input type="checkbox" id="rating_2" >
			</div>
			<div class="box_line_right" ><span style='color:#009900;' >Good Deal</span> ({{$fc['rating'][2]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input  id="rating_3" type="checkbox" >
			</div>
			<div class="box_line_right" ><span style='color:#02BD00;' >Fair Deal</span> ({{$fc['rating'][3]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input id="rating_4" type="checkbox" >
			</div>
			<div class="box_line_right" ><span style='color:#FF8501;' >High Price</span> ({{$fc['rating'][4]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input id="rating_5" type="checkbox" >
			</div>
			<div class="box_line_right" ><span style='color:#F50100;' >Overpriced</span> ({{$fc['rating'][5]}})
			</div>
		</div>


		<div class="box_line" >
			<div class="box_line_left" >
				<input id="rating_6" type="checkbox" >
			</div>
			<div class="box_line_right" ><span style='color:#6B6B6B' >No price</span> ({{$fc['rating'][6]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input id="rating_7" type="checkbox" >
			</div>
			<div class="box_line_right" ><span style='color:#858585;' >Uncertain</span> ({{$fc['rating'][7]}})
			</div>
		</div>

   		<div class="box_line" >
			<div class="box_line_left" >
				<input id="rating_8" type="checkbox" >
			</div>
			<div class="box_line_right" ><span style='color:#A3A3FF;' >Not posted</span> ({{$fc['rating'][8]}})
			</div>
		</div>

	</div>
</div>




<div class="filter" >

	<div  class="filter_header" >Days last reprice</div>
	<div class="filter_box" >

		<div class="box_line" >
			<div class="box_line_left" >
				<input id="reprice_1" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#016B01;' >&nbsp;0-7</span> ({{$fc['reprice'][1]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input id="reprice_2" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#009900;' >&nbsp;8-14</span> ({{$fc['reprice'][2]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input id="reprice_3" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#02BD00;' >15-21</span> ({{$fc['reprice'][3]}})
			</div>
		</div>


		<div class="box_line" >
			<div class="box_line_left" >
				<input id="reprice_4" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#FF8501;' >22-30</span> ({{$fc['reprice'][4]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input id="reprice_5" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#F50100;' >>30</span> ({{$fc['reprice'][5]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input id="reprice_6" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#6B6B6B' >n/d</span> ({{$fc['reprice'][6]}})
			</div>
		</div>


	</div>
</div>





<div class="filter" >

	<div  class="filter_header" >Days in stock</div>
	<div class="filter_box" >

		<div class="box_line" >
			<div class="box_line_left" >
				<input  id="stock_1" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#016B01;' >&nbsp;0-30</span> ({{$fc['stock'][1]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input  id="stock_2" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#009900;' >31-60</span> ({{$fc['stock'][2]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input  id="stock_3" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#02BD00;' >61-90</span> ({{$fc['stock'][3]}})
			</div>
		</div>


		<div class="box_line" >
			<div class="box_line_left" >
				<input  id="stock_4" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#FF8501;' >91-120</span> ({{$fc['stock'][4]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input  id="stock_5" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#F50100;' >>120</span> ({{$fc['stock'][5]}})
			</div>
		</div>

	</div>
</div>





<div class="filter" >

	<div  class="filter_header" >Custom status</div>
	<div class="filter_field" >
		<input type="text" id="custom_status">
	</div>
</div>





<div class="filter" >

	<div  class="filter_header" >Number Of Pics</div>
	<div class="filter_box" >

		<div class="box_line" >
			<div class="box_line_left" >
				<input id="pics_1"  type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#016B01;' >15 or less</span> ({{$fc['pics'][1]}})
			</div>
		</div>

		<div class="box_line" >
			<div class="box_line_left" >
				<input id="pics_2" type="checkbox" >
			</div>
			<div class="box_line_right" >
<span style='color:#009900;' >&nbsp;Over 15</span> ({{$fc['pics'][2]}})
			</div>
		</div>

	</div>
</div>



   <br><br><br><br>


 <!-- <div class="app-sidebar__inner">

	23123

      <ul class="vertical-nav-menu">

          <li class="app-sidebar__heading">Sections</li>
          <li><a href="index.html"><i class="metismenu-icon pe-7s-car"></i>Cars</a></li>
          <li><a href="index.html"><i class="metismenu-icon pe-7s-display2"></i>History</a></li>



          </ul>
             </div>-->


                    </div>
                </div>





                   <!--class="app-main__outer"-->
								 <div>
                    <div class="app-main__inner"  >
                        <div class="app-page-title"  >
                            <div class="page-title-wrapper">

<div class="page-title-heading"  >
  <!--  width:1000px -->

<div class="page-title-icon" style="margin-left:30px;">

<i class=' {{ $history ? "pe-7s-note2" : "pe-7s-car" }}  icon-gradient bg-happy-itmeo'></i>
</div>


@if ($history)
<div>
	Vehicle history data
	<div class="page-title-subheading">Data from {{$dateTimeBoston}}<br>
Dealercenter: {{$numCars['dc']}}. Cargurus: {{$numCars['cg']}}
	</div>
</div>
@else
<div>
	Vehicle report
	<div class="page-title-subheading">Last stats. Data from {{$dateTimeBoston}} <br>
  Dealercenter: {{$numCars['dc']}}. Cargurus: {{$numCars['cg']}}
	</div>
</div>
@endif



</div>

<div class="header_box_2"> </div>

<div class="header_box_3" >
<!--
<button class="mb-2 mr-2 border-0 btn-transition btn btn-outline-warning">Warning</button>
-->
 <button class="mb-2 mr-2 btn btn-primary " data-bs-toggle="modal" data-bs-target="#pricesModal" >Clear prices</button>

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

<button type="button" id="daysInStock" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowDown.png') }}" class="down">Days in stock</button>
<button type="button" id="daysInStockD" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowUp.png') }}" alt="">Days in stock</button>


<button type="button" id="lastReprise" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowDown.png') }}" class="down">Days since last reprice</button>
<button type="button" id="lastRepriseD" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowUp.png') }}" >Days since last reprice</button>

<button type="button" id="CGRating" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowDown.png') }}" class="down">Cargurus rating</button>
<button type="button" id="CGRatingD" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowUp.png') }}" >Cargurus rating</button>

<button type="button" id="price" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowDown.png') }}" class="down">Price</button>
<button type="button" id="priceD" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowUp.png') }}" >Price</button>

<button type="button" id="odometer" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowDown.png') }}" class="down">Odometer</button>
<button type="button" id="odometerD" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowUp.png') }}" >Odometer</button>


<button type="button" id="pictures" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowDown.png') }}" class="down">Number Of Pics</button>
<button type="button" id="picturesD" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowUp.png') }}" >Number Of Pics</button>

<button type="button" id="customStatus" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowDown.png') }}" class="down">Custom status</button>
<button type="button" id="customStatusD" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowUp.png') }}" >Custom status</button>

<button type="button" id="daysCG" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowDown.png') }}" class="down">Days in CG</button>
<button type="button" id="daysCGD" tabindex="0" class="dropdown-item"><img src="{{ asset('includes\assets\images\ArrowUp.png') }}" >Days in CG</button>

</div>

</div>
   <!--
                                    <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                                        <i class="fa fa-star"></i>
                                    </button>-->

                                </div>    </div>
                        </div>

<div id="charts" >
&nbsp;
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

<!-- Modal prices-->
<div class="modal" id="pricesModal" tabindex="-1" aria-labelledby="pricesLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="pricesLabel">Warning</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><img src="{{ asset('includes/close.png') }}" ></button>
      </div>
      <div class="modal-body"> <br>
        Are you sure you want to clear all NEW PRICE fields?     <br><br>
      </div>
      <div class="modal-footer">
        <button type="button" id="clearPrices" class="btn btn-primary">&nbsp;Yes&nbsp;</button>  &nbsp;&nbsp;
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">&nbsp;No&nbsp;</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal notices-->
<div class="modal" id="noticesModal" tabindex="-1" aria-labelledby="noticesLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="noticesLabel">Notice</h1>
        <button type="button" class="btn-close" aria-label="Close"><img src="{{ asset('includes/close.png') }}" ></button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" id="noticesOk" class="btn btn-primary" >&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal chart line-->
<div class="modal" id="graphModal" tabindex="-1" aria-labelledby="graphLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
 <!--     <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><img src="{{ asset('includes/close.png') }}" ></button>
      </div>-->
      <div class="modal-body">
		<!--	 data-bs-dismiss="modal"   -->
			<button type="button" class="btn-close"
   style="position:absolute;right:8px;top:8px;"
			 aria-label="Close"><img src="{{ asset('includes/close.png') }}"  ></button>

	 	      <div id="chart" style="width:100%;height:350px;"></div>
      </div>

    </div>
  </div>
</div>



@endsection