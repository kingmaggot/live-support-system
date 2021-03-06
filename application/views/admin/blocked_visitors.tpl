<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#select-all').click(function() {
			
			if ($(this).is(':checked')) {
				
				$('input:checkbox').prop('checked', true);
				
			} else {
				
				$('input:checkbox').prop('checked', false);
				
			}

		});
		
	});
</script>

<div id="content">
	
	<div class="container-fluid">

		<div class="page-header">
			
			<h1><?php echo get_text('blocked_visitors'); ?></h1>
			
		</div>
		
		<div class="row">

			<div class="col-md-12">
				
				<div class="panel panel-default">
					
					<div class="panel-heading">

						<div class="row">
							
							<div class="col-md-7">&nbsp;</div>
							
							<div class="col-md-5">
								
								<a href="<?php echo $absolute_url; ?>admin/blocked_visitors/add" class="btn btn-default btn-sm pull-right"><span class="fa fa-plus"></span></a>

							</div>
							
						</div>
						
					</div>
					
					<div class="panel-body">
						
						<?php if (isset($success) && $success): ?>
						
							<p class="text-center">
								<span class="label label-success"><?php echo get_text('success_data_saved'); ?></span>
							</p>
							
						<?php endif; ?>
						
						<?php if ($blocked_visitors['success']): ?>
						
							<div class="table-responsive">
								
								<form method="post" action="<?php echo $absolute_url; ?>admin/blocked_visitors/delete">
									
									<input type="hidden" name="token" value="<?php echo generate_token('form'); ?>">
									
									<table class="table">
										<thead>
											<tr>
												<th><input type="checkbox" id="select-all" value="cb"></th>
												<th><?php echo get_text('ip_address'); ?></th>
												<th><?php echo get_text('description'); ?></th>
												<th><?php echo get_text('date'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($blocked_visitors['data'] as $value): ?>
												<tr>
													<td><input type="checkbox" name="cb[]" value="<?php echo $value['blocked_visitor_id']; ?>"></td>
													<td><?php echo $value['ip_address']; ?></td>
													<td><?php echo $value['description']; ?></td>
													<td><?php echo $value['time']; ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>

									<button type="submit" class="btn btn-danger"><?php echo get_text('delete'); ?></button>

								</form>
								
							</div>
							
							<ul class="pager">
								<?php if ($blocked_visitors['current_page'] > 1 && ($blocked_visitors['current_page'] - 1) < $blocked_visitors['pages']): ?>
									<li><a href="<?php echo $absolute_url; ?>admin/blocked_visitors/page/<?php echo ($blocked_visitors['current_page'] - 1); ?>"><?php echo get_text('previous'); ?></a></li>
								<?php endif; ?>
								<?php if ($blocked_visitors['pages'] > $blocked_visitors['current_page'] && ($blocked_visitors['current_page'] - 1) < $blocked_visitors['pages']): ?>
									<li><a href="<?php echo $absolute_url; ?>admin/blocked_visitors/page/<?php echo ($blocked_visitors['current_page'] + 1); ?>"><?php echo get_text('next'); ?></a></li>
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
