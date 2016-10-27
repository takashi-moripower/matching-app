<?php

use App\Defines\Defines;

$loginUser = $this->getLoginUser();


$engineer_label = $contact->engineer->user->name;

switch ($loginUser['group_id']) {
	case Defines::GROUP_ENGINEER:
		$tab = 'Enterprises/viewTabs';
		break;

	case Defines::GROUP_ENTERPRISE_PREMIUM:
	case Defines::GROUP_ENTERPRISE_FREE:
		$tab = 'Engineers/viewTabs';
		break;

	case Defines::GROUP_ADMINISTRATOR:
		$tab = NULL;
		break;
}

$panel_class = $tab ? 'panel-under-tab' : '';
?>
<div class="col-lg-12">
	<?php if ($tab): ?>
		<?= $this->Element($tab, ['engineer_id' => $contact->engineer_id, 'enterprise_id' => $contact->enterprise_id]) ?>
	<?php endif ?>

	<div class="panel panel-default <?= $panel_class ?>">
		<table class="table table-bordered table-view table-view-engineers">
			<tbody>
				<tr>
					<th></th>
					<td><?= h($engineer_label) ?> <i class="fa fa-long-arrow-right"></i>　<?= h($contact->enterprise->user->name) ?></td>
					<td><?= h($contact->enterprise->user->name) ?> <i class="fa fa-long-arrow-right"></i>　<?= h($engineer_label) ?></td>
				</tr>
				<tr>
					<th>アクセス実績</th>
					<td><?= h($contact->engineer_record_text) ?></td>
					<td><?= h($contact->enterprise_record_text) ?></td>
				</tr>
				<tr>
					<th>最終アクセス</th>
					<td><?= h($contact->engineer_date) ?></td>
					<td><?= h($contact->enterprise_date) ?></td>
				</tr>
				<tr>
					<th>総アクセス数</th>
					<td><?= h($contact->engineer_count) ?></td>
					<td><?= h($contact->enterprise_count) ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>