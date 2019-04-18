<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdSectionSummary[]|\Cake\Collection\CollectionInterface $sdSectionSummaries
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sd Section Summary'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Sections'), ['controller' => 'SdSections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Section'), ['controller' => 'SdSections', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdSectionSummaries index large-9 medium-8 columns content">
    <h3><?= __('Sd Section Summaries') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_section_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fields') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sdSectionSummaries as $sdSectionSummary): ?>
            <tr>
                <td><?= $this->Number->format($sdSectionSummary->id) ?></td>
                <td><?= $sdSectionSummary->has('sd_section') ? $this->Html->link($sdSectionSummary->sd_section->id, ['controller' => 'SdSections', 'action' => 'view', $sdSectionSummary->sd_section->id]) : '' ?></td>
                <td><?= h($sdSectionSummary->fields) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sdSectionSummary->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sdSectionSummary->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sdSectionSummary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdSectionSummary->id)]) ?>
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
