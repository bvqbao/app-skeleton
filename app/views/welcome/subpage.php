<?php
use Support\Facades\Lang;
?>
<div class="page-header">
	<h1><?php echo $title; ?></h1>
</div>

<p><?php echo $welcomeMessage; ?></p>

<a class="btn btn-md btn-success" href="<?php echo url();?>">
	<?php echo Lang::get('welcome.back_home'); ?>
</a>
