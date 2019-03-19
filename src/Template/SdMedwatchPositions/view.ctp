<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdMedwatchPosition $sdMedwatchPosition
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sd Medwatch Position'), ['action' => 'edit', $sdMedwatchPosition->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sd Medwatch Position'), ['action' => 'delete', $sdMedwatchPosition->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdMedwatchPosition->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sd Medwatch Positions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Medwatch Position'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sd Fields'), ['controller' => 'SdFields', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Field'), ['controller' => 'SdFields', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sdMedwatchPositions view large-9 medium-8 columns content">
    <h3><?= h($sdMedwatchPosition->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Medwatch No') ?></th>
            <td><?= h($sdMedwatchPosition->medwatch_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Field Name') ?></th>
            <td><?= h($sdMedwatchPosition->field_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sd Field') ?></th>
            <td><?= $sdMedwatchPosition->has('sd_field') ? $this->Html->link($sdMedwatchPosition->sd_field->id, ['controller' => 'SdFields', 'action' => 'view', $sdMedwatchPosition->sd_field->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Value Type') ?></th>
            <td><?= h($sdMedwatchPosition->value_type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sdMedwatchPosition->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Position Top') ?></th>
            <td><?= $this->Number->format($sdMedwatchPosition->position_top) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Position Left') ?></th>
            <td><?= $this->Number->format($sdMedwatchPosition->position_left) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Position Width') ?></th>
            <td><?= $this->Number->format($sdMedwatchPosition->position_width) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Position Height') ?></th>
            <td><?= $this->Number->format($sdMedwatchPosition->position_height) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Set Number') ?></th>
            <td><?= $this->Number->format($sdMedwatchPosition->set_number) ?></td>
        </tr>
    </table>
</div>
