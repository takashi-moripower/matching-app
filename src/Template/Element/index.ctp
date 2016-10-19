<?php

use Cake\Utility\Hash;
use App\Defines\Defines;
?>
<table class='table table-bordered table-striped'>
	<thead>
		<tr>
			<?php foreach ($template['data'] as $t): ?>
				<th class='<?= Hash::get($t, 'hclass', 'text-center') ?>'>
					<?php
					if (Hash::get($t, 'flags', 0) & Defines::INDEX_FLAG_SORTABLE) {
						echo $this->Paginator->sort(Hash::get($t, 'key'), Hash::get($t, 'label'));
					} else {
						echo Hash::get($t, 'label');
					}
					?>
				</th>
			<?php endforeach ?>
			<?php if (isset($template['action'])): ?>
				<th class='text-center'>
					action
				</th>
			<?php endif ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($entities as $entity): ?>
			<tr>
				<?php foreach ($template['data'] as $t): ?>
					<td class='<?= Hash::get($t, 'dclass') ?>'>
						<?php
						$key = Hash::get($t, 'data_key', Hash::get($t, 'key'));
						echo h(Hash::get($entity, $key));
						?>
					</td>
				<?php endforeach ?>
				<?php
				if (isset($template['action'])) {
					echo "<td class='action link text-center'>";
					foreach ($template['action'] as $action) {
						$key = Hash::get($action, 'key', 'id');
						$url = Hash::get($action, 'url', []) + [ Hash::get($entity, $key)];
						$options = Hash::get($action, 'options', []) + ['escape' => false];
						echo $this->Html->link(Hash::get($action, 'label'), $url, $options);
						echo ' ';
					}
					echo "</td>";
				}
				?>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>