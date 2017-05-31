<?php

use App\Defines\Defines;
use Cake\ORM\TableRegistry;

$group_name = Defines::GROUP_NAME;
$loginUser = $this->getLoginUser();
$table_c = TableRegistry::get('Contacts');

$index_template = [
	'data' => [
		['key' => 'Users.name', 'label' => '企業名', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_key' => 'user.name'],
		['key' => 'establish', 'label' => '設立', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class' => 'text-right'],
		['key' => 'capital', 'label' => '資本金', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class' => 'text-right'],
		['key' => 'employee', 'label' => '従業員数', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class' => 'text-right'],
	],
	'action' => [
		[ 'url' => ['controller' => 'enterprises', 'action' => 'view'], 'label' => '<i class="fa fa-building-o fa-lg"></i>', 'options' => ['title' => '閲覧']],
	],
];

if ($loginUser['group_id'] == Defines::GROUP_ADMINISTRATOR) {
    $index_template['action'][] = [
        'url' => ['controller' => 'users', 'action' => 'edit'],
        'label' => '<i class="fa fa-pencil-square-o fa-lg"></i>',
        'key' => 'user_id',
        'options' => ['title' => '編集'],
    ];
    $index_template['action'][] = [
        'url' => '',
        'label' => '<i class="fa fa-trash-o fa-lg text-danger"></i>',
        'options' => ['title' => '削除','class'=>'link-delete'],
    ];
    $index_template['data'][] = ['key' => 'Users.group_id', 'label' => '区分', 'flags'=>Defines::INDEX_FLAG_SORTABLE , 'data_key'=>'user.group.name', 'data_class'=>'text-center'];
}
if ($loginUser['group_id'] == Defines::GROUP_ENGINEER) {
    $index_template['data'][] = ['key' => 'contact.engineer_record_text', 'label' => '技術者 <i class="fa fa-long-arrow-right"></i> 企業', 'flags' => 0, ];
    $index_template['data'][] = ['key' => 'contact.enterprise_record_text', 'label' => '企業 <i class="fa fa-long-arrow-right"></i> 技術者', 'flags' => 0,];
}

foreach ($enterprises as $enterprise) {
	if ($loginUser['group_id'] == Defines::GROUP_ENGINEER) {
		$enterprise->contact = $table_c->setEngineerAccess($loginUser['engineer_id'], $enterprise->id, Defines::CONTACT_RECORD_SEARCH);
	}
}
?>

<?php if ($loginUser['group_id'] == Defines::GROUP_ADMINISTRATOR):?>
<div class="row">
    <div class="col-xs-12 text-right">
        <a class='btn btn-primary' href='<?= $this->Url->build(['controller'=>'users','action'=>'addEnterprise'])?>'>新規作成</a>
    </div>
</div>
<br>
<?php endif; ?>

<?= $this->element('index', ['template' => $index_template, 'entities' => $enterprises]) ?>


<form class="form-delete" method="post" action="<?= $this->url->build(['controller'=>'users','action'=>'delete']) ?>">
    <input type="hidden" name="user_id" />
    <input type="hidden" name="redirect" value="<?= $this->url->build(['controller'=>'enterprises','action'=>'index'],true) ?>" />
</form>
<?php $this->append('script')?>
<script>
    $(function(){
        $('.link-delete').on('click',function(){
            id = $(this).parents('tr[user_id]').attr('user_id');
            name = $(this).parents('tr[name]').attr('name');
            if( confirm('企業['+name+']を本当に削除しますか？')){
                $(".form-delete input[name='user_id']").val(id);
                $(".form-delete").submit();
            }
            return false;
        });
    });
</script>
<?php $this->end() ?>
