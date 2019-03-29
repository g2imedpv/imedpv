<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdDocument $sdDocument
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sd Document'), ['action' => 'edit', $sdDocument->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sd Document'), ['action' => 'delete', $sdDocument->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdDocument->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sd Documents'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Document'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sd Cases'), ['controller' => 'SdCases', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Case'), ['controller' => 'SdCases', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sdDocuments view large-9 medium-8 columns content">
    <h3><?= h($sdDocument->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Sd Case') ?></th>
            <td><?= $sdDocument->has('sd_case') ? $this->Html->link($sdDocument->sd_case->id, ['controller' => 'SdCases', 'action' => 'view', $sdDocument->sd_case->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Doc Classification') ?></th>
            <td><?= h($sdDocument->doc_classification) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Doc Source') ?></th>
            <td><?= h($sdDocument->doc_source) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Doc Name') ?></th>
            <td><?= h($sdDocument->doc_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Doc Path') ?></th>
            <td><?= h($sdDocument->doc_path) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Doc Type') ?></th>
            <td><?= h($sdDocument->doc_type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sdDocument->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Doc Size') ?></th>
            <td><?= $this->Number->format($sdDocument->doc_size) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Deleted') ?></th>
            <td><?= $this->Number->format($sdDocument->is_deleted) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created By') ?></th>
            <td><?= $this->Number->format($sdDocument->created_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Dt') ?></th>
            <td><?= h($sdDocument->created_dt) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Dt') ?></th>
            <td><?= h($sdDocument->updated_dt) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Doc Description') ?></h4>
        <?= $this->Text->autoParagraph(h($sdDocument->doc_description)); ?>
    </div>
</div>
