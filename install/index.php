<?php

session_start();

define('ABSOLUTE_PATH', realpath(dirname(__FILE__) . '/../') . '/');

function check_settings() {
	
	$errors = array();
	
	if (version_compare(PHP_VERSION, '5.3.7', '<=')) {
		
		$errors[] = true;
		
	}
	
	if (!ini_get('file_uploads')) {
		
		$errors[] = true;
		
	}
	
	if (ini_get('register_globals')) {
		
		$errors[] = true;
		
	}
	
	if (ini_get('magic_quotes_gpc')) {
		
		$errors[] = true;
		
	}
	
	if (ini_get('session_auto_start')) {
		
		$errors[] = true;
		
	}
	
	if (!extension_loaded('PDO') || !extension_loaded('pdo_mysql')) {
		
		$errors[] = true;
		
	}
	
	if (!extension_loaded('gd')) {
		
		$errors[] = true;
		
	}
	
	if (!is_writable(ABSOLUTE_PATH . 'config/database.php')) {
		
		$errors[] = true;
		
	}

	if (!is_writable(ABSOLUTE_PATH . 'config')) {
		
		$errors[] = true;
		
	}

	if (!is_writable(ABSOLUTE_PATH . 'uploads')) {
		
		$errors[] = true;
		
	}
	
	return (count($errors) > 0) ? false : true;

}


$_SESSION['step_1'] = (check_settings()) ? true : false;

if (isset($_POST['upgrade'])) {
	
	$_SESSION['upgrade'] = true;
	
	header('Location: step_1.php');
	
} else {
	
	$_SESSION['upgrade'] = false;
	
}

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
				margin-top: 20px;
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
									<li>3. Son</li>
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
							
							<p>Başlamadan önce lütfen sisteminizin kurulum gereksinimlerini karşıladığından emin olun. Sunucu yapılandırması gereksinimleri karşılamıyorsa, barındırma desteğiyle iletişim kurmanız gerekir.</p>
							
							<hr>
							
							<?php if (!check_settings()): ?>
							
								<div class="alert alert-warning">
									Görünen o ki, sunucunuz komut dosyasını çalıştırmak için gereken şartları sağlamadı. Bunun çözülmesi için lütfen sunucu yöneticinize veya barındırma sağlayıcınıza başvurun.
								</div>
								
							<?php endif; ?>
								
							<table class="table">
								<thead>
									<tr>
										<th>PHP Ayarları</th>
										<th>Gerekli Ayarlar</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>PHP Versiyonu</td>
										<td>5.3.7+</td>
										<td><?php if (version_compare(PHP_VERSION, '5.3.7', '<=')): ?> <span class="label label-danger pull-right">Başarısız</span> <?php else: ?> <span class="label label-success pull-right">Başarılı</span> <?php endif; ?></td>
									</tr>
									<tr>
										<td>Dosya yükleme izni</td>
										<td>Açık</td>
										<td><?php if (ini_get('file_uploads')): ?> <span class="label label-success pull-right">Başarılı</span> <?php else: ?> <span class="label label-danger pull-right">Başarısız</span> <?php endif; ?></td>
									</tr>
									<tr>
										<td>Register Globals</td>
										<td>Kapalı</td>
										<td><?php if (!ini_get('register_globals')): ?> <span class="label label-success pull-right">Başarılı</span> <?php else: ?> <span class="label label-danger pull-right">Başarısız</span> <?php endif; ?></td>
									</tr>
									<tr>
										<td>Magic Quotes GPC</td>
										<td>Kapalı</td>
										<td><?php if (!ini_get('magic_quotes_gpc')): ?> <span class="label label-success pull-right">Başarılı</span> <?php else: ?> <span class="label label-danger pull-right">Başarısız</span> <?php endif; ?></td>
									</tr>
									<tr>
										<td>Session Auto Start</td>
										<td>Kapalı</td>
										<td><?php if (!ini_get('session_auto_start')): ?> <span class="label label-success pull-right">Başarılı</span> <?php else: ?> <span class="label label-danger pull-right">Başarısız</span> <?php endif; ?></td>
									</tr>
								</tbody>
							</table>
							
							<table class="table">
								<thead>
									<tr>
										<th>PHP Uzantıları</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>PDO</td>
										<td><?php if (extension_loaded('PDO') || extension_loaded('pdo_mysql')): ?> <span class="label label-success pull-right">Başarılı</span> <?php else: ?> <span class="label label-danger pull-right">Başarısız</span> <?php endif; ?></td>
									</tr>
									<tr>
										<td>GD</td>
										<td><?php if (extension_loaded('gd')): ?> <span class="label label-success pull-right">Başarılı</span> <?php else: ?> <span class="label label-danger pull-right">Başarısız</span> <?php endif; ?></td>
									</tr>
								</tbody>
							</table>

							<table class="table">
								<thead>
									<tr>
										<th>Dosya izinleri</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo 'config/database.php'; ?></td>
										<td><?php if (is_writable(ABSOLUTE_PATH . 'config/database.php')): ?> <span class="label label-success pull-right">Başarılı</span> <?php else: ?> <span class="label label-danger pull-right">Başarısız</span> <?php endif; ?></td>
									</tr>
								</tbody>
							</table>
							
							<table class="table">
								<thead>
									<tr>
										<th>Klasör izinleri</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo 'config/'; ?></td>
										<td><?php if (is_writable(ABSOLUTE_PATH . 'config')): ?> <span class="label label-success pull-right">Başarılı</span> <?php else: ?> <span class="label label-danger pull-right">Başarısız</span> <?php endif; ?></td>
									</tr>
									<tr>
										<td><?php echo 'uploads/'; ?></td>
										<td><?php if (is_writable(ABSOLUTE_PATH . 'uploads')): ?> <span class="label label-success pull-right">Başarılı</span> <?php else: ?> <span class="label label-danger pull-right">Başarısız</span> <?php endif; ?></td>
									</tr>
								</tbody>
							</table>
							
							<?php if (check_settings()): ?>
							
								<form method="post">
									<button type="submit" name="upgrade" class="btn btn-primary">Güncelleme başlat</button>
									<a href="step_1.php" class="btn btn-primary">Yükleme başlat</a>
								</form>
								
							<?php else: ?>
							
								<a href="index.php" class="btn btn-warning">Tekrar dene</a>
								
							<?php endif; ?>
							
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
