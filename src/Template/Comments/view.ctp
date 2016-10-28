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
		<?= $this->Html->link($engineer->user->name . ' &nbsp;<i class="fa fa-user fa-lg"></i>', ['controller' => 'engineers', 'action' => 'view', $engineer->id], [ 'class' => 'pull-right', 'escape' => false]) ?>
	</div>
	<div class="panel-body">
		<?php
		foreach ($comments as $comment) {
			echo $this->Element('Comments/baloon', ['comment' => $comment]);
		}
		?>	
	</div>
</div>
<?php
if ($this->getLoginUser('group_id') != Defines::GROUP_ENTERPRISE_FREE) {
	echo $this->Element('Comments/form');
}
?>
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


		$(window).on('resize', setPanelHeight);

		setPanelHeight();

		panel = $('.comments .panel-body');
		panel.scrollTop(panel[0].scrollHeight);

	});

	function setPanelHeight() {
		panel = $('.comments .panel-body');

		ph = panel.height();

		wh = $(window).height();

		bh = $('body').height();

		ph2 = Math.max(wh - bh + ph, 240);

		panel.height(ph2);
	}
</script><?php $this->end() ?>
