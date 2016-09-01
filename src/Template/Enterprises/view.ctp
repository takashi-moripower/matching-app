<div class="col-lg-12">
	<?= $this->Element('Enterprises/viewTabs',['enterprise_id'=>$enterprise->id]) ?>
	
	<div class="panel panel-default panel-under-tab">
		<table class="table table-bordered table-view table-view-engineers">
			<tbody>
				<tr>
					<th>企業名</th>
					<td><?= h($enterprise->user->name) ?></td>
				</tr>
				<tr>
					<th>郵便番号</th>
					<td><?= h($enterprise->postalcode) ?></td>
				</tr>
				<tr>
					<th>所在地</th>
					<td><?= h($enterprise->address) ?></td>
				</tr>
				<tr>
					<th>電話番号</th>
					<td><?= h($enterprise->phone) ?></td>
				</tr>
				<tr>
					<th>担当者</th>
					<td><?= h($enterprise->personnel_name) ?></td>
				</tr>
				<tr>
					<th>担当者電話</th>
					<td><?= h($enterprise->personnel_phone) ?></td>
				</tr>
				<tr>
					<th>担当者Email</th>
					<td><?= h($enterprise->personnel_email) ?></td>
				</tr>
				<tr>
					<th>設立</th>
					<td><?= h($enterprise->establish->format('Y-m-d')) ?></td>
				</tr>
				<tr>
					<th>資本金</th>
					<td><?= h($enterprise->capital) ?></td>
				</tr>
				<tr>
					<th>従業員数</th>
					<td><?= h($enterprise->employee) ?></td>
				</tr>
				<?php foreach ($enterprise->user->informations as $information): ?>
					<tr>
						<th><?= h($information->title) ?></th>
						<td>
							<?= h($information->content) ?>
							<?php foreach ($information->files as $file): ?>
								<?= $this->Element('Informations/file', ['file' => $file]) ?>
							<?php endforeach ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>