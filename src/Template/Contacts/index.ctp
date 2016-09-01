<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Contact'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Engineers'), ['controller' => 'Engineers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Engineer'), ['controller' => 'Engineers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Enterprises'), ['controller' => 'Enterprises', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Enterprise'), ['controller' => 'Enterprises', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="contacts index large-9 medium-8 columns content">
    <h3><?= __('Contacts') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('engineer_id') ?></th>
                <th><?= $this->Paginator->sort('enterprise_id') ?></th>
                <th><?= $this->Paginator->sort('engineer_access') ?></th>
                <th><?= $this->Paginator->sort('engineer_viewdate') ?></th>
                <th><?= $this->Paginator->sort('engineer_interest') ?></th>
                <th><?= $this->Paginator->sort('enterprise_access') ?></th>
                <th><?= $this->Paginator->sort('enterprise_viewdate') ?></th>
                <th><?= $this->Paginator->sort('enterprise_interest') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?= $this->Number->format($contact->id) ?></td>
                <td><?= $contact->has('engineer') ? $this->Html->link($contact->engineer->name, ['controller' => 'Engineers', 'action' => 'view', $contact->engineer->id]) : '' ?></td>
                <td><?= $contact->has('enterprise') ? $this->Html->link($contact->enterprise->name, ['controller' => 'Enterprises', 'action' => 'view', $contact->enterprise->id]) : '' ?></td>
                <td><?= $this->Number->format($contact->engineer_access) ?></td>
                <td><?= h($contact->engineer_viewdate) ?></td>
                <td><?= $this->Number->format($contact->engineer_interest) ?></td>
                <td><?= $this->Number->format($contact->enterprise_access) ?></td>
                <td><?= h($contact->enterprise_viewdate) ?></td>
                <td><?= h($contact->enterprise_interest) ?></td>
                <td><?= h($contact->created) ?></td>
                <td><?= h($contact->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $contact->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $contact->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $contact->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contact->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
