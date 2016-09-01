<div class="file">
	<a href="<?= $this->Url->build(['controller' => 'files', 'action' => 'load', $file->id, $file->name]) ?>">
		<?php if ($file->isImage()): ?>
			<img src="<?= $this->Url->build(['controller' => 'files', 'action' => 'load', $file->id, $file->name]) ?>" >
		<?php else: ?>
			<?= $file->name ?>
		<?php endif ?>
	</a>
</div>
