<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdSectionSet $sdSectionSet
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Sd Section Sets'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sd Sections'), ['controller' => 'SdSections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Section'), ['controller' => 'SdSections', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Field Values'), ['controller' => 'SdFieldValues', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Field Value'), ['controller' => 'SdFieldValues', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdSectionSets form large-9 medium-8 columns content">
    <?= $this->Form->create($sdSectionSet) ?>
    <fieldset>
        <legend><?= __('Add Sd Section Set') ?></legend>
        <?php
            echo $this->Form->control('sd_section_id', ['options' => $sdSections]);
            echo $this->Form->control('sd_field_value_id', ['options' => $sdFieldValues]);
            echo $this->Form->control('set_array');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
