<tr>
	<th><?= h($information->title) ?></th>
	<td>
		<?= h(nl2br($information->content)) ?>
		<?php foreach ($information->files as $file): ?>
			<?= $this->Element('Informations/file', ['file' => $file]) ?>
		<?php endforeach ?>
	</td>
</tr>
