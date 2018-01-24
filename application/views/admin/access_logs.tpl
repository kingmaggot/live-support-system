<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<div id="content">
	
	<div class="container-fluid">

		<div class="page-header">
			
			<h1><?php echo get_text('access_logs'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-12">
				
				<div class="panel panel-default">
					
					<div class="panel-heading">

						<div class="row">
							
							<div class="col-md-7">&nbsp;</div>
							
						</div>
						
					</div>
					
					<div class="panel-body">

						<?php if ($access_logs['success']): ?>
						
							<div class="table-responsive">

								<table class="table">
									<thead>
										<tr>
											<th><?php echo get_text('full_name'); ?></th>
											<th><?php echo get_text('email_address'); ?></th>
											<th><?php echo get_text('ip_address'); ?></th>
											<th><?php echo get_text('last_login'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($access_logs['data'] as $value): ?>
											<tr>
												<td><?php echo $value['full_name']; ?></td>
												<td><?php echo $value['user_email']; ?></td>
												<td><?php echo $value['ip_address']; ?></td>
												<td><?php echo $value['time']; ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								
							</div>
							
							<ul class="pager">
								<?php if ($access_logs['current_page'] > 1 && ($access_logs['current_page'] - 1) < $access_logs['pages']): ?>
									<li><a href="<?php echo $absolute_url; ?>admin/access_logs/page/<?php echo ($access_logs['current_page'] - 1); ?>"><?php echo get_text('previous'); ?></a></li>
								<?php endif; ?>
								<?php if ($access_logs['pages'] > $access_logs['current_page'] && ($access_logs['current_page'] - 1) < $access_logs['pages']): ?>
									<li><a href="<?php echo $absolute_url; ?>admin/access_logs/page/<?php echo ($access_logs['current_page'] + 1); ?>"><?php echo get_text('next'); ?></a></li>
								<?php endif; ?>
							</ul>
							
						<?php else: ?>
						
							<span class="label label-primary"><?php echo get_text('records_not_found'); ?></span>
							
						<?php endif; ?>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
	
	</div>
	
</div>

<?php include('footer.tpl'); ?>
