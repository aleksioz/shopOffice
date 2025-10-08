<?php
/* @var $this Controller */
/* @var $content string */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
	<!-- Bootstrap CSS for fullwidth layout -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<!-- jQuery from Google CDN -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	
	<style>
		/* Fullwidth layout styles */
		body {
			margin: 0;
			padding: 0;
		}
		
		.container-fluid {
			width: 100%;
			padding: 15px;
		}
		
		.fullwidth-content {
			width: 100%;
			max-width: none;
			margin: 0;
			padding: 0;
		}
		
		/* Optional: Remove default margins/padding from content */
		.content-wrapper {
			padding: 20px;
			background: #fff;
			min-height: calc(100vh - 40px);
		}
		
		/* Navigation/header styling */
		.module-header {
			background: #f8f9fa;
			border-bottom: 1px solid #dee2e6;
			padding: 15px 0;
			margin-bottom: 20px;
		}
		
		.module-title {
			font-size: 24px;
			font-weight: bold;
			color: #495057;
		}
	</style>
</head>

<body>
	<div class="container-fluid fullwidth-content">
		<div class="module-header">
			<div class="row">
				<div class="col-md-6">
					<h1 class="module-title">Shop Office</h1>
				</div>
				<div class="col-md-6 text-right">
					<?php 
					// Breadcrumbs or navigation can go here
					if(isset($this->breadcrumbs)):
						echo '<nav class="breadcrumb">';
						foreach($this->breadcrumbs as $label=>$url) {
							if(is_string($label) || is_array($url)) {
								echo CHtml::link($label, $url) . ' / ';
							} else {
								echo '<span>' . $url . '</span>';
							}
						}
						echo '</nav>';
					endif;
					?>
				</div>
			</div>
		</div>
		
		<div class="content-wrapper">
			<?php echo $content; ?>
		</div>
	</div>

	<!-- Optional: jQuery and Bootstrap JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>