<?php

use App\Defines\Defines;
use Cake\ORM\TableRegistry;

$group_name = Defines::GROUP_NAME;
$table_c = TableRegistry::get('Contacts');
$loginUser = $this->getLoginUser();
?>
<div class="row">
	<div class="col-lg-9">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>氏名</th>
					<th>資格・技能</th>
					<th>年齢</th>
					<?php if (Defines::isEnterprise($loginUser['group_id'])): ?>
						<th>技術者 <i class="fa fa-long-arrow-right"></i> 企業</th>
						<th>企業 <i class="fa fa-long-arrow-right"></i> 技術者</th>
					<?php endif ?>
					<th>action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($engineers as $engineer): ?>
					<tr>
						<td class="col-lg-2"><?= h($engineer->user->name) ?></td>
						<td class="col-lg-7 "><?= h($engineer->attribute_names) ?></td>
						<td class="col-lg-1"><?= $engineer->age ?></td>

						<?php
						if (Defines::isEnterprise($loginUser['group_id'])):
							$engineer->contact = $table_c->setEnterpriseAccess($engineer->id, $loginUser['enterprise_id'], Defines::CONTACT_RECORD_SEARCH);
							?>
							<td class="col-lg-1 trim"><?= $engineer->contact->engineer_record_text ?></td>
							<td class="col-lg-1 trim"><?= $engineer->contact->enterprise_record_text ?></td>
						<?php endif ?>
						<td class="col-lg-1 text-center">
							<?= $this->Html->link( '<i class="fa fa-search"></i>', ['controller' => 'engineers', 'action' => 'view', $engineer->id],['escape'=>false]) ?>
							<?php
							if ($this->getLoginUser('group_id') == Defines::GROUP_ADMINISTRATOR) {
								echo $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'users', 'action' => 'edit', $engineer->user_id],['escape'=>false]);
							}
							?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<?= $this->element('paginator') ?>	
	</div>
	<div class="col-lg-3">
		<?= $this->Element('Engineers/search') ?>
	</div>

</div>
