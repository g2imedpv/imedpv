<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdSectionSet[]|\Cake\Collection\CollectionInterface $sdSectionSets
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sd Section Set'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Sections'), ['controller' => 'SdSections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Section'), ['controller' => 'SdSections', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Field Values'), ['controller' => 'SdFieldValues', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Field Value'), ['controller' => 'SdFieldValues', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdSectionSets index large-9 medium-8 columns content">
    <h3><?= __('Sd Section Sets') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_section_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_field_value_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('set_array') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sdSectionSets as $sdSectionSet): ?>
            <tr>
                <td><?= $this->Number->format($sdSectionSet->id) ?></td>
                <td><?= $sdSectionSet->has('sd_section') ? $this->Html->link($sdSectionSet->sd_section->id, ['controller' => 'SdSections', 'action' => 'view', $sdSectionSet->sd_section->id]) : '' ?></td>
                <td><?= $sdSectionSet->has('sd_field_value') ? $this->Html->link($sdSectionSet->sd_field_value->id, ['controller' => 'SdFieldValues', 'action' => 'view', $sdSectionSet->sd_field_value->id]) : '' ?></td>
                <td><?= h($sdSectionSet->set_array) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sdSectionSet->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sdSectionSet->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sdSectionSet->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdSectionSet->id)]) ?>
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
