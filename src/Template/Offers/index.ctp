<?php

use App\Defines\Defines;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

$loginUser = $this->getLoginUser();
?>
<ul>
	<li><?= $this->Html->link('新規作成', ['controller'=>'offers','action'=>'add'])?></li>
</ul>
<div class="row">
	<div class="col-lg-9">
		<?php
		$enterprise_id = Hash::get($search, 'enterprise');
		$tab = false;
		if ($enterprise_id) {
			echo $this->Element('Enterprises/viewTabs', ['enterprise_id' => $enterprise_id]);
			$tab = true;
		}
		?>
		<div class="panel panel-default <?= $tab ? 'panel-under-tab' : '' ?>">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th><?= $this->Paginator->sort('enterprise_id', '企業名') ?></th>
						<th><?= $this->Paginator->sort('title', 'タイトル') ?></th>
						<th>必要技能</th>
						<th>action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($offers as $offer): ?>
						<tr>
							<td><a href="<?= $this->Url->build(['controller' => 'enterprises', 'action' => 'view', $offer->enterprise_id]) ?>"><?= h($offer->enterprise->user->name) ?></a></td>
							<td><?= h($offer->title) ?></td>
							<td><?= h($offer->attribute_text) ?></td>
							<td class="text-center">
								<?= $this->Acl->link('<i class="fa fa-search"></i>', ['controller' => 'offers', 'action' => 'search', $offer->id], ['escape' => false , 'title'=>'検索']) ?>
								<?= $this->Acl->link('<i class="fa fa-newspaper-o"></i>', ['controller' => 'offers', 'action' => 'view', $offer->id], ['escape' => false, 'title'=>'閲覧']) ?>
								<?= $this->Acl->link('<i class="fa fa-pencil-square-o"></i>', ['controller' => 'offers', 'action' => 'edit', $offer->id], ['escape' => false, 'title'=>'編集']) ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?= $this->element('paginator') ?>	
	</div>
	<div class="col-lg-3">
		<?= $this->Element('Offers/search') ?>
	</div>
</div>

<?=
(false == 'undefined') ? 'true' : 'false' ?>