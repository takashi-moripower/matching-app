<?php
use Cake\Utility\Hash;
?>

<div class="container">
	<div class="row">
		<ul class="nav nav-pills">
			<?php foreach ($items as $item): ?>
				<li class="<?= $this->isMatch(Hash::get($item, 'url.controller'), Hash::get($item, 'url.action' , NULL)) ? 'active' : '' ?>" >
					<?= $this->Html->link($item['label'], $item['url']) ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
