<?php

use App\Defines\Defines;
use Cake\ORM\TableRegistry;

$group_name = Defines::GROUP_NAME;
$loginUser = $this->getLoginUser();
$table_c = TableRegistry::get('Contacts');

\Cake\I18n\FrozenTime::setToStringFormat('yyyy-MM-dd HH:mm');

$index_template = [
	'data' => [
		['key' => 'id', 'label' => 'id', 'flags' => Defines::INDEX_FLAG_SORTABLE,'data_class'=>'text-right'],
		['key' => 'engineer_id', 'label' => '技術者', 'flags' => Defines::INDEX_FLAG_SORTABLE,'data_class'=>'text-center','data_key'=>'engineer.user.name',],
		['key' => 'enterprise_id', 'label' => '企業', 'flags' => Defines::INDEX_FLAG_SORTABLE,'data_class'=>'text-center','data_key'=>'enterprise.user.name',],
	],
	'action' => [
		[ 'url' => function($entity){ return['controller'=>'contacts','action'=>'view',$entity->engineer_id,$entity->enterprise_id];}, 'label' => '<i class="fa fa-exchange fa-lg"></i>', 'options' => ['title' => '閲覧']],
	],
];

?>

<?= $this->element('index', ['template' => $index_template, 'entities' => $contacts]) ?>
