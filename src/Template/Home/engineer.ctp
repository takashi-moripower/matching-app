<h2>技術者メニュー</h2>

<ul class="nav nav-pills nav-stacked">
	<li role="presentation"><?= $this->Html->link('自己情報・閲覧',['controller'=>'engineers','action'=>'view',$engineer->id])?></li>
	<li role="presentation"><?= $this->Html->link('自己情報・編集',['controller'=>'users','action'=>'editSelf'])?></li>
	<li role="presentation"><?= $this->Html->link('企業検索',['controller'=>'enterprises','action'=>'index'])?></li>
	<li role="presentation"><?= $this->Html->link('求人検索',['controller'=>'offers','action'=>'match'])?></li>
</ul>

