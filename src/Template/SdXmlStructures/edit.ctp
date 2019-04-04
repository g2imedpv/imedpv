<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdXmlStructure $sdXmlStructure
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sdXmlStructure->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sdXmlStructure->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Sd Xml Structures'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sd Fields'), ['controller' => 'SdFields', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Field'), ['controller' => 'SdFields', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdXmlStructures form large-9 medium-8 columns content">
    <?= $this->Form->create($sdXmlStructure) ?>
    <fieldset>
        <legend><?= __('Edit Sd Xml Structure') ?></legend>
        <?php
            echo $this->Form->control('tag');
            echo $this->Form->control('level');
            echo $this->Form->control('last_tag_id');
            echo $this->Form->control('sd_field_id', ['options' => $sdFields]);
            echo $this->Form->control('multiple');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
