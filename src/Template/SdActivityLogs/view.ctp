<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdActivityLog $sdActivityLog
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sd Activity Log'), ['action' => 'edit', $sdActivityLog->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sd Activity Log'), ['action' => 'delete', $sdActivityLog->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdActivityLog->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sd Activity Logs'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Activity Log'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sd Users'), ['controller' => 'SdUsers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd User'), ['controller' => 'SdUsers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sdActivityLogs view large-9 medium-8 columns content">
    <h3><?= h($sdActivityLog->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Sd User') ?></th>
            <td><?= $sdActivityLog->has('sd_user') ? $this->Html->link($sdActivityLog->sd_user->title, ['controller' => 'SdUsers', 'action' => 'view', $sdActivityLog->sd_user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Controller') ?></th>
            <td><?= h($sdActivityLog->controller) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Action') ?></th>
            <td><?= h($sdActivityLog->action) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sdActivityLog->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sd Section Value Id') ?></th>
            <td><?= $this->Number->format($sdActivityLog->sd_section_value_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sd Actvity Id') ?></th>
            <td><?= $this->Number->format($sdActivityLog->sd_actvity_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Time') ?></th>
            <td><?= h($sdActivityLog->updated_time) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Data Changed') ?></h4>
        <?= $this->Text->autoParagraph(h($sdActivityLog->data_changed)); ?>
    </div>
</div>
