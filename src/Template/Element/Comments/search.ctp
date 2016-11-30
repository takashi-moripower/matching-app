<div class="panel panel-default">
	<div class="panel-heading">Search</div>
	<div class="panel-body">
	<?= $this->Form->create(NULL) ?>
	<?= $this->Form->input('freeword',['placeHolder'=>'free word','label'=>false]) ?>
		<?= $this->Form->submit('search') ?>
	<?= $this->Form->end() ?>
	</div>
</div>
