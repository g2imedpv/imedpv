<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdInputHistory $sdInputHistory
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sdInputHistory->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sdInputHistory->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Sd Input Histories'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sd Field Values'), ['controller' => 'SdFieldValues', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Field Value'), ['controller' => 'SdFieldValues', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Users'), ['controller' => 'SdUsers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd User'), ['controller' => 'SdUsers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdInputHistories form large-9 medium-8 columns content">
    <?= $this->Form->create($sdInputHistory) ?>
    <fieldset>
        <legend><?= __('Edit Sd Input History') ?></legend>
        <?php
            echo $this->Form->control('sd_field_value_id', ['options' => $sdFieldValues]);
            echo $this->Form->control('input');
            echo $this->Form->control('sd_user_id', ['options' => $sdUsers]);
            echo $this->Form->control('time_changed');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
