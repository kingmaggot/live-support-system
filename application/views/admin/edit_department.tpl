<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<div id="content">
	
	<div class="container-fluid">

		<div class="page-header">
			
			<h1><?php echo get_text('departments'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-12">
				
				<div class="panel panel-default">
					
					<div class="panel-heading"><?php echo get_text('edit_department'); ?></div>
					
					<div class="panel-body">

						<?php if (isset($has_errors) && $has_errors): ?>
						
							<div class="alert alert-danger">
								
								<strong><?php echo get_text('error_found_errors'); ?></strong>
								
								<ul>
									<?php foreach ($display_errors as $value): ?>
										<li><?php echo $value; ?></li>
									<?php endforeach; ?>
								</ul>
								
							</div>
							
						<?php endif; ?>
						
						<form method="post">
							
							<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">
							
							<?php if ($department['success']): ?>
							
								<?php foreach($department['data'] as $value): ?>
									
									<input type="hidden" name="department_id" value="<?php echo $value['department_id']; ?>">
									
									<div class="row">
										
										<div class="col-md-6">
											
											<div class="form-group">
												
												<label for="department-name"><?php echo get_text('department_name'); ?></label>
												<input type="text" name="department_name" id="department-name" class="form-control" value="<?php echo $value['department_name']; ?>">
												
											</div>
											
										</div>
										
									</div>
									
									<div class="row">
										
										<div class="col-md-6">
											
											<div class="form-group">
												
												<label for="department-email"><?php echo get_text('email_address'); ?></label>
												<input type="text" name="department_email" id="department-email" class="form-control" value="<?php echo $value['department_email']; ?>">
												
											</div>
											
										</div>
										
									</div>
									
								<?php endforeach; ?>
								
							<?php endif; ?>
							
							<button type="submit" name="submit" class="btn btn-primary"><?php echo get_text('save'); ?></button>
							
						</form>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
	
	</div>
	
</div>

<?php include('footer.tpl'); ?>
