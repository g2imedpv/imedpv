<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdXmlStructure $sdXmlStructure
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sd Xml Structure'), ['action' => 'edit', $sdXmlStructure->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sd Xml Structure'), ['action' => 'delete', $sdXmlStructure->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdXmlStructure->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sd Xml Structures'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Xml Structure'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sd Fields'), ['controller' => 'SdFields', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Field'), ['controller' => 'SdFields', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sdXmlStructures view large-9 medium-8 columns content">
    <h3><?= h($sdXmlStructure->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Tag') ?></th>
            <td><?= h($sdXmlStructure->tag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sd Field') ?></th>
            <td><?= $sdXmlStructure->has('sd_field') ? $this->Html->link($sdXmlStructure->sd_field->id, ['controller' => 'SdFields', 'action' => 'view', $sdXmlStructure->sd_field->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sdXmlStructure->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Level') ?></th>
            <td><?= $this->Number->format($sdXmlStructure->level) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Last Tag Id') ?></th>
            <td><?= $this->Number->format($sdXmlStructure->last_tag_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Multiple') ?></th>
            <td><?= $this->Number->format($sdXmlStructure->multiple) ?></td>
        </tr>
    </table>
</div>
