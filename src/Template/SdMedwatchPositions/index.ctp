<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdMedwatchPosition[]|\Cake\Collection\CollectionInterface $sdMedwatchPositions
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sd Medwatch Position'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Fields'), ['controller' => 'SdFields', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Field'), ['controller' => 'SdFields', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdMedwatchPositions index large-9 medium-8 columns content">
    <h3><?= __('Sd Medwatch Positions') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('medwatch_no') ?></th>
                <th scope="col"><?= $this->Paginator->sort('field_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('position_top') ?></th>
                <th scope="col"><?= $this->Paginator->sort('position_left') ?></th>
                <th scope="col"><?= $this->Paginator->sort('position_width') ?></th>
                <th scope="col"><?= $this->Paginator->sort('position_height') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_field_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('set_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('value_type') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sdMedwatchPositions as $sdMedwatchPosition): ?>
            <tr>
                <td><?= $this->Number->format($sdMedwatchPosition->id) ?></td>
                <td><?= h($sdMedwatchPosition->medwatch_no) ?></td>
                <td><?= h($sdMedwatchPosition->field_name) ?></td>
                <td><?= $this->Number->format($sdMedwatchPosition->position_top) ?></td>
                <td><?= $this->Number->format($sdMedwatchPosition->position_left) ?></td>
                <td><?= $this->Number->format($sdMedwatchPosition->position_width) ?></td>
                <td><?= $this->Number->format($sdMedwatchPosition->position_height) ?></td>
                <td><?= $sdMedwatchPosition->has('sd_field') ? $this->Html->link($sdMedwatchPosition->sd_field->id, ['controller' => 'SdFields', 'action' => 'view', $sdMedwatchPosition->sd_field->id]) : '' ?></td>
                <td><?= $this->Number->format($sdMedwatchPosition->set_number) ?></td>
                <td><?= h($sdMedwatchPosition->value_type) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sdMedwatchPosition->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sdMedwatchPosition->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sdMedwatchPosition->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdMedwatchPosition->id)]) ?>
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
