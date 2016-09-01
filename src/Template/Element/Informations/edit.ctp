<?php
if ($information->isNew()) {
	$url = ['controller' => 'informations', 'action' => 'add'];
} else {
	$url = ['controller' => 'informations', 'action' => 'edit'];
}
?>

<?= $this->Form->create($information, ['type' => 'file', 'url' => $url]) ?>
<?= $this->Form->hidden('id') ?>
<?= $this->Form->hidden('user_id') ?>
<?= $this->Form->hidden('order_num') ?>
<table class="table table-information">
	<tbody>
		<tr>
			<td class="col-lg-2">
				<?= $this->Form->input('title', ['label' => false, 'placeHolder' => 'タイトル']); ?>
				<?= $this->Form->input('published', ['type' => 'checkbox', 'label' => '公開', 'default' => true]); ?>
			</td>
			<td class="col-lg-7">
				<?= $this->Form->textArea('content', ['placeHolder' => '本文']); ?>
			</td>
			<td class="col-lg-2 files">
				<?php foreach ($information->files as $file): ?>
					<?= $this->Element('Informations/fileEdit', ['file' => $file]) ?>
				<?php endforeach ?>				
				<div class="files-end"></div>
				<?= $this->Form->button('ファイルを添付', ['type' => 'button', 'class' => 'btn btn-default', 'name' => 'add-file']) ?>
			</td>
			<td class="col-lg-2 files">
				<?php if ($information->isNew()): ?>
					<?= $this->Form->submit('新規作成') ?>
				<?php else : ?>
					<?= $this->Form->submit('修正') ?>
					<?= $this->Html->link('削除', ['controller' => 'informations', 'action' => 'delete', $information->id], ['class' => 'btn btn-default']) ?>
					<?= $this->Html->link('<i class="fa fa-caret-up"></i>', ['controller' => 'informations', 'action' => 'moveUp', $information->user_id, $information->id], ['class' => 'btn btn-default', 'escape' => false]) ?>
					<?= $this->Html->link('<i class="fa fa-caret-down"></i>', ['controller' => 'informations', 'action' => 'moveDown', $information->user_id, $information->id], ['class' => 'btn btn-default', 'escape' => false]) ?>
				<?php endif ?>
			</td>
		</tr>
	</tbody>
</table>
<?= $this->Form->end() ?>
