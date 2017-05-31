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
        ['key' => 'content', 'label' => '内容', 'flags' => Defines::INDEX_FLAG_NO_ESCAPE,'data_class'=>'trim trim40','data_key'=>'content_with_file'],
        ['key' => 'modified', 'label' => '時刻', 'flags' => Defines::INDEX_FLAG_SORTABLE,'data_class'=>'text-center'],
    ],
];

?>

<?= $this->element('index', ['template' => $index_template, 'entities' => $comments]) ?>
