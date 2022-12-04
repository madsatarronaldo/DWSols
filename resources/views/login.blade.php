@extends('AuthLayout')
@section('title' , 'Login')

@section('page_content')

<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Login Form</h2>
				</div>
			</div>
			<div class="col-md-6 col-lg-4" style="font-size:20px; text-align:center; padding-top: 0.5em; margin-left: 19em;" >
				@if(Session::get('Success'))
					<div class=" alert login-wrap p-0 mb-4 text-center" style="border-color: #8BE78B;">
						<strong > {{Session::get('Success')}}</strong>
					</div>  
					@endif
					@if(Session::get('Fail'))
					<div class=" alert login-wrap p-0 ml-1" style="background-color:red;" >
						<strong clss="mb-4 text-center"> {{Session::get('Fail')}}</strong>
					</div>  
				@endif
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      	<h3 class="mb-4 text-center">Have an account?</h3>
		      	<form action="{{route('userlogin')}}" method="post" class="signin-form">
					@csrf
		      		<div class="form-group">
		      			<input type="text" class="form-control" placeholder="Username" name="username" required>
		      		</div>
					  <div class="form-group">
		      			<input type="email" class="form-control" placeholder="Email" name="email" required>
		      		</div>
	            <div class="form-group">
	              <input id="password-field" name="password" type="password" class="form-control" placeholder="Password" required>
	              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
	            </div>
	            <div class="form-group">
	            	<button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
	            </div>
	            <div class="form-group d-md-flex">
	            	<div class="w-20">
					</div>
					<div class="w-20 text-md-right" style="margin-left:100px;">
						<a href="/registeration" style="color: #fff">Create An Account</a>
					</div>
	            </div>
	          </form>
		      </div>
				</div>
			</div>
@endsection