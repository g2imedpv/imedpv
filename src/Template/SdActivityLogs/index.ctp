<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdActivityLog[]|\Cake\Collection\CollectionInterface $sdActivityLogs
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sd Activity Log'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Users'), ['controller' => 'SdUsers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd User'), ['controller' => 'SdUsers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdActivityLogs index large-9 medium-8 columns content">
    <h3><?= __('Sd Activity Logs') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('controller') ?></th>
                <th scope="col"><?= $this->Paginator->sort('action') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_section_value_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_actvity_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sdActivityLogs as $sdActivityLog): ?>
            <tr>
                <td><?= $this->Number->format($sdActivityLog->id) ?></td>
                <td><?= $sdActivityLog->has('sd_user') ? $this->Html->link($sdActivityLog->sd_user->title, ['controller' => 'SdUsers', 'action' => 'view', $sdActivityLog->sd_user->id]) : '' ?></td>
                <td><?= h($sdActivityLog->controller) ?></td>
                <td><?= h($sdActivityLog->action) ?></td>
                <td><?= $this->Number->format($sdActivityLog->sd_section_value_id) ?></td>
                <td><?= h($sdActivityLog->updated_time) ?></td>
                <td><?= $this->Number->format($sdActivityLog->sd_actvity_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sdActivityLog->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sdActivityLog->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sdActivityLog->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdActivityLog->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
