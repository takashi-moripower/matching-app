<?php

use App\Defines\Defines;
?>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('user_name', '技術者名') ?></th>
			<th><?= $this->Paginator->sort('search', '検索した企業の数') ?></th>
			<th><?= $this->Paginator->sort('view', '詳細閲覧した企業の数') ?></th>
			<th><?= $this->Paginator->sort('comment', 'コメントした企業の数') ?></th>
			<th><?= $this->Paginator->sort('count', '総アクセス数') ?></th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>

		<?php 
		foreach ($contacts as $contact){
			echo $this->Element('Contacts/rowRanking',['contact'=>$contact]);
		} 
		?>
		
	</tbody>
</table>

<?= $this->Element('paginator');