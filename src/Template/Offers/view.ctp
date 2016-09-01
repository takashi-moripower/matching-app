<?php

use App\Defines\Defines;
use Cake\Utility\Hash;
?>
<div class="panel panel-default">
	<table class="table">
		<tr>
			<th>企業名</th>
			<td><?= $offer->enterprise->user->name ?></td>
		</tr>
		<tr>
			<th>タイトル</th>
			<td><?= $offer->title ?></td>
		</tr>
		<tr>
			<th>属性</th>
			<td>
				<?= $offer->attribute_text ?>
			</td>
		</tr>
	</table>
</div>
