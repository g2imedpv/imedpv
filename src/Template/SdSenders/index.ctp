<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdSender[]|\Cake\Collection\CollectionInterface $sdSenders
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sd Sender'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Companies'), ['controller' => 'SdCompanies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Company'), ['controller' => 'SdCompanies', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdSenders index large-9 medium-8 columns content">
    <h3><?= __('Sd Senders') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_company_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sender_type') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sdSenders as $sdSender): ?>
            <tr>
                <td><?= $this->Number->format($sdSender->id) ?></td>
                <td><?= $sdSender->has('sd_company') ? $this->Html->link($sdSender->sd_company->id, ['controller' => 'SdCompanies', 'action' => 'view', $sdSender->sd_company->id]) : '' ?></td>
                <td><?= $this->Number->format($sdSender->sender_type) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sdSender->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sdSender->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sdSender->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdSender->id)]) ?>
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
