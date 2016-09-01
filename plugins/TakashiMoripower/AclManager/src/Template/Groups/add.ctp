
<?= $this->Form->create($group) ?>
<fieldset>
	<legend><?= __('Add Group') ?></legend>
	<?php
	echo $this->Form->input('name');
	?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
