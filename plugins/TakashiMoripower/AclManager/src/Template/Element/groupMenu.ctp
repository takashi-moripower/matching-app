<?php
$menu_items = [
	'グループ一覧' => ['action'=>'index'],
	'グループ追加' => ['action'=>'add'],
	'アクションリスト自動追加' => ['action'=>'update'],
	'アクションリスト初期化 '=> ['action'=>'format'],
	'アクセス権編集' => ['action'=>'permit'],
];

?>
<ul class="list-group">
	<?php foreach( $menu_items as $label => $url ):?>
	<li class="list-group-item">
		<a href="<?= $this->Url->build( $url )?>">
			<?=$label?>
		</a>
	</li>
	<?php endforeach ?>
</ul>