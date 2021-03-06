<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdWorkflow $sdWorkflow
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sdWorkflow->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sdWorkflow->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Sd Workflows'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sd Product Workflows'), ['controller' => 'SdProductWorkflows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Product Workflow'), ['controller' => 'SdProductWorkflows', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Workflow Activities'), ['controller' => 'SdWorkflowActivities', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Workflow Activity'), ['controller' => 'SdWorkflowActivities', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdWorkflows form large-9 medium-8 columns content">
    <?= $this->Form->create($sdWorkflow) ?>
    <fieldset>
        <legend><?= __('Edit Sd Workflow') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('description');
            echo $this->Form->control('status');
            echo $this->Form->control('country');
            echo $this->Form->control('workflow_type');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
