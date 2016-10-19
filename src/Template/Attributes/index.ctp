<?php 
use App\Defines\Defines;

$index_template = [
	'data' => [
		['key' => 'id', 'label' => 'ID', 'flags' => Defines::INDEX_FLAG_SORTABLE,'dclass' => 'text-right' ],
		['key' => 'name', 'label' => '名称', 'flags' => Defines::INDEX_FLAG_SORTABLE,],
		['key' => 'type', 'label' => '分類', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'dclass' => 'text-center' , 'data_key'=>'type_name'],
		['key' => 'note', 'label' => '備考', 'flags' => Defines::INDEX_FLAG_SORTABLE,],
	],
	'action' => [
		[ 'url' => ['action' => 'edit'], 'label' => '<i class="fa fa-pencil-square-o fa-lg"></i>', 'options' => ['title' => '編集']],
	],
];
?>
<div class='text-right'>
<a class='btn btn-default' href='<?= $this->Url->build(['action'=>'add'])?>'>新規作成</a>
</div>
<br>
<?= $this->element('index', ['template' => $index_template, 'entities' => $attributes]) ?>
