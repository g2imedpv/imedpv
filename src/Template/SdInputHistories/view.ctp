<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdInputHistory $sdInputHistory
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sd Input History'), ['action' => 'edit', $sdInputHistory->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sd Input History'), ['action' => 'delete', $sdInputHistory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdInputHistory->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sd Input Histories'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Input History'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sd Field Values'), ['controller' => 'SdFieldValues', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Field Value'), ['controller' => 'SdFieldValues', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sd Users'), ['controller' => 'SdUsers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd User'), ['controller' => 'SdUsers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sdInputHistories view large-9 medium-8 columns content">
    <h3><?= h($sdInputHistory->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Sd Field Value') ?></th>
            <td><?= $sdInputHistory->has('sd_field_value') ? $this->Html->link($sdInputHistory->sd_field_value->id, ['controller' => 'SdFieldValues', 'action' => 'view', $sdInputHistory->sd_field_value->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sd User') ?></th>
            <td><?= $sdInputHistory->has('sd_user') ? $this->Html->link($sdInputHistory->sd_user->title, ['controller' => 'SdUsers', 'action' => 'view', $sdInputHistory->sd_user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sdInputHistory->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Time Changed') ?></th>
            <td><?= h($sdInputHistory->time_changed) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Input') ?></h4>
        <?= $this->Text->autoParagraph(h($sdInputHistory->input)); ?>
    </div>
</div>
