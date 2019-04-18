<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdSectionSet $sdSectionSet
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sd Section Set'), ['action' => 'edit', $sdSectionSet->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sd Section Set'), ['action' => 'delete', $sdSectionSet->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdSectionSet->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sd Section Sets'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Section Set'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sd Sections'), ['controller' => 'SdSections', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Section'), ['controller' => 'SdSections', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sd Field Values'), ['controller' => 'SdFieldValues', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Field Value'), ['controller' => 'SdFieldValues', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sdSectionSets view large-9 medium-8 columns content">
    <h3><?= h($sdSectionSet->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Sd Section') ?></th>
            <td><?= $sdSectionSet->has('sd_section') ? $this->Html->link($sdSectionSet->sd_section->id, ['controller' => 'SdSections', 'action' => 'view', $sdSectionSet->sd_section->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sd Field Value') ?></th>
            <td><?= $sdSectionSet->has('sd_field_value') ? $this->Html->link($sdSectionSet->sd_field_value->id, ['controller' => 'SdFieldValues', 'action' => 'view', $sdSectionSet->sd_field_value->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Set Array') ?></th>
            <td><?= h($sdSectionSet->set_array) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sdSectionSet->id) ?></td>
        </tr>
    </table>
</div>
