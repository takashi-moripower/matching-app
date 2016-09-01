<div class="file panel panel-default">
	<div class="panel-body clearfix" style="padding:0.5rem">
		<a href="<?= $this->Url->build(['controller' => 'files', 'action' => 'load', $file->id, $file->name]) ?>" class="pull-left">
			<?php if ($file->isImage()): ?>
				<img src="<?= $this->Url->build(['controller' => 'files', 'action' => 'load', $file->id, $file->name]) ?>" >
			<?php else: ?>
				<?= $file->name ?>
			<?php endif ?>
		</a>
		<div class="pull-right">
			削除 &nbsp; <?= $this->Form->checkbox("file_remove[{$file->id}]") ?> 
		</div>
	</div>
</div>
