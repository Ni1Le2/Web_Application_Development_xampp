<?php 
// if  errors have been thrown, display all errors according to the error-style
if (count($errors) > 0) : ?>
	<div class="error">
		<?php foreach ($errors as $error) : ?>
			<p><?php echo $error ?></p>
		<?php endforeach ?>
	</div> <br>
<?php endif ?>