<?php

use App\Defines\Defines;
use Cake\I18n\FrozenTime;

FrozenTime::setToStringFormat('yyyy-MM-dd HH:mm');

$index_template = [
	'data' => [
		['key' => 'id', 'label' => 'ID', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class' => 'text-right'],
		['key' => 'content', 'label' => '内容', 'flags' => Defines::INDEX_FLAG_SORTABLE,],
		['key' => 'start', 'label' => '開始', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class' => 'text-right',],
		['key' => 'end', 'label' => '終了', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class' => 'text-right'],
		['key' => 'flags', 'label' => '対象', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class'=>'text-center','data_key' => 'flags_text'],
	],
	'action' => [
		[ 'url' => ['action' => 'edit'], 'label' => '<i class="fa fa-pencil-square-o fa-lg"></i>', 'options' => ['title' => '編集']],
	],
];
?>
<div class='text-right'>
	<a class='btn btn-default' href='<?= $this->Url->build(['action' => 'add']) ?>'>新規作成</a>
</div>
<br>
<?= $this->element('index', ['template' => $index_template, 'entities' => $notices]) ?>
