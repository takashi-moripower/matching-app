<?php
if (!isset($label)) {
	$label = "コメント";
}
if (!isset($panel_type)) {
	$panel_type = "primary";
}
if (!isset($type)) {
	$type = 'enterprise';
}

if( !isset($sort)){
	$sort = true;
}

$columns = [
	'対象' => 'engineer_name',
	'方向' => 'direction',
	'件数'=>'count',
	'既読'=>'read_enterprise',
	'本文'=>'content',
	'最終更新時刻'=>'modified',
];
if ($type == 'engineer') {
	$columns['対象'] = 'enterprise_name';
	$columns['既読'] = 'read_engineer';
}

?>

<div class="panel panel-<?= $panel_type ?>">
	<div class="panel-heading"><?= $label ?></div>
	<table class="table table-bordered table-comments">
		<thead>
			<tr>
				<?php foreach( $columns as $label => $key ){
					echo "<th class='text-center'>";
					if( $sort ){
						echo $this->Paginator->sort($key,$label);
					}else{
						echo $label;
					}
					echo "</th>";
				}?>
			</tr>
		</thead>
		<tbody>
			<?php
			\Cake\I18n\FrozenTime::setToStringFormat('yyyy-MM-dd HH:mm');

			foreach ($comments as $comment) {
				echo $this->Element('Comments/row', ['comment' => $comment, 'type' => $type]);
			}
			?>
		</tbody>
	</table>
</div>