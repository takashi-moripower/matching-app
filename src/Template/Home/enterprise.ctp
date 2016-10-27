<h2>企業メニュー</h2>

<ul class="nav nav-pills nav-stacked">
	<li role="presentation"><?= $this->Html->link('企業情報・閲覧',['controller'=>'enterprises','action'=>'view',$enterprise->id])?></li>
	<li role="presentation"><?= $this->Html->link('企業情報・編集',['controller'=>'users','action'=>'editSelf'])?></li>
	<li role="presentation"><?= $this->html->link('求人情報',['controller'=>'offers','action'=>'index'])?></li>
	<li role="presentation"><?= $this->html->link('技術者ランキング',['controller'=>'contacts','action'=>'rankEngineer'])?></li>
	<li role="presentation"><?= $this->Html->link('技術者検索',['controller'=>'engineers','action'=>'index'])?></li>
	<li role="presentation"><?= $this->Html->link('コメント',['controller'=>'comments','action'=>'index'])?></li>
</ul>

<?php
echo $this->Element('notices',['notices'=>$notices]);

echo $this->Element('Enterprises/comments',['label'=>'新着コメント','panel_type'=>'success']);