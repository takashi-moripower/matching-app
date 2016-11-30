<h2>技術者メニュー</h2>

<ul class="nav nav-pills nav-stacked">
	<li role="presentation"><?= $this->Html->link('自己情報・閲覧', ['controller' => 'engineers', 'action' => 'view', $engineer->id]) ?></li>
	<li role="presentation"><?= $this->Html->link('自己情報・編集', ['controller' => 'users', 'action' => 'editSelf']) ?></li>
</ul>
<?php
echo $this->Element('notices', ['notices' => $notices]);
echo $this->Element('Engineers/warning');


echo $this->Element('Comments/list',['type'=>'engineer','label'=>'新着コメント','panel_type'=>'success','sort'=>false]);

