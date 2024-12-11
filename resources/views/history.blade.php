@extends('layouts.main')

@section('content')
						<div class="pageBody" >

                   <!--class="app-main__outer"-->
																<div  >
                    <div class="app-main__inner"  >
                        <div class="app-page-title"  >
                            <div class="page-title-wrapper">
                                <div class="page-title-heading" >
<div class="page-title-icon" style="margin-left:30px;">
<i class="pe-7s-note2 icon-gradient bg-happy-itmeo"></i>
</div>
                                    <div>History
                                        <div class="page-title-subheading"> History and logs of parsers
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">


            </div></div></div><!-- end title -->

                  <div class="pageBody2">

<div class="main-card mb-3 card">
<div class="card-body">
<!--<h5 class="card-title">Table striped</h5>   -->
 <table class="mb-0 table table-striped">
     <thead>
     <tr>
		 			<th>&nbsp;&nbsp;Date</th>
         <th>View</th>
         <th>DC Status</th>
         <th>СG Status</th>
         <th>DC FTP Status</th>
       		<th>DC Cars</th>
         <th>СG Cars</th>
     </tr>
     </thead>


     <tbody>


					@foreach ($parsings as $parsing)
     <tr>
         <td>&nbsp;&nbsp;<a href='{{url("dashboard/history/".$parsing["date"])}}' >{{$parsing['date']}}</a></td>
         <td ><a href='{{url("dashboard/history/".$parsing["date"])}}' >View cars</a></td>
<td>
<h7 style="position:relative;top:4px;"><div class="mb-2 mr-2 badge badge-pill badge-success" >Success</div></h7>
</td>


<td >
<h7 style="position:relative;top:4px;"><div class="mb-2 mr-2 badge badge-pill {{ @$parsing['statusCG']=='success' ? 'badge-success' : 'badge-secondary' }}" >
@if (@$parsing['statusCG']=='success')
Success
@else
No data
@endif
</div></h7>
</td>


<td >
@if (@$parsing['statusDcFTP'])
<h7 style="position:relative;top:4px;"><div class="mb-2 mr-2 badge badge-pill {{ @$parsing['statusDcFTP']=='success' ? 'badge-success' : 'badge-secondary' }}" >
@if (@$parsing['statusDcFTP']=='success')
Success
@elseif(@$parsing['statusDcFTP']=='noData')
No data
@elseif(@$parsing['statusDcFTP']=='noFile')
No file
@endif
</div></h7>
@endif
</td>


      <td>{{$parsing['ncDC']}}</td>
         <td>{{$parsing['ncCG']}}</td>
     </tr>
					 @endforeach

     </tbody>
 </table>
</div>
</div>

                  </div>

                       </div>
        </div>
    </div>
@endsection