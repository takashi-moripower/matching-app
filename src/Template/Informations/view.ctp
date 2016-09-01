<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Information'), ['action' => 'edit', $information->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Information'), ['action' => 'delete', $information->id], ['confirm' => __('Are you sure you want to delete # {0}?', $information->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Informations'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Information'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Files'), ['controller' => 'Files', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New File'), ['controller' => 'Files', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="informations view large-9 medium-8 columns content">
    <h3><?= h($information->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('User') ?></th>
            <td><?= $information->has('user') ? $this->Html->link($information->user->id, ['controller' => 'Users', 'action' => 'view', $information->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($information->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($information->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Order') ?></th>
            <td><?= $this->Number->format($information->order) ?></td>
        </tr>
        <tr>
            <th><?= __('Published') ?></th>
            <td><?= $this->Number->format($information->published) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($information->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($information->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Content') ?></h4>
        <?= $this->Text->autoParagraph(h($information->content)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Files') ?></h4>
        <?php if (!empty($information->files)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Information Id') ?></th>
                <th><?= __('Title') ?></th>
                <th><?= __('Content') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($information->files as $files): ?>
            <tr>
                <td><?= h($files->id) ?></td>
                <td><?= h($files->information_id) ?></td>
                <td><?= h($files->title) ?></td>
                <td><?= h($files->content) ?></td>
                <td><?= h($files->created) ?></td>
                <td><?= h($files->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Files', 'action' => 'view', $files->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Files', 'action' => 'edit', $files->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Files', 'action' => 'delete', $files->id], ['confirm' => __('Are you sure you want to delete # {0}?', $files->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
