<?php

use App\Defines\Defines;
use Cake\ORM\TableRegistry;

\Cake\I18n\FrozenDate::setToStringFormat('yyyy-MM-dd');

$group_name = Defines::GROUP_NAME;
$loginUser = $this->getLoginUser();
$table_c = TableRegistry::get('Contacts');
?>

<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>企業名</th>
			<th>設立</th>
			<th>資本金</th>
			<th>従業員数</th>
			<?php if ($loginUser['group_id'] == Defines::GROUP_ENGINEER): ?>
				<th>技術者 <i class="fa fa-long-arrow-right"></i> 企業</th>
				<th>企業 <i class="fa fa-long-arrow-right"></i> 技術者</th>
				<?php endif ?>
				<?php if ($loginUser['group_id'] == Defines::GROUP_ADMINISTRATOR): ?>
				<th>グループ</th>
			<?php endif ?>
			<th>action</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($enterprises as $enterprise):
			if ($loginUser['group_id'] == Defines::GROUP_ENGINEER) {
				$enterprise->contact = $table_c->setEngineerAccess($loginUser['engineer_id'], $enterprise->id, Defines::CONTACT_RECORD_SEARCH);
			}
			?>

			<tr>
				<td><?= h($enterprise->user->name) ?></td>
				<td><?= h($enterprise->establish) ?></td>
				<td><?= h($enterprise->capital) ?></td>
				<td><?= h($enterprise->employee) ?></td>
				<?php if ($loginUser['group_id'] == Defines::GROUP_ENGINEER): ?>
					<td><?= $enterprise->contact->engineer_record_text ?></td>
					<td><?= $enterprise->contact->enterprise_record_text ?></td>
				<?php endif ?>
				<?php if ($loginUser['group_id'] == Defines::GROUP_ADMINISTRATOR): ?>
					<td><?= $group_name[$enterprise->user->group_id] ?></td>
					<td>
						<?= $this->Html->link('edit', ['controller' => 'users', 'action' => 'edit', $enterprise->user_id]) ?>
						<?= $this->Html->link('view', ['controller' => 'enterprises', 'action' => 'view', $enterprise->id]) ?>
					</td>
				<?php else: ?>
					<td>
						<?= $this->Html->link('view', ['controller' => 'enterprises', 'action' => 'view', $enterprise->id]) ?>
					</td>
				<?php endif ?>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<?= $this->element('paginator') ?>
