<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdXmlStructure[]|\Cake\Collection\CollectionInterface $sdXmlStructures
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sd Xml Structure'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Fields'), ['controller' => 'SdFields', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Field'), ['controller' => 'SdFields', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdXmlStructures index large-9 medium-8 columns content">
    <h3><?= __('Sd Xml Structures') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('tag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('level') ?></th>
                <th scope="col"><?= $this->Paginator->sort('last_tag_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_field_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('multiple') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sdXmlStructures as $sdXmlStructure): ?>
            <tr>
                <td><?= $this->Number->format($sdXmlStructure->id) ?></td>
                <td><?= h($sdXmlStructure->tag) ?></td>
                <td><?= $this->Number->format($sdXmlStructure->level) ?></td>
                <td><?= $this->Number->format($sdXmlStructure->last_tag_id) ?></td>
                <td><?= $sdXmlStructure->has('sd_field') ? $this->Html->link($sdXmlStructure->sd_field->id, ['controller' => 'SdFields', 'action' => 'view', $sdXmlStructure->sd_field->id]) : '' ?></td>
                <td><?= $this->Number->format($sdXmlStructure->multiple) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sdXmlStructure->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sdXmlStructure->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sdXmlStructure->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdXmlStructure->id)]) ?>
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
