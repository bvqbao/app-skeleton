<?php
use Support\Assets;
use Support\Facades\Lang;
?>
<!DOCTYPE html>
<html lang="<?php echo Lang::getLocale(); ?>">
<head>
	<!-- Site meta -->
	<meta charset="utf-8">
	<title><?php echo $title.' - V1.0'; ?></title>
	<!-- CSS -->
	<?php
	Assets::css(array(
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css',
		base_url('css/style.css'),
	));
	?>
</head>
<body>

	<div class="container">
		<?php echo $body; ?>
	</div>

	<!-- JS -->
	<?php
	Assets::js(array(
		base_url('js/jquery.js'),
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'
	));
	?>
</body>
</html>