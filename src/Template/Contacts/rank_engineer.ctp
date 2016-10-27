
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('user_name', '技術者名') ?></th>
			<th>検索した企業の数</th>
			<th>コメントした企業の数</th>
			<th>宋アクセス数</th>
		</tr>
	</thead>
	<tbody>

		<?php foreach ($contacts as $contact): ?>
			<tr>
				<td><?= h($contact->user_name) ?></td>
				<td><?= $contact->search ?></td>
				<td><?= $contact->comment ?></td>
				<td><?= $contact->count ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>