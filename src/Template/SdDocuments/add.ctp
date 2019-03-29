<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdDocument $sdDocument
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Sd Documents'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sd Cases'), ['controller' => 'SdCases', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Case'), ['controller' => 'SdCases', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdDocuments form large-9 medium-8 columns content">
    <?= $this->Form->create($sdDocument) ?>
    <fieldset>
        <legend><?= __('Add Sd Document') ?></legend>
        <?php
            echo $this->Form->control('sd_case_id', ['options' => $sdCases]);
            echo $this->Form->control('doc_classification');
            echo $this->Form->control('doc_description');
            echo $this->Form->control('doc_source');
            echo $this->Form->control('doc_name');
            echo $this->Form->control('doc_path');
            echo $this->Form->control('doc_type');
            echo $this->Form->control('doc_size');
            echo $this->Form->control('is_deleted');
            echo $this->Form->control('created_dt', ['empty' => true]);
            echo $this->Form->control('updated_dt', ['empty' => true]);
            echo $this->Form->control('created_by');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
