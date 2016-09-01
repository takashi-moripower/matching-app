<?= $this->Element('Enterprises/viewTabs', ['enterprise_id' => $enterprise_id]) ?>

<div class="panel panel-default panel-under-tab">
	<table class="table">
		<?php foreach ($comments as $comment): ?>
			<tr>
				<td><?= nl2br(h($comment->content)) ?></td>
				<td class="text-right">
					<?= $comment->created ?><br>
					<?= $comment->sender_name ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if( empty( $comment )): ?>
			<tr>
				<td>no comment</td>
			</tr>
		<?php endif ?>
	</table>
</div>
<div class="panel panel-default">
	<div class="panel-body">
		<?= $this->Form->create(NULL) ?>
		<?= $this->Form->textArea('content') ?>
		<?= $this->Form->hidden('engineer_id', ['value' => $engineer_id]) ?>
		<?= $this->Form->hidden('enterprise_id', ['value' => $enterprise_id]) ?>
		<?= $this->Form->hidden('flags', ['value' => $flags]) ?>
		<?= $this->Form->button('送信') ?>
		<?= $this->Form->end() ?>
	</div>
</div>
