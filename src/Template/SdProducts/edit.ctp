<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdProduct $sdProduct
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sdProduct->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sdProduct->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Sd Products'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sd Companies'), ['controller' => 'SdCompanies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Company'), ['controller' => 'SdCompanies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Product Workflows'), ['controller' => 'SdProductWorkflows', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd Product Workflow'), ['controller' => 'SdProductWorkflows', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdProducts form large-9 medium-8 columns content">
    <?= $this->Form->create($sdProduct) ?>
    <fieldset>
        <legend><?= __('Edit Sd Product') ?></legend>
        <?php
            echo $this->Form->control('product_name');
            echo $this->Form->control('study_no');
            echo $this->Form->control('study_name');
            echo $this->Form->control('study_type');
            echo $this->Form->control('WHODD_decode');
            echo $this->Form->control('sd_company_id', ['options' => $sdCompanies]);
            echo $this->Form->control('short_desc');
            echo $this->Form->control('product_desc');
            echo $this->Form->control('blinding_tech');
            echo $this->Form->control('sd_product_flag');
            echo $this->Form->control('WHODD_code');
            echo $this->Form->control('WHODD_name');
            echo $this->Form->control('mfr_name');
            echo $this->Form->control('start_date');
            echo $this->Form->control('end_date');
            echo $this->Form->control('status');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
