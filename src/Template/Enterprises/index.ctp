<?php

use App\Defines\Defines;
use Cake\ORM\TableRegistry;

$group_name = Defines::GROUP_NAME;
$loginUser = $this->getLoginUser();
$table_c = TableRegistry::get('Contacts');

$index_template = [
	'data' => [
		['key' => 'Users.name', 'label' => '企業名', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_key' => 'user.name'],
		['key' => 'establish', 'label' => '設立', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'dclass' => 'text-right'],
		['key' => 'capital', 'label' => '資本金', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'dclass' => 'text-right'],
		['key' => 'employee', 'label' => '従業員数', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'dclass' => 'text-right'],
	],
	'action' => [
		[ 'url' => ['controller' => 'users', 'action' => 'view'], 'label' => '<i class="fa fa-search fa-lg"></i>', 'options' => ['title' => '閲覧']],
	],
];

if ($loginUser['group_id'] == Defines::GROUP_ADMINISTRATOR) {
	$index_template['action'][] = [
		'url' => ['controller' => 'enterprises', 'action' => 'edit'],
		'label' => '<i class="fa fa-pencil-square-o fa-lg"></i>',
		'key' => 'user_id',
		'options' => ['title' => '編集'],
	];
}
if ($loginUser['group_id'] == Defines::GROUP_ENGINEER) {
	$index_template['data'][] = ['key' => 'contact.engineer_record_text', 'label' => '技術者 <i class="fa fa-long-arrow-right"></i> 企業', 'flags' => 0, ];
	$index_template['data'][] = ['key' => 'contact.enterprise_record_text', 'label' => '企業 <i class="fa fa-long-arrow-right"></i> 技術者', 'flags' => 0,];
}

foreach ($enterprises as $enterprise) {
	if ($loginUser['group_id'] == Defines::GROUP_ENGINEER) {
		$enterprise->contact = $table_c->setEngineerAccess($loginUser['engineer_id'], $enterprise->id, Defines::CONTACT_RECORD_SEARCH);
	}
}
?>

<?= $this->element('index', ['template' => $index_template, 'entities' => $enterprises]) ?>
