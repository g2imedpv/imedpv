<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdSender $sdSender
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sdSender->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sdSender->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Sd Senders'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sd Companies'), ['controller' => 'SdCompanies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Company'), ['controller' => 'SdCompanies', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdSenders form large-9 medium-8 columns content">
    <?= $this->Form->create($sdSender) ?>
    <fieldset>
        <legend><?= __('Edit Sd Sender') ?></legend>
        <?php
            echo $this->Form->control('sd_company_id', ['options' => $sdCompanies]);
            echo $this->Form->control('sender_type');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
