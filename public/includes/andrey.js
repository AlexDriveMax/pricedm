

 /*

$('body').on('click', '.carName', function(){

$(this).parent(".carsRow").find(".tableHeader").css("display","block");

})
*/


/*  /////////////////////// */
/*/////////сортировка /////////*/
/*  /////////////////////// */
$(".sortBy div button").click(function (){

	sortBy=$(this).attr("id");

	getCars();

	$(".sortBy.dropdown").click();

;})
/*  /////////////////////// */
/*/////////SIDEBAR /////////*/
/*  /////////////////////// */

var menu = $('.sideBar');
var menuTimeout = null;


var showMenuOn=false;
$(window).on('mousemove', mouseMoveHandler);
function mouseMoveHandler(e) {
    if (e.pageX < 20 || menu.is(':hover')) {
        clearTimeout(menuTimeout);
        menuTimeout = null;
        showMenu();
    } else if (menuTimeout === null) {
        menuTimeout = setTimeout(hideMenu, 1000);
    }
}


function showMenu (){

if (!isDesktop) {return false;}

if (showMenuOn) {return false;}

$('.sideBar').css('display','block');

	$( ".inSideBar" ).animate({
    width: "190px"
  }, 200, function() {

  });

   showMenuOn=true;
}


function hideMenu(){

	if (!showMenuOn) {return false;}

	$( ".inSideBar" ).animate({
    width: "0px"
  }, 200, function() {

     $('.sideBar').css('display','none');
    showMenuOn=false;

  });

    showMenuOn=false;

}

$(".close").click(function (){
   hideMenu() ;
;});




/*  /////////////////////// */
/*/////////ФИЛЬТРЫ /////////*/
/*  /////////////////////// */
var filters=[];
 var filterId;
 var deleteIndex;
$(".filter :checkbox").click(function() {

   filterId = $(this).attr('id');

  if($(this).is(":checked"))
    {
        filters.push(filterId);
    } else{
    		deleteIndex = filters.indexOf(filterId);
				if (deleteIndex !== -1) {
				    filters.splice(deleteIndex, 1);
				}
       // alert('NOchecked');
		;}
    		
    getCars();
	 //	console.log(filters);
});

 var customStatus;
 var searchOn=0;
$('#custom_status').keyup(function(eventObject){
  var len = $(this).val().length;
	if (len>=3) {
		customStatus=$(this).val();
     getCars();
		searchOn=1;
	}else{
		if (!searchOn) {return false;}
		customStatus=false;
    getCars();
		searchOn=0;
	;}
});



/*  /////////////////////////////////////////////// */
/*НАЖАТИЕ В ПОЛЕ ЦЕНЫ - ДИСКАУНТ */
/*НАЖАТИЕ В ПОЛЕ ЦЕНЫ - СОХР И ПЕРЕХОД К СЛЕД */
/*  /////////////////////////////////////////////// */


// var pressedPrice=0;
$('body').on('keyup', '.price', function(event){

 // if (pressedPrice) {pressedPrice=0;return false;}
 // if (!pressedPrice) {pressedPrice=1;}

/*пересчет дискаунта   */
var price2= $(this).val();
price2 = parseInt(price2);

var advPrice = $(this).closest('table').find('td.advPrice').text().replace('$', '').replace(',', '');
advPrice = parseInt(advPrice);

if (price2 && advPrice) {

    var discount=advPrice-price2;
		discount=number_format(discount) ;
		if (discount.substr(0, 1)=="-") {
        discount=discount.replace('-', '');
				discount="-$"+discount;
		}else{
       discount="$"+discount;
		}

    $(this).closest('table').find('td.discount').text(discount);

}



    //нажатие enter  - сохранение, перемещение курсора и скролл
    if (event.keyCode === 13) {

	     var newPriceId = $(this).attr("newPriceId");
	     newPriceId = parseInt(newPriceId);

	     var newPriceIdNext = newPriceId + 1;

			 var nextSelector = $('[newPriceId="'+newPriceIdNext+'"]');

	     priceSave($(this));

	    $('html, body').animate({
	        scrollTop: nextSelector.offset().top-200
	    }, 500, function(){

    		focus2(nextSelector);

			});


	  	var y = $(window).scrollTop();

    } else {
         //предупреждающие сообщения
        // warnings($(this));
    }


})



   $('body').on('focus', '.price',function(){

      numCar = $(this).closest(".main-card").attr('num');
      wasFocused=1;
      warnings($(this));

  });


 /*  /////////////////////////////////////////////// */
