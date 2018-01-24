<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<div id="content">
	
	<div class="container-fluid">

		<div class="page-header">
			
			<h1><?php echo get_text('blocked_visitors'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-12">
				
				<div class="panel panel-default">
					
					<div class="panel-heading"><?php echo get_text('add_new_visitor'); ?></div>
					
					<div class="panel-body">

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
							
							<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">
							
							<div class="row">
								
								<div class="col-md-6">
									
									<div class="form-group">
										
										<label for="ip-address"><?php echo get_text('ip_address'); ?></label>
										<input type="text" name="ip_address" id="ip-address" class="form-control" value="<?php if (isset($ip_address)) echo sanitize($ip_address, 'string'); elseif (!empty($_POST['ip_address'])) echo sanitize($_POST['ip_address'], 'string'); ?>">
										
									</div>
									
									<div class="form-group">
										
										<label for="description"><?php echo get_text('description'); ?></label>
										<input type="text" name="description" id="description" class="form-control" value="<?php if (!empty($_POST['description'])) echo sanitize($_POST['description'], 'string'); ?>">
										
									</div>
									
								</div>
								
							</div>
							
							<button type="submit" name="submit" class="btn btn-primary"><?php echo get_text('save'); ?></button>
							
						</form>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
	
	</div>
	
</div>

<?php include('footer.tpl'); ?>
