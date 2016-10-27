<?php

use App\Defines\Defines;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

$group_name = Defines::GROUP_NAME;
$loginUser = $this->getLoginUser();
$table_c = TableRegistry::get('Contacts');



$index_template = [
	'data' => [
		['key' => 'Users.name', 'label' => '氏名', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_key' => 'user.name'],
		['key' => 'attribute_names', 'label' => '技能・資格', 'flags' => 0,],
		['key' => 'birthday', 'label' => '年齢', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_key' => 'age', 'data_class' => 'text-right'],
	],
];



//	企業が閲覧した場合
if (!empty($loginUser['enterprise_id'])) {
	$enterprise_id = $loginUser['enterprise_id'];

	//	コメント用callback function
	$comment_func = function( $entity ) use( $enterprise_id ) {
		return ['controller' => 'comments', 'action' => 'view', $entity->id, $enterprise_id];
	};
	
	//	基本情報の閲覧
	$index_template['action'][] = [ 'url' => ['controller'=>'engineers','action'=>'view'], 'label' => '<i class="fa fa-user fa-lg"></i>', 'options' => ['title' => '閲覧']];

	//	プレミアコースのみコメント可
	if ($loginUser['group_id'] == Defines::GROUP_ENTERPRISE_PREMIUM) {
		$index_template['action'][] = [ 'url' => $comment_func, 'label' => '<i class="fa fa-comment-o fa-lg"></i>', 'options' => ['title' => 'コメント']];
	}
}

//	管理者のみ詳細閲覧可能
if ($loginUser['group_id'] == Defines::GROUP_ADMINISTRATOR) {
	$index_template['action'][] = [ 'url' => ['controller'=>'engineers','action'=>'view'], 'label' => '<i class="fa fa-user fa-lg"></i>', 'options' => ['title' => '閲覧']];
}?>
<div class="row">
	<div class="col-xs-9">
		<?= $this->element('index', ['template' => $index_template, 'entities' => $engineers]) ?>
		<?= $this->Element('paginator') ?>
	</div>
	<div class="col-lg-3">
		<?= $this->Element('Engineers/search') ?>
	</div>
</div>


<?php
unset( $search['limit'] );
unset( $search['page']);
if( empty( $search )){
	return;
}
if( !Defines::isEnterprise( $this->getLoginUser( 'group_id'))){
	return;
}
$table_contacts = TableRegistry::get('contacts');
foreach( $engineers as $engineer ){
	$table_contacts->setEnterpriseAccess( $engineer->id , $enterprise_id , Defines::CONTACT_RECORD_SEARCH );
}
?>
