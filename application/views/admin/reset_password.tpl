<!DOCTYPE html>
<html>
	<head>
		
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		
		<title><?php echo $site_title; ?></title>

		<link href="<?php echo $absolute_url; ?>assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo $absolute_url; ?>assets/css/admin/font-awesome.min.css" rel="stylesheet">
		<link href="<?php echo $absolute_url; ?>assets/css/admin/login.css" rel="stylesheet">
		
	</head>
	<body>

		<nav class="navbar navbar-default navbar-fixed-top">
			
			<div class="container-fluid">
				
				<div class="navbar-header">
					
					<a class="navbar-brand" href="<?php echo $absolute_url; ?>admin/index">Kingmaggot Canlı Destek</a>

				</div>

			</div>
			
		</nav>
		
		<div class="container-fluid">
			
			<div class="row">

				<div class="col-md-5 center-block">
					
					<div class="panel panel-default">
						
						<div class="panel-heading">
							
							<span class="fa fa-lock"></span> <?php echo get_text('message_login'); ?>
							
						</div>
						
						<div class="panel-body">

							<?php if (isset($success) && !$success): ?>
							
								<div class="alert alert-danger">
									<?php echo get_text('error_reset_password'); ?>
								</div>
								
							<?php endif; ?>
							
							<?php if (isset($success) && $success): ?>
							
								<p class="text-center">
									<span class="label label-success"><?php echo get_text('success_new_password'); ?></span>
								</p>
								
							<?php endif; ?>
								
							<?php if (isset($has_errors) && $has_errors): ?>
							
								<div class="alert alert-danger">
									
									<strong><?php echo get_text('error_found_errors'); ?></strong>
									
									<ul>
										<?php foreach ($errors as $value): ?>
											<li><?php echo $value; ?></li>
										<?php endforeach; ?>
									</ul>
									
								</div>
								
							<?php endif; ?>
							
							<form method="post">
								
								<div class="form-group">
									
									<label for="user-email"><?php echo get_text('email_address'); ?></label>
									<div class="input-group">
										<span class="input-group-addon"><span class="fa fa-at"></span></span>
										<input type="text" name="user_email" id="user-email" class="form-control">
									</div>
									
								</div>
								
								<button type="submit" name="submit" class="btn btn-primary pull-right"><?php echo get_text('get_new_password'); ?></button>
								
							</form>
							
						</div>
						
					</div>
					
				</div>
				
			</div>
			
			<div class="row">
				
				<div class="col-md-5 center-block">
					
					<p class="small"><a href="https://cokiyiya.com" target="_blank">Kingmaggot</a></p>
					
				</div>
				
			</div>
			
		</div>
		
	</body>
</html>
