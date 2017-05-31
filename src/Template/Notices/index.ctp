<?php

use App\Defines\Defines;
use Cake\I18n\FrozenTime;

FrozenTime::setToStringFormat('yyyy-MM-dd HH:mm');

$index_template = [
    'data' => [
        ['key' => 'id', 'label' => 'ID', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class' => 'text-right'],
        ['key' => 'content', 'label' => '内容', 'flags' => Defines::INDEX_FLAG_SORTABLE,],
        ['key' => 'start', 'label' => '開始', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class' => 'text-right',],
        ['key' => 'end', 'label' => '終了', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class' => 'text-right'],
        ['key' => 'flags', 'label' => '対象', 'flags' => Defines::INDEX_FLAG_SORTABLE, 'data_class'=>'text-center','data_key' => 'flags_text'],
    ],
    'action' => [
        [ 'url' => ['action' => 'edit'], 'label' => '<i class="fa fa-pencil-square-o fa-lg"></i>', 'options' => ['title' => '編集']],
        [ 'url'=>'' , 'label'=>'<i class="fa fa-trash-o fa-lg text-danger"></i>' , 'options'=>['title'=>'削除','class'=>'link-delete']],
    ],
];
?>
<div class='text-right'>
    <a class='btn btn-primary' href='<?= $this->Url->build(['action' => 'add']) ?>'>新規作成</a>
</div>
<br>
<?= $this->element('index', ['template' => $index_template, 'entities' => $notices]) ?>


<form class="form-delete" method="post" action="<?= $this->url->build(['controller'=>'notices','action'=>'delete']) ?>">
    <input type="hidden" name="id" />
</form>
<?php $this->append('script')?>
<script>
    $(function () {
        $('.link-delete').on('click', function () {
            id = $(this).parents('tr[entity_id]').attr('entity_id');
            if (confirm('通知[id=' + id + ']を本当に削除しますか？')) {
                $(".form-delete input[name='id']").val(id);
                $(".form-delete").submit();
            }
            return false;
        });
    });
</script>
<?php $this->end() ?>