<?php

use App\Defines\Defines;
?>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('user_name', '技術者名') ?></th>
			<th><?= $this->Paginator->sort('search', '検索した企業の数') ?></th>
			<th><?= $this->Paginator->sort('view', '詳細閲覧した企業の数') ?></th>
			<th><?= $this->Paginator->sort('comment', 'コメントした企業の数') ?></th>
			<th><?= $this->Paginator->sort('count', '総アクセス数') ?></th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>

		<?php foreach ($contacts as $contact): ?>
			<tr>
				<td><?= h($contact->user_name) ?></td>
				<td class="text-right"><?= $contact->search ?></td>
				<td class="text-right"><?= $contact->view ?></td>
				<td class="text-right"><?= $contact->comment ?></td>
				<td class="text-right"><?= $contact->count ?></td>
				<td class="text-center">
					<?= $this->Html->link('<i class="fa fa-user fa-fw fa-lg"></i>', ['controller' => 'engineers', 'action' => 'view', $contact->engineer_id], ['escape' => false, 'title' => '技術者情報']) ?>
					<?php
					if (!empty($this->getLoginUser('enterprise_id')) && $this->getLoginUser('group_id') != Defines::GROUP_ENTERPRISE_FREE) {
						$enterprise_id = $this->getLoginUser('enterprise_id');
						echo $this->Html->link('<i class="fa fa-comment-o fa-fw fa-lg"></i>', ['controller' => 'comments', 'action' => 'view', $contact->engineer_id, $enterprise_id], ['escape' => false, 'title' => 'コメント']);
					}
					?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?= $this->Element('paginator');