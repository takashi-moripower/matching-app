<?php
use App\Defines\Defines;
?>
<h2>管理者メニュー</h2>

<ul class="nav nav-pills nav-stacked">
	<li role="presentation"><?= $this->Html->link('企業',['controller'=>'enterprises','action'=>'index'])?></li>
	<li role="presentation"><?= $this->Html->link('技術者',['controller'=>'engineers','action'=>'index'])?></li>
	<li role="presentation"><?= $this->Html->link('技能、資格',['controller'=>'attributes','action'=>'index'])?></li>
	<li role="presentation"><?= $this->Html->link('求人',['controller'=>'offers','action'=>'index'])?></li>
	<li role="presentation"><?= $this->Html->link('権限',['plugin'=>'TakashiMoripower/AclManager','controller'=>'groups','action'=>'index'])?></li>
	<li role="presentation"><?= $this->Html->link('通知',['controller'=>'notices','action'=>'index'])?></li>
	<li role="separator" class="divider"><hr></li>
	<li role="presentation"><?= $this->Html->link('技術者追加',['controller'=>'users','action'=>'addEngineer'])?></li>
	<li role="presentation"><?= $this->Html->link('企業追加',['controller'=>'users','action'=>'addEnterprise'])?></li>
	<li role="separator" class="divider"><hr></li>
	<li role="presentation"><?= $this->Html->link('debug アカウント切り替え',['controller'=>'debug','action'=>'loginAs'])?></li>
	<li role="presentation"><?= $this->Html->link('debug ダミーデータ生成　企業',['controller'=>'debug','action'=>'dummyEnterprises'])?></li>
	<li role="presentation"><?= $this->Html->link('debug ダミーデータ生成　技術者',['controller'=>'debug','action'=>'dummyEngineers'])?></li>
	<li role="presentation"><?= $this->Html->link('debug ダミーデータ生成　求人',['controller'=>'debug','action'=>'dummyOffers'])?></li>
	<li role="presentation"><?= $this->Html->link('debug データ消去　企業',['controller'=>'debug','action'=>'deleteEnterprises'])?></li>
	<li role="presentation"><?= $this->Html->link('debug データ消去　技術者',['controller'=>'debug','action'=>'deleteEngineers'])?></li>
	<li role="presentation"><?= $this->Html->link('debug データ消去　関係',['controller'=>'debug','action'=>'deleteContacts'])?></li>
	<li role="presentation"><?= $this->Html->link('debug データ消去　求人',['controller'=>'debug','action'=>'deleteOffers'])?></li>
</ul>

<?php 