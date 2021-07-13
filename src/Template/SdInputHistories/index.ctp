<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdInputHistory[]|\Cake\Collection\CollectionInterface $sdInputHistories
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sd Input History'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Field Values'), ['controller' => 'SdFieldValues', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Field Value'), ['controller' => 'SdFieldValues', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Users'), ['controller' => 'SdUsers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd User'), ['controller' => 'SdUsers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdInputHistories index large-9 medium-8 columns content">
    <h3><?= __('Sd Input Histories') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_field_value_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('time_changed') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sdInputHistories as $sdInputHistory): ?>
            <tr>
                <td><?= $this->Number->format($sdInputHistory->id) ?></td>
                <td><?= $sdInputHistory->has('sd_field_value') ? $this->Html->link($sdInputHistory->sd_field_value->id, ['controller' => 'SdFieldValues', 'action' => 'view', $sdInputHistory->sd_field_value->id]) : '' ?></td>
                <td><?= $sdInputHistory->has('sd_user') ? $this->Html->link($sdInputHistory->sd_user->title, ['controller' => 'SdUsers', 'action' => 'view', $sdInputHistory->sd_user->id]) : '' ?></td>
                <td><?= h($sdInputHistory->time_changed) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sdInputHistory->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sdInputHistory->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sdInputHistory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdInputHistory->id)]) ?>
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
