<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V18</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="{{ asset('fav/2023-06-19_151612.png') }}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('loginIncludes/bootstrap.min.css') }}">


<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('loginIncludes/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<!--===============================================================================================-->

<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('loginIncludes/select2.min.css') }}">

<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('loginIncludes/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('loginIncludes/main.css') }}">
<!--===============================================================================================-->
</head>
<body style="background-color: #666666;">

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form action="/sendform" method="POST" class="login100-form validate-form">

@csrf <!-- {{ csrf_field() }} -->

		<!--			-->

<!--

-->

@if ($err)
<span class="login100-form-title p-b-43" style="font-size: 16px;color:#FF0A0A;" >
						invalid email or password
</span>
@else
<span class="login100-form-title p-b-43">
						Login to continue
</span>
@endif
					<div class="wrap-input100 validate-input"  data-validate = "Valid email is required: ex@abc.xyz" >
						<input class="input100" type="text" name="email">
						<span class="focus-input100"></span>
						<span class="label-input100">Email</span>
					</div>


					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="pass">
						<span class="focus-input100"></span>
						<span class="label-input100">Password</span>
					</div>

					<div class="flex-sb-m w-full p-t-3 p-b-32">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div>
 <!--
						<div>
							<a href="#" class="txt1">
								Forgot Password?
							</a>
						</div>
-->
					</div>


					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>
      <!--
					<div class="text-center p-t-46 p-b-20">
						<span class="txt2">
							or sign up using
						</span>
					</div>

					<div class="login100-form-social flex-c-m">
						<a href="#" class="login100-form-social-item flex-c-m bg1 m-r-5">
							<i class="fa fa-facebook-f" aria-hidden="true"></i>
						</a>

						<a href="#" class="login100-form-social-item flex-c-m bg2 m-r-5">
							<i class="fa fa-twitter" aria-hidden="true"></i>
						</a>
					</div>
-->

				</form>

				<div class="login100-more" style="background-image: url('loginIncludes/417654.jpg');">
				</div>
			</div>
		</div>
	</div>





<!--===============================================================================================-->
	<script src="{{ asset('loginIncludes/jquery-3.2.1.min.js') }}"></script>



	<script src="{{ asset('loginIncludes/bootstrap.min.js') }}"></script>

	<script src="{{ asset('loginIncludes/select2.min.js') }}"></script>


	<script src="{{ asset('loginIncludes/main.js') }}"></script>


</body>
</html>