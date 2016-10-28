<div class="panel panel-default new-comment">
		<?= $this->Form->create($new_comment) ?>
		<?= $this->Form->hidden('engineer_id') ?>
		<?= $this->Form->hidden('enterprise_id') ?>
		<?= $this->Form->hidden('flags') ?>
	<div class="panel-body clearfix">
		<div class="col-xs-12 col-lg-10">
			<?= $this->Form->textArea('content',['value'=>'']) ?>
		</div>
		<div class="col-xs-12 col-lg-2 text-right">
			<br class="hidden-xs hidden-sm">
		<?= $this->Form->button('コメント', ['class' => 'btn btn-primary']) ?>
		</div>
	</div>
		<?= $this->Form->end() ?>
</div>
<div class="panel panel-default">
	<div class="panel-body clearfix">
		<?= $this->Form->create(NULL, ['type' => 'file', 'url' => ['action' => 'addFile'], 'class' => 'comment-file']) ?>
		<?= $this->Form->File('file', ['class' => 'pull-left']) ?>
		<?= $this->Form->hidden('engineer_id', ['value' => $engineer->id]) ?>
		<?= $this->Form->hidden('enterprise_id', ['value' => $enterprise->id]) ?>
		<?= $this->Form->hidden('flags',['value'=>$new_comment->flags]) ?>
		<?= $this->Form->button('ファイルを添付', ['type' => 'submit', 'class' => 'btn btn-primary pull-right', 'name' => 'add-file', 'disabled' => 'disabled']) ?>
		<?= $this->Form->end() ?>
	</div>
</div>
