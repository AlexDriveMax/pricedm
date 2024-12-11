<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Price Drive Max</title>
					<link rel="icon" type="image/png" href="{{ asset('fav/2023-06-19_151612.png') }}"/>
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

	<link href="{{ asset('modalBootstrap/css/bootstrap.css') }}" rel="stylesheet">


<link href="{{ asset('includes/apexcharts.css') }}?r={{$rand}}" rel="stylesheet">

<link href="{{ asset('includes/main.css') }}" rel="stylesheet">
<link href="{{ asset('includes/andrey.css') }}?r={{$rand}}" rel="stylesheet">
<!--
<script type="text/javascript" src="{{asset('includes/apexcharts_js')}}"></script>
-->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
var graphJSData=[]
var graphY=[]
</script>
</head>

<body>


     <!--     fixed-header    -->
    <div class="app-container app-theme-white body-tabs-shadow fixed-header "  >
        <div class="app-header header-shadow" style="width:100%">
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
            </div>

						<div class="app-header__content">
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
                                Vehicle report
                            </a>
                        </li>

                        <li class="dropdown nav-item">
                            <a href="{{ url('/dashboard/history') }}" class="nav-link">
                                <i class="nav-link-icon  metismenu-icon pe-7s-note2"></i>
                                History
                            </a>
                        </li>

@if (@$dateTimeBoston)
                        <li class="dropdown nav-item">
                            <a href="{{ url('/dashboard/export/'.$dateTimeBoston) }}" class="nav-link">
                                <i class="nav-link-icon  metismenu-icon pe-7s-server"></i>
                                Export
                            </a>
                        </li>
@endif

                    </ul>
																				</div>

                <div class="app-header-right">

																<a href="{{ url('/logout/exit') }}" class="nav-link">
                                <i class="nav-link-icon fa fa-exit"></i>
                                Exit
                 </a>  <!--{{@$resultT}}--><br>
															</div>
            </div>
        </div>




    @yield('content')

   </div>

	 @include('frameJS')
</body>
</html>
