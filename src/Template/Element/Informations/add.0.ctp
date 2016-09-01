<?= $this->Form->create(NULL,['type'=>'file','url'=>['controller'=>'informations','action'=>'add']]) ?>
<?= $this->Form->hidden('user_id',['value'=>$user->id]) ?>
<table class="table">
	<tbody>
		<tr>
			<td class="col-lg-2">
				<?= $this->Form->input('title',['label'=>false,'placeHolder'=>'表題']); ?>
				<?= $this->Form->input('published',['type'=>'checkbox','label'=>'公開','default'=>true]); ?>
			</td>
			<td class="col-lg-7">
				<?= $this->Form->textArea('content',['placeHolder'=>'本文']); ?>
			</td>
			<td class="col-lg-2 files">
				<div class="files-end"></div>
				<?= $this->Form->button('ファイルを添付', ['type' => 'button', 'class' => 'btn btn-default', 'name' => 'add-file']) ?>
			</td>
			<td class="col-lg-2 files">
				<?= $this->Form->submit('新規追加') ?>
			</td>
		</tr>
	</tbody>
</table>
<?= $this->Form->end() ?>
