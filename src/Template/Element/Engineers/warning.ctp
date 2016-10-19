<?php
$params = [
	'postalcode' => '郵便番号',
	'address' => '住所',
	'birthday' => '生年月日',
	'phone' => '電話番号',
	'education' => '最終学歴',
];

$warning = [];

foreach ($params as $key => $label) {
	if (empty($engineer->{$key})) {
		$warning[] = $label;
	}
}

if (empty($warning) && !empty($engineer->attributes)) {
	return;
}
?>
<br>

<div class="panel panel-warning">
	<div class="panel-heading">
		警告：未入力項目があります
	</div>
	<div class="panel-body">
		<?= implode(',', $warning) ?>の項目が未入力です<br>
		<div class="text-right">
			<?= $this->Html->link('連絡先', ['controller' => 'engineers', 'action' => 'addressSelf']) ?>
		</div>

		<?php if (empty($engineer->attributes)): ?>
			技能・経歴が未登録です
			<div class="text-right">
				<?= $this->Html->link('技能・経歴', ['controller' => 'engineers', 'action' => 'attributesSelf']) ?>
			</div>
		<?php endif ?>
	</div>
</div>
	<?php

