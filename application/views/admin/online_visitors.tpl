<?php if ($online_visitors['success']): ?>

	<div class="row">
		
		<div class="col-md-12">
			
			<div class="table-responsive">
				
				<table class="table">
					<tbody>
						<?php foreach($online_visitors['online_visitors'] as $value): ?>
							<tr>
								<td><p class="small"><?php echo $value['platform']; ?></p></td>
								<td><p class="small"><?php echo $value['browser']; ?></p></td>
								<td><a href="<?php echo $value['referer']; ?>" target="_blank"><span class="fa fa-link"></span></a></td>
								<td>
									<button type="button" id="invite-visitor" class="btn btn-default btn-default btn-xs pull-right" data-ip-address="<?php echo $value['ip_address']; ?>">
										<span class="fa fa-comment-o"></span>
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				
			</div>

		</div>
		
	</div>

<?php else: ?>

	<span class="label label-default"><?php echo get_text('no_visitors'); ?></span>
	
<?php endif; ?>
