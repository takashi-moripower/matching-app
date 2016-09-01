<?php
$code = sprintf( '%04d' , rand(0,9999) );
?>
<div class="panel panel-default panel-body">
	<p class="alert alert-danger text-center">	警告</p>
	<p>権限テーブルを初期化すると、<br>
	すべてのユーザーがすべてのアクションにアクセスできなくなり<br>
	通常の手法では復元できません</p>
	<p>実行する場合は　初期化コード[<?= $code ?>]を入力してください</p>
</div>

<div class="col-lg-4 col-lg-offset-4 panel panel-default panel-body">
<?= $this->Form->create(NULL) ?>
	<fieldset>
		<label>初期化コード</label>
<?= $this->Form->text('code')?>
<?= $this->Form->hidden('code2',['value'=>$code ,]) ?>
		<br>
<?= $this->Form->submit('実行') ?>
	</fieldset>
<?= $this->Form->end()?>
</div>