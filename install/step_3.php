<?php

session_start();

if (!$_SESSION['step_3']) {
	
	header('Location: step_2.php');
	
}

session_destroy();

?>
<!DOCTYPE html>
<html>
	<head>
		
		<meta charset="UTF-8">
		
		<title>GokmenDC Kurulum Ekranı</title>
		
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
		
		<style>
			body {
				background-color: #F4F4F4;
				margin-top: 35px;
			}

			a:hover {
				text-decoration: none;
			}

			#steps ul li{
				width: 25%;
			}
			
			#steps ul li {
				background: #EEEEEE;
				color: #AAAAAA;
				padding: 8px;
				border-radius: 5px;
			}
			
			#steps ul li.active {
				background: #337AB7;
				color: #FFFFFF;
			}
		</style>
		
	</head>
	<body>
		
		<div class="container">

			<div class="row">
				
				<div class="col-md-12">
					
					<div class="panel panel-default">

						<div class="panel-body">
							
							<div id="steps" class="text-center">
								
								<ul class="list-inline">
									<li>1. Veritabanı</li>
									<li>2. Ayarlar</li>
									<li class="active">3. Son</li>
								</ul>
								
							</div>

						</div>
						
					</div>
					
				</div>
				
			</div>
			
			<div class="row">
				
				<div class="col-md-12">
					
					<div class="panel panel-default">
						
						<div class="panel-body">

							<div class="row">
								
								<div class="col-md-12 text-center">

									<h1>Kurulum başarıyla tamamlandı!</h1>
									
									<p class="lead text-muted">Son olarak sunucudan install klasörünü siliniz!</p>
									
									<a href="../admin" class="btn btn-primary btn-lg">Kontrol paneline git</a>

								</div>
								
							</div>

						</div>
						
					</div>
					
				</div>
				
			</div>

			<div class="row">
				
				<div class="col-md-12 text-right">
					
					<p class="small"><a href="https://gokmendc.com" target="_blank">GokmenDC</a></p>

				</div>
				
			</div>

		</div>
		
	</body>
</html>
