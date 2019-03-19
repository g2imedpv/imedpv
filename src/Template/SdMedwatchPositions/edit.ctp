<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdMedwatchPosition $sdMedwatchPosition
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sdMedwatchPosition->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sdMedwatchPosition->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Sd Medwatch Positions'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sd Fields'), ['controller' => 'SdFields', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Field'), ['controller' => 'SdFields', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdMedwatchPositions form large-9 medium-8 columns content">
    <?= $this->Form->create($sdMedwatchPosition) ?>
    <fieldset>
        <legend><?= __('Edit Sd Medwatch Position') ?></legend>
        <?php
            echo $this->Form->control('medwatch_no');
            echo $this->Form->control('field_name');
            echo $this->Form->control('position_top');
            echo $this->Form->control('position_left');
            echo $this->Form->control('position_width');
            echo $this->Form->control('position_height');
            echo $this->Form->control('sd_field_id', ['options' => $sdFields]);
            echo $this->Form->control('set_number');
            echo $this->Form->control('value_type');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
