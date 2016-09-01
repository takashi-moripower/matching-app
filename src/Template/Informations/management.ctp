<?php

use App\Defines\Defines;
?>
<h2><?= h($user->name) ?></h2>
<?php
echo $this->Element('Users/editTabs', ['user' => $user]);
?>
<div class="panel panel-default panel-under-tab">
	<?php foreach ($informations as $information): ?>
		<?= $this->Element('Informations/edit', ['information' => $information]) ?>
	<?php endforeach ?>
<?= $this->Element('Informations/edit', ['information' => $new_information]) ?>
</div>



<div class="hidden" role="file-input-source">
<?= $this->Form->File('files[]') ?>
</div>

<?php $this->append('script') ?>
<script>
	$(function () {
		$('body').on({
			click: function () {
				html = $('div[role="file-input-source"]').html();
				$(this).parents('td.files').find('.files-end').prepend(html);
			}
		}, 'button[name="add-file"]');
	});
</script>
<?php $this->end() ?>