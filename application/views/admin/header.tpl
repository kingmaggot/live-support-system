<!DOCTYPE html>
<html>
	<head>

		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		
		<title><?php echo $site_title; ?></title>

		<link href="<?php echo $absolute_url; ?>assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo $absolute_url; ?>assets/css/admin/font-awesome.min.css" rel="stylesheet">
		<link href="<?php echo $absolute_url; ?>assets/css/admin/main.css" rel="stylesheet">
		
		<?php if ($current_page == 'settings'): ?>
			<link href="<?php echo $absolute_url; ?>assets/css/admin/jquery.minicolors.css" rel="stylesheet">
		<?php endif; ?>
		
		<script src="<?php echo $absolute_url; ?>assets/js/jquery.min.js"></script>
		<script src="<?php echo $absolute_url; ?>assets/js/bootstrap.min.js"></script>
		
		<?php if ($current_page == 'settings'): ?>
			<script src="<?php echo $absolute_url; ?>assets/js/admin/jquery.minicolors.min.js"></script>
		<?php endif; ?>
		
		<?php if ($current_page == 'index' || $current_page == 'chat_history'): ?>
			<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
			<script src="<?php echo $absolute_url; ?>assets/js/gmaps.js"></script>

			<script type="text/javascript">
				$(document).ready(function() {
					
					$('body').on('click', 'a.geolocation', function(event) {

						event.preventDefault();

						$.ajax({
							type: 'GET',
							url: $(this).attr('href'),
							dataType: 'json',
							success: function(data) {

								if (typeof data.latitude !== 'undefined' && typeof data.longitude !== 'undefined') {
									
									$('#map-wrapper').slideDown(500, function() {

											var map = new GMaps({
												div: '#map',
												lat: data.latitude,
												lng: data.longitude,
												zoomControl: false,
												streetViewControl: false,
												panControl: false,
												zoom: 10
											});
											
											map.addMarker({
												lat: data.latitude,
												lng: data.longitude
											});
										
										if (typeof data.city === 'undefined') {
											
											$('#map-wrapper p').html(data.country);
											
										} else {
											
											$('#map-wrapper p').html(data.city + ' - ' + data.country);
											
										}
										
									});
									
								}
								
							}	
						});
						
					});
					
				});
			</script>
		<?php endif; ?>
		
		<?php if ($current_page == 'index'): ?>
			<script src="<?php echo $absolute_url; ?>assets/js/admin/live_chat.min.js"></script>
			<script src="<?php echo $absolute_url; ?>assets/js/jquery.validate.min.js"></script>
			<script type="text/javascript">
				$(document).ready(function() {

					live_chat.start();
					
				});
			</script>
		<?php endif; ?>
		
	</head>
	<body>
		
		<nav class="navbar navbar-default navbar-fixed-top">
			
			<div class="container-fluid">
				
				<div class="navbar-header">
					
					<?php if ($current_page == 'index'): ?>
					
						<button type="button" class="navbar-toggle">
							<span class="fa fa-volume-up fa-lg"></span>
						</button>
						
					<?php endif; ?>
					
					<button type="button" class="navbar-toggle">
						<span class="fa fa-sign-out fa-lg"></span>
					</button>

					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					
					<a class="navbar-brand" href="<?php echo $absolute_url; ?>admin/index">Kingmaggot Canlı Destek</a>

				</div>

				<div class="collapse navbar-collapse">
					
					<ul class="nav navbar-nav navbar-right">
						<?php if ($current_page == 'index'): ?>
							<li><a href="#" class="sound"><span class="fa fa-volume-up fa-lg"></span></a></li>
						<?php endif; ?>
						<li><a href="<?php echo $absolute_url; ?>admin/logout"><span class="fa fa-sign-out fa-lg"></span> <?php echo get_text('logout'); ?></a></li>
					</ul>
					
				</div>

			</div>
			
		</nav>
