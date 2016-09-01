<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Attribute'), ['action' => 'edit', $attribute->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Attribute'), ['action' => 'delete', $attribute->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attribute->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Attributes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Attribute'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="attributes view large-9 medium-8 columns content">
    <h3><?= h($attribute->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($attribute->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($attribute->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Type') ?></th>
            <td><?= $this->Number->format($attribute->type) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($attribute->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($attribute->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Note') ?></h4>
        <?= $this->Text->autoParagraph(h($attribute->note)); ?>
    </div>
</div>
