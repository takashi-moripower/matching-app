<?php
use App\Defines\Defines;
?>
<?php

switch ($this->getLoginUser('group_id')) {
	case Defines::GROUP_ENGINEER:
		$tab = 'Enterprises/viewTabs';
		break;

	case Defines::GROUP_ENTERPRISE_PREMIUM:
	case Defines::GROUP_ENTERPRISE_FREE:
		$tab = 'Engineers/viewTabs';
		break;
}
?>
<?= $this->Element($tab, ['engineer_id' => $engineer->id, 'enterprise_id' => $enterprise->id]) ?>
<div class="panel panel-primary comments">
	<div class="panel-heading clearfix">
		<?= $this->Html->link('<i class="fa fa-building-o fa-lg"></i> &nbsp;' . h($enterprise->user->name), ['controller' => 'enterprises', 'action' => 'view', $enterprise->id], [ 'class' => 'pull-left', 'escape' => false]) ?>
		<?= $this->Html->link( $engineer->user->name  . ' &nbsp;<i class="fa fa-user fa-lg"></i>', ['controller' => 'engineers', 'action' => 'view', $engineer->id], [ 'class' => 'pull-right', 'escape' => false]) ?>
	</div>
	<div class="panel-body">
	<?php
	foreach ($comments as $comment) {
		echo $this->Element('Comments/baloon', ['comment' => $comment]);
	}
	?>	
	</div>
</div>
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
<?php $this->append('script') ?>
<script>
	$(function () {
		$('.comment-file input[type="file"]').on("change", function () {
			var file = this.files[0];
			if (file != null) {
				$('.comment-file button[name="add-file"]').removeAttr('disabled');
			}
		});
		$('.comment-file input[type="file"]').trigger('change');
		
		
		$(window).on('resize',setPanelHeight);
		
		setPanelHeight();
		
		panel = $('.comments .panel-body');
		panel.scrollTop( panel[0].scrollHeight);
		
	});
	
	function setPanelHeight(){
		panel = $('.comments .panel-body');
		
		ph = panel.height();
		
		wh = $(window).height();
				
		bh = $('body').height();
		
		ph2 = Math.max( wh - bh + ph , 240 );
		
		panel.height( ph2 );
	}
</script><?php $this->end() ?>
