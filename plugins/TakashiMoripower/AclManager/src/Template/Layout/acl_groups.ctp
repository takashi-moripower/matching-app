<?php

use Cake\Core\Configure;

/**
 * Default `html` block.
 */
if (!$this->fetch('html')) {
	$this->start('html');
	printf('<html lang="%s" class="no-js">', Configure::read('App.language'));
	$this->end();
}

/**
 * Default `title` block.
 */
if (!$this->fetch('title')) {
	$this->start('title');
	echo Configure::read('App.title');
	$this->end();
}

/**
 * Default `footer` block.
 */
if (!$this->fetch('tb_footer')) {
	$this->start('tb_footer');
	?>
	<?php
	$this->end();
}

/**
 * Default `body` block.
 */
$this->prepend('tb_body_attrs', ' class="' . implode(' ', [$this->request->controller, $this->request->action]) . '" ');
if (!$this->fetch('tb_body_start')) {
	$this->start('tb_body_start');
	echo '<body' . $this->fetch('tb_body_attrs') . '>';
	?>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">AccessControlList</h1>
		</div>
	</div>
</div>
<?php
	$this->end();
}
/**
 * Default `flash` block.
 */
if (!$this->fetch('tb_flash')) {
	$this->start('tb_flash');
	if (isset($this->Flash))
		echo $this->Flash->render();
	$this->end();
}
if (!$this->fetch('tb_body_end')) {
	$this->start('tb_body_end');
	echo '</body>';
	$this->end();
}

/**
 * Prepend `meta` block with `author` and `favicon`.
 */
$this->prepend('meta', $this->Html->meta('author', null, ['name' => 'author', 'content' => Configure::read('App.author')]));
$this->prepend('meta', $this->Html->meta('favicon.ico', '/favicon.ico', ['type' => 'icon']));

/**
 * Prepend `css` block with Bootstrap stylesheets and append
 * the `$html5Shim`.
 */
$html5Shim = <<<HTML
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
HTML;
//$this->prepend('css', $this->Html->css(['bootstrap/bootstrap']));
$this->prepend('css', $this->Html->css(['//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css']));

$this->append('css', $html5Shim);
$this->append('css', $this->Html->css('style'));

/**
 * Prepend `script` block with jQuery and Bootstrap scripts
 */
//$this->prepend('script', $this->Html->script(['jquery/jquery', 'bootstrap/bootstrap']));
$this->prepend('script', $this->Html->script(['//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js']));
$this->prepend('script' , '<script src="https://code.jquery.com/jquery-3.0.0.min.js" integrity="sha256-JmvOoLtYsmqlsWxa7mDSLMwa6dZ9rrIdtrrVYRnDRH0=" crossorigin="anonymous"></script>');
?>
<!DOCTYPE html>

<?= $this->fetch('html') ?>

<head>

	<?= $this->Html->charset() ?>

	<title><?= $this->fetch('title') ?></title>

	<?= $this->fetch('meta') ?>
	<?= $this->fetch('css') ?>

</head>

<?= $this->fetch('tb_body_start'); ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<?= $this->fetch('tb_flash'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3">
			<?= $this->Element('groupMenu') ?>
		</div>
		<div class="col-lg-9">
			<?= $this->fetch('content') ?>
		</div>
	</div>
</div>
<?php
echo $this->fetch('tb_footer');
echo $this->fetch('script');
echo $this->fetch('tb_body_end');
?>

</html>