/////////////прочистка цен///////////
 /*  /////////////////////////////////////////////// */
    $("#clearPrices").click(function (){
    /*  $('#pricesModal').modal('hide');    */
			$('.btn-close').trigger('click');
			$('.price').val('');
			clearPricesDB()
		;})



/*  ГРАФИК */
 var fid;
document.addEventListener("keydown", function(event) {
    if (event.altKey && event.code === "KeyG")
    {

			if (numCar) {
      	chart.updateSeries(graphJSData[numCar]);
			 $('#graphModal').modal('toggle');

			 var selectorF = $('input[newPriceId="'+numCar+'"]');
       focus2(selectorF)

			}else{
        alert('Please select a vehicle')
			;}

        event.preventDefault();
    }
});

var numCar;
var wasFocused=0;

 $('body').on('click', '.graphButton button', function(){
      numCar = $(this).closest(".main-card").attr('num');


		 //	alert(grY.prices.min);
      chart.updateSeries(graphJSData[numCar]);

		 	chart.updateOptions(graphY[numCar]);


			$('#graphModal').modal('toggle');

			return false;
})




$('body').on('focus', '.input', function(){
      numCar = $(this).closest(".main-card").attr('num');
})

$('body').on('click', '.main-card', function(){
      numCar = $(this).attr('num');
})

$("#graphModal .btn-close").click(function (){
	$('#graphModal').modal('hide');
	focusSelectedCar();
})

$("#noticesModal .btn-close").click(function (){
	$('#noticesModal').modal('hide');
   focusSelectedCar();
})

$("#noticesModal #noticesOk").click(function (){
	$('#noticesModal').modal('hide');
   focusSelectedCar();
})





function focusSelectedCar(){
	if (numCar && wasFocused) {
		var selectorF = $('input[newPriceId="'+numCar+'"]');
		focus2(selectorF);
	}
}

function focus2(selector){
	selector.focus();
	if (selector.val()) {
		var tmpStr = selector.val();
		selector.val('');
		selector.val(tmpStr);
	}
	wasFocused=1;
;}

 //предупреждающие сообщения
