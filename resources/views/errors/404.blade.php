<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
		<title>404 - Page not found!</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="/assets/images/logo/favicon.png">

        <!-- plugins css -->
		<link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.css" />
		<link rel="stylesheet" href="/bower_components/PACE/themes/blue/pace-theme-minimal.css" />
		<link rel="stylesheet" href="/bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css" />

		<!-- core css -->
		<link href="/assets/css/ei-icon.css" rel="stylesheet">
		<link href="/assets/css/themify-icons.css" rel="stylesheet">
		<link href="/assets/css/font-awesome.min.css" rel="stylesheet">
		<link href="/assets/css/animate.min.css" rel="stylesheet">
		<link href="/assets/css/app.css" rel="stylesheet">
	</head>

	<body>
		<div class="app">
			<div class="authentication">
				<div class="page-404 container">
					<div class="row">
						<div class="col-md-6">
							<div class="full-height">
								<div class="vertical-align full-height pdd-horizon-70">
									<div class="table-cell">
										<h1 class="text-dark font-size-80 text-light">Opps!</h1>
										<p class="lead lh-1-8">Hello there, You seem to be lost, but don't worry,<br>we'ill get you back on track...</p>
										<a href="{{ url()->previous() }}" class="btn btn-warning">Get Me Back!</a>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-5 ml-auto hidden-sm hidden-xs">
							<div class="full-height height-100">
								<div class="vertical-align full-height">
									<div class="table-cell">
										<img class="img-responsive" src="/assets/images/others/404.png" alt="">
									</div>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="/assets/js/vendor.js"></script>
		
		<script src="/assets/js/app.min.js"></script>

		<!-- page js -->

	</body>
</html>

