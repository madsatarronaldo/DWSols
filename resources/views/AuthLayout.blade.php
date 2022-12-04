<!doctype html>
<html lang="en">
  <head>
  	<title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js">
	<link rel="stylesheet" href="{{URL::asset('css/style.css')}}">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	</head>
	@yield('dashbaord')
	<body class="img js-fullheight" style="background-image: url(images/bg.jpg); overflow:hidden; position:unset;" >
	<section class="ftco-section">
		<div class="container">
			@yield('page_content')
		</div>
	</section>
	<script>
    window.setTimeout(function() {
      $(".alert").fadeTo(500, 100).slideUp(500, function(){
          $(this).remove(); 
      });
    }, 3000);
  </script>
	</body>
</html>

