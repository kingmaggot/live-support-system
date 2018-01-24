<?php include('header.tpl'); ?>
<?php include('sidebar.tpl'); ?>

<div id="content">
	
	<div class="container-fluid">

		<div class="page-header">
			
			<h1><?php echo get_text('oops'); ?></h1>
			
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
						
						<div class="alert alert-danger"><?php echo $message; ?></div>
						
						<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><?php echo get_text('please_try_again'); ?></a>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
	
	</div>
	
</div>

<?php include('footer.tpl'); ?>
