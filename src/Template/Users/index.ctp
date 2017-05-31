<?php $this->append('script'); ?>
<script>
    $(function(){
        $('.link-delete').on('click',function(){
            if( confirm('本当に削除しますか？') ){
                form = $('form#delete-user');
                userId = $(this).attr('user_id');
                form.find('input[name="user-id"]').val(userId);
                form.submit();
            };
            return false;
        });
    });
</script>
<?php $this->end() ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Group</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach( $users as $user ): ?>
            <tr>
                <td><?= h($user->name) ?></td>
                <td><?= h($user->group->name ) ?></td>
                <td class="text-center">
                    <?=$this->Html->link('<i class="fa fa-lg fa-pencil-square-o"></i>',['controller'=>'users','action'=>'edit',$user->id],['escape'=>false,'title'=>'編集']) ?>
                    <?=$this->Html->link('<i class="fa fa-lg fa-trash-o"></i>',[],['escape'=>false,'title'=>'削除','class'=>'link-delete text-danger text-lg','user_id'=>$user->id]) ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<form id="delete-user" action="<?= $this->Url->build(['controller'=>'users','action'=>'delete'])?>" method="post">
    <input type="hidden" name="user-id" >
</form>

<?= $this->Element('paginator') ?>