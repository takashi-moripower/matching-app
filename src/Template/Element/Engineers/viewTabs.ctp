<?php

use App\Defines\Defines;

$tabs = [
	'技術者情報' => ['controller' => 'engineers', 'action' => 'view', $engineer_id],
];

$loginUser = $this->getLoginUser();

if ( $loginUser['group_id'] == Defines::GROUP_ENTERPRISE_PREMIUM ){
	$tabs += [
		'相互関心度' => ['controller' => 'contacts', 'action' => 'view', $engineer_id, $loginUser['enterprise_id']],
		'コメント' => ['controller' => 'comments', 'action' => 'view', $engineer_id, $loginUser['enterprise_id']],
	];
}
if ( $loginUser['group_id'] == Defines::GROUP_ENTERPRISE_FREE ){
	$tabs += [
		'相互関心度' => ['controller' => 'contacts', 'action' => 'view', $engineer_id, $loginUser['enterprise_id']],
	];
}
?>


<ul class="nav nav-tabs">
	<?php foreach ($tabs as $label => $url): ?>
		<li role="presentation" class="<?= $this->isMatch($url['controller'], $url['action']) ? 'active' : '' ?>">
			<?= $this->Html->link($label, $url) ?>
		</li>
	<?php endforeach ?>
</ul>			
