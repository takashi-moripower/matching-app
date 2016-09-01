<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit File'), ['action' => 'edit', $file->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete File'), ['action' => 'delete', $file->id], ['confirm' => __('Are you sure you want to delete # {0}?', $file->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Files'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New File'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Informations'), ['controller' => 'Informations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Information'), ['controller' => 'Informations', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="files view large-9 medium-8 columns content">
    <h3><?= h($file->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Information') ?></th>
            <td><?= $file->has('information') ? $this->Html->link($file->information->title, ['controller' => 'Informations', 'action' => 'view', $file->information->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($file->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($file->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($file->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($file->modified) ?></td>
        </tr>
    </table>
</div>
