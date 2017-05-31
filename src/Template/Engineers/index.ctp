<?php

use App\Defines\Defines;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

$loginUser = $this->getLoginUser();
$table_c = TableRegistry::get('Contacts');

$index_template = [
    'data' => [
        ['key' => 'Users.name', 'label' => '氏名', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_key' => 'user.engineer_name'],
        ['key' => 'attribute_names', 'label' => '技能・資格', 'flags' => 0,],
        ['key' => 'birthday', 'label' => '年齢', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_key' => 'age', 'data_class' => 'text-right'],
    ],
];

//	企業が閲覧した場合
if (Defines::isEnterprise($loginUser['group_id'])) {
    $enterprise_id = $loginUser['enterprise_id'];

    //	コメント用callback function
    $comment_func = function( $entity ) use( $enterprise_id ) {
            return ['controller' => 'comments', 'action' => 'view', $entity->id, $enterprise_id];
    };

    //	基本情報の閲覧
    $index_template['action'][] = [ 'url' => ['controller'=>'engineers','action'=>'view'], 'label' => '<i class="fa fa-user fa-lg"></i>', 'options' => ['title' => '閲覧']];

    //	プレミアコースのみコメント可
    if ($loginUser['group_id'] == Defines::GROUP_ENTERPRISE_PREMIUM) {
        $index_template['action'][] = [ 'url' => $comment_func, 'label' => '<i class="fa fa-comment-o fa-lg"></i>', 'options' => ['title' => 'コメント']];
    }
}

//	管理者のみ詳細閲覧　編集　削除可能
if ($loginUser['group_id'] == Defines::GROUP_ADMINISTRATOR) {
    //エディット用callback function
    $edit_func = function( $entity ){
        return ['controller'=>'users' , 'action'=>'edit' , $entity->user_id ];
    };
    
    $index_template['action'][] = [ 'url' => ['controller'=>'engineers','action'=>'view'], 'label' => '<i class="fa fa-user fa-lg"></i>', 'options' => ['title' => '閲覧']];
    $index_template['action'][] = [ 'url' => $edit_func, 'label' => '<i class="fa fa-pencil-square-o fa-lg"></i>', 'options' => ['title' => '編集']];
    $index_template['action'][] = [ 'url' => '', 'label' => '<i class="fa fa-trash-o fa-lg text-danger"></i>', 'options' => ['title' => '削除' , 'class'=>'link-delete']];

}
?>
<?php if ($loginUser['group_id'] == Defines::GROUP_ADMINISTRATOR):?>
<div class="row">
    <div class="col-xs-9 text-right">
        <a class='btn btn-primary' href='<?= $this->Url->build(['controller'=>'users','action'=>'addEngineer'])?>'>新規作成</a>
    </div>
</div>
        <br>
<?php endif; ?>
<div class="row">
    <div class="col-xs-9">
        <?= $this->element('index', ['template' => $index_template, 'entities' => $engineers]) ?>
        <?= $this->Element('paginator') ?>
    </div>
    <div class="col-lg-3">
        <?= $this->Element('Engineers/search') ?>
    </div>
</div>

<form class="form-delete" method="post" action="<?= $this->url->build(['controller'=>'users','action'=>'delete']) ?>">
    <input type="hidden" name="user_id" />
    <input type="hidden" name="redirect" value="<?= $this->url->build(['controller'=>'engineers','action'=>'index'],true) ?>" />
</form>
<?php $this->append('script')?>
<script>
    $(function () {
        $('.link-delete').on('click', function () {
            id = $(this).parents('tr[user_id]').attr('user_id');
            name = $(this).parents('tr[name]').attr('name');
            if (confirm('技術者[' + name + ']を本当に削除しますか？')) {
                $(".form-delete input[name='user_id']").val(id);
                $(".form-delete").submit();
            }
            return false;
        });
    });
</script>
<?php $this->end() ?>


<?php
    unset( $search['limit'] );
    unset( $search['page']);
    if( empty( $search )){
        return;
    }
    if( !Defines::isEnterprise( $this->getLoginUser( 'group_id'))){
        return;
    }
    $table_contacts = TableRegistry::get('contacts');
    foreach( $engineers as $engineer ){
        $table_contacts->setEnterpriseAccess( $engineer->id , $enterprise_id , Defines::CONTACT_RECORD_SEARCH );
    }
?>
