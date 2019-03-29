<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdDocument[]|\Cake\Collection\CollectionInterface $sdDocuments
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sd Document'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Cases'), ['controller' => 'SdCases', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Case'), ['controller' => 'SdCases', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdDocuments index large-9 medium-8 columns content">
    <h3><?= __('Sd Documents') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_case_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('doc_classification') ?></th>
                <th scope="col"><?= $this->Paginator->sort('doc_source') ?></th>
                <th scope="col"><?= $this->Paginator->sort('doc_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('doc_path') ?></th>
                <th scope="col"><?= $this->Paginator->sort('doc_type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('doc_size') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_deleted') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_dt') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_dt') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_by') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sdDocuments as $sdDocument): ?>
            <tr>
                <td><?= $this->Number->format($sdDocument->id) ?></td>
                <td><?= $sdDocument->has('sd_case') ? $this->Html->link($sdDocument->sd_case->id, ['controller' => 'SdCases', 'action' => 'view', $sdDocument->sd_case->id]) : '' ?></td>
                <td><?= h($sdDocument->doc_classification) ?></td>
                <td><?= h($sdDocument->doc_source) ?></td>
                <td><?= h($sdDocument->doc_name) ?></td>
                <td><?= h($sdDocument->doc_path) ?></td>
                <td><?= h($sdDocument->doc_type) ?></td>
                <td><?= $this->Number->format($sdDocument->doc_size) ?></td>
                <td><?= $this->Number->format($sdDocument->is_deleted) ?></td>
                <td><?= h($sdDocument->created_dt) ?></td>
                <td><?= h($sdDocument->updated_dt) ?></td>
                <td><?= $this->Number->format($sdDocument->created_by) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sdDocument->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sdDocument->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sdDocument->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdDocument->id)]) ?>
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
