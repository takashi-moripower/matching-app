<?php
$list_SNS = \Cake\Core\Configure::read('SNS');
?>

<div class="panel panel-default panel-body col-lg-4 col-lg-offset-4">
	<?= $this->Flash->render('auth') ?>
	<?= $this->Form->create() ?>
	<fieldset>
		<legend class="text-center">メールアドレスでログイン</legend>
		<?= $this->Form->input('email') ?>
		<?= $this->Form->input('password') ?>
	</fieldset>
	<div class="text-right">
		<?= $this->Form->button(__('Login'), ['class' => 'btn-primary']); ?>
	</div>
	<?= $this->Form->end() ?>
</div>
<br>
<div class="panel panel-default panel-body col-lg-4 col-lg-offset-4">
	<legend class="text-center">外部アカウントでログイン</legend>

	<div class="text-center">
		<?php foreach ($list_SNS as $SNS): ?>
			<a href="<?= $this->Url->build(['controller' => 'users', 'action' => 'loginSns', $SNS]) ?>">
				<?= $this->Form->button(ucfirst($SNS)) ?>
			</a>
		<?php endforeach ?>
	</div>
</div>
<div class="panel panel-default panel-body col-lg-4 col-lg-offset-4">
	<legend class="clearfix">
		<div class="col-xs-4 col-xs-offset-4 text-center">
			新規登録
		</div>
		<div class="col-xs-4 text-right">
			<button class="btn btn-default"　type="button" data-toggle="collapse" data-target="#addNew" aria-expanded="false">
				<i class="fa fa-caret-down"></i>
			</button>
		</div>
	</legend>

	<div class="collapse text-center" id="addNew" >
		<?php foreach ($list_SNS as $SNS): ?>
			<a href="<?= $this->Url->build(['controller' => 'users', 'action' => 'addSns', $SNS]) ?>" class="btn btn-default">
				<?= ucfirst($SNS) ?>
			</a>
		<?php endforeach ?>
		<br>
		<br>
		<a href="<?= $this->Url->build(['controller' => 'users', 'action' => 'addEngineer']) ?>" class="btn btn-default col-xs-8 col-xs-offset-2">
			Email
		</a>
	</div>
</div>
