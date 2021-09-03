<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdSender $sdSender
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sd Sender'), ['action' => 'edit', $sdSender->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sd Sender'), ['action' => 'delete', $sdSender->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdSender->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sd Senders'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Sender'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sd Companies'), ['controller' => 'SdCompanies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Company'), ['controller' => 'SdCompanies', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sdSenders view large-9 medium-8 columns content">
    <h3><?= h($sdSender->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Sd Company') ?></th>
            <td><?= $sdSender->has('sd_company') ? $this->Html->link($sdSender->sd_company->id, ['controller' => 'SdCompanies', 'action' => 'view', $sdSender->sd_company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sdSender->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sender Type') ?></th>
            <td><?= $this->Number->format($sdSender->sender_type) ?></td>
        </tr>
    </table>
</div>