function warnings(selectorW){

	    var warnings = selectorW.attr("warnings") ;
	    var warningsNoticed = selectorW.attr("warningsNoticed") ;

			var notice="";

			if (warnings && warningsNoticed==0) {

		    var repriceDays = selectorW.attr("repriceDays") ;
		    var nLeadsMonth = selectorW.attr("nLeadsMonth") ;

	      var search_pending = ~warnings.indexOf("pending");
	      var search_reprice = ~warnings.indexOf("reprice");
	      var search_leads = ~warnings.indexOf("leads");

		 	if (search_pending) {
					notice = notice+"Active deal Pending<br>"
		 	}

		 if (search_reprice) {
					notice = notice+"Last reprice days - "+repriceDays+"<br>"
	 	}

				if (search_leads) {
					notice = notice+"Leads AVG ratio - "+nLeadsMonth+"<br>"
				}

	      notice="<br>"+notice+"<br>";

				$('#noticesModal').find('.modal-body').html(notice);

				$('#noticesModal').modal('show');


	    	selectorW.attr("warningsNoticed", 1) ;

			}

;}


 function chartRating(data){
  var colors8 = [
      '#016B01',
      '#009900',
      '#02BD00',
      '#FF8501',
      '#F50100',
      '#6B6B6B',
      '#858585',
      '#A3A3FF'
    ]

        var options8 = {
          series: [
					{ name: "Rating",

          data: data
        }
				],
				title: {
          text: 'Cargurus rating',
          align: 'center',
          offsetY: 9,
					style: {
						fontSize:  '10px',
						fontWeight:  'bold',
						color:  '#3f6ad8',
					}
        },


          chart: {
          height: 190,
          width: 280,
          type: 'bar',
          events: {
            click: function(chart, w, e) {
              // console.log(chart, w, e)
            }
          }
        },
        colors: colors8,
        plotOptions: {
          bar: {
            columnWidth: '65%',
            distributed: true,
          }
        },
        dataLabels: {
          enabled: false
        },
        legend: {
          show: false
        },
        xaxis: {
          categories: [

            ['Great', 'Deal'],
            ['Good', 'Deal'],
            ['Fair', 'Deal'],
            ['High', 'Price'],
            ['Over', 'Price'],
            ['No', 'Price'],
            'Uncert',
            ['Not', 'Posted'],

           ],
          labels: {
            style: {
              colors: colors8,
              fontSize: '8px'
            }
          }
        },
        yaxis: {
          labels: {
            style: {
              fontSize: '8px'
            }
          }
        }
        };

        var chart8 = new ApexCharts(document.querySelector("#chartRating"), options8);
        chart8.render();

 ;}






 function chartReprice(data){
  var colors8 = [
      '#016B01',
      '#009900',
      '#02BD00',
      '#FF8501',
      '#F50100',
      '#6B6B6B',
    ]

        var options9 = {
          series: [
					{ name: "Days",

          data: data
        }
				],
				title: {
          text: 'Days last reprice',
          align: 'center',
          offsetY: 9,
					   style: {
				      fontSize:  '10px',
				      fontWeight:  'bold',
						color:  '#3f6ad8',
    					}
        },


          chart: {
          height: 190,
          width: 210,
          type: 'bar',
          events: {
            click: function(chart, w, e) {
              // console.log(chart, w, e)
            }
          }
        },
        colors: colors8,
        plotOptions: {
          bar: {
            columnWidth: '65%',
            distributed: true,
          }
        },
        dataLabels: {
          enabled: false
        },
        legend: {
          show: false
        },
        xaxis: {
          categories: [

            '0-7',
            '8-14',
            '15-21',
            '22-30',
            '>30',
            'n/d',

           ],
          labels: {
            style: {
              colors: colors8,
              fontSize: '9px'
            }
          }
        },
        yaxis: {
          labels: {
            style: {
              fontSize: '8px'
            }
          }
        }
        };

        var chart9 = new ApexCharts(document.querySelector("#chartReprice"), options9);
        chart9.render();

 ;}





 function chartStock(data){
  var colors8 = [
      '#016B01',
      '#009900',
      '#02BD00',
      '#FF8501',
      '#F50100',
    ]

        var options10 = {
          series: [
					{ name: "Days",

          data: data
        }
				],
				title: {
          text: 'Days in stock',
          align: 'center',
          offsetY: 9,
					   style: {
				      fontSize:  '10px',
				      fontWeight:  'bold',
						color:  '#3f6ad8',
    					}
        },


          chart: {
          height: 190,
          width: 205,
          type: 'bar',
          events: {
            click: function(chart, w, e) {
              // console.log(chart, w, e)
            }
          }
        },
        colors: colors8,
        plotOptions: {
          bar: {
            columnWidth: '55%',
            distributed: true,
          }
        },
        dataLabels: {
          enabled: false
        },
        legend: {
          show: false
        },
        xaxis: {
          categories: [

            '0-30',
            '31-60',
            '61-90',
            '91-120',
            '>120',

           ],
          labels: {
            style: {
              colors: colors8,
              fontSize: '8px'
            }
          }
        },
        yaxis: {
          labels: {
            style: {
              fontSize: '7px'
            }
          }
        }
        };

        var chart10 = new ApexCharts(document.querySelector("#chartStock"), options10);
        chart10.render();

 ;}
 ////////////////////////////
 ///ВСПОМОГАТ ФУНКЦИИ/////
//////////////////////////////

function dict_reverse(obj) {
  new_obj= {}
  rev_obj = Object.keys(obj).reverse();
  rev_obj.forEach(function(i) {
    new_obj[i] = obj[i];
  })
  return new_obj;
}





function number_format(number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
      

