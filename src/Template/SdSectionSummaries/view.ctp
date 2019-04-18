<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdSectionSummary $sdSectionSummary
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sd Section Summary'), ['action' => 'edit', $sdSectionSummary->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sd Section Summary'), ['action' => 'delete', $sdSectionSummary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdSectionSummary->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sd Section Summaries'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Section Summary'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sd Sections'), ['controller' => 'SdSections', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Section'), ['controller' => 'SdSections', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sdSectionSummaries view large-9 medium-8 columns content">
    <h3><?= h($sdSectionSummary->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Sd Section') ?></th>
            <td><?= $sdSectionSummary->has('sd_section') ? $this->Html->link($sdSectionSummary->sd_section->id, ['controller' => 'SdSections', 'action' => 'view', $sdSectionSummary->sd_section->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fields') ?></th>
            <td><?= h($sdSectionSummary->fields) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sdSectionSummary->id) ?></td>
        </tr>
    </table>
</div>
