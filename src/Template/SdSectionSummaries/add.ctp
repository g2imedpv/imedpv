<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdSectionSummary $sdSectionSummary
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Sd Section Summaries'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sd Sections'), ['controller' => 'SdSections', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Section'), ['controller' => 'SdSections', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdSectionSummaries form large-9 medium-8 columns content">
    <?= $this->Form->create($sdSectionSummary) ?>
    <fieldset>
        <legend><?= __('Add Sd Section Summary') ?></legend>
        <?php
            echo $this->Form->control('sd_section_id', ['options' => $sdSections]);
            echo $this->Form->control('fields');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
