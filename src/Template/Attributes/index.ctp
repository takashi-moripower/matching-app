<?php

use App\Defines\Defines;

$index_template = [
	'data' => [
            ['key' => 'id', 'label' => 'ID', 'flags' => Defines::INDEX_FLAG_SORTABLE,'data_class' => 'text-right' ],
            ['key' => 'name', 'label' => '名称', 'flags' => Defines::INDEX_FLAG_SORTABLE,],
            ['key' => 'type', 'label' => '分類', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class' => 'text-center' , 'data_key'=>'type_name'],
            ['key' => 'note', 'label' => '備考', 'flags' => Defines::INDEX_FLAG_SORTABLE,],
	],
	'action' => [
            [ 'url' => ['action' => 'edit'], 'label' => '<i class="fa fa-pencil-square-o fa-lg"></i>', 'options' => ['title' => '編集']],
            [ 'url'=>'' , 'label'=>'<i class="fa fa-trash-o fa-lg text-danger"></i>' , 'options'=>['title'=>'削除','class'=>'link-delete'] ]
	],
];
?>
<div class='text-right'>
    <a class='btn btn-primary' href='<?= $this->Url->build(['action'=>'add'])?>'>新規作成</a>
</div>
<br>
<?= $this->Element('index', ['template' => $index_template, 'entities' => $attributes]) ?>
<?= $this->Element('paginator') ?>

<form class="form-delete" method="post" action="<?= $this->url->build(['controller'=>'attributes','action'=>'delete']) ?>">
    <input type="hidden" name="id" >
</form>

<?php $this->append('script')?>
<script>
    $(function(){
        $('.link-delete').on('click',function(){
            id = $(this).parents('tr[entity_id]').attr('entity_id');
            if( confirm('属性[id='+id+']を本当に削除しますか？')){
                $(".form-delete input[name='id']").val(id);
                $(".form-delete").submit();
            }
            return false;
        });
    });
</script>
<?php $this->end() ?>