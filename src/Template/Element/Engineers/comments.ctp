<?php

use App\Defines\Defines;
use Cake\ORM\TableRegistry;

$table_enterprises = TableRegistry::get('enterprises');

if (empty($label)) {
	$label = "コメント";
}
if (empty($panel_type)) {
	$panel_type = "primary";
}
?>
<div class="panel panel-<?= $panel_type ?>">
	<div class="panel-heading"><?= $label ?></div>
	<table class="table table-bordered table-comments">
		<thead>
			<tr>
				<th class="text-center">対象</th>
				<th class="text-center">件数</th>
				<th class="text-center">既読</th>
				<th class="text-center">本文</th>
				<th class="text-center">送信時刻</th>
			</tr>
		</thead>
		<tbody>
			<?php
			\Cake\I18n\FrozenTime::setToStringFormat('yyyy-MM-dd HH:mm');

			foreach ($comments as $comment) {
				$enterprise = $table_enterprises->get($comment->enterprise_id, ['contain' => 'Users']);
				$url = ['controller' => 'comments', 'action' => 'view', $engineer->id, $enterprise->id];
				$read = $comment->last->flags & Defines::COMMENT_FLAG_READ_ENTERPRISE ? '既読' : '未読';
				echo "<tr>";
				echo "<td class='col-xs-2'>" . $this->Html->link(h($enterprise->user->name), $url) . "</td>";
				echo "<td class='col-xs-1 text-right'>" . $comment->count . "件</td>";
				echo "<td class='col-xs-1 text-center'>" . $read . "</td>";
				echo "<td class='col-xs-1 text-left'><div class='trim' style='width:50rem;max-width:50rem'>" . $comment->last->content_file . "</div></td>";
				echo "<td class='col-xs-2 text-center'>" . $comment->last_modified . "</td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>

</div>