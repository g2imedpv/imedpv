<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdCompany[]|\Cake\Collection\CollectionInterface $sdCompanies
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Add Company'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd User Types'), ['controller' => 'SdUserTypes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd User Type'), ['controller' => 'SdUserTypes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Users'), ['controller' => 'SdUsers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd User'), ['controller' => 'SdUsers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdCompanies index large-9 medium-8 columns content" style="table-layout:fixed; width:220%;">
    <h3><?= __('Company List') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_user_type_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('company_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('company_email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('website') ?></th>
                <th scope="col"><?= $this->Paginator->sort('address_line1') ?></th>
                <th scope="col"><?= $this->Paginator->sort('address_line2') ?></th>
                <th scope="col"><?= $this->Paginator->sort('zipcode') ?></th>
                <th scope="col"><?= $this->Paginator->sort('city') ?></th>
                <th scope="col"><?= $this->Paginator->sort('state') ?></th>
                <th scope="col"><?= $this->Paginator->sort('country') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cell_country_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cell_phone_no') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone1_country_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone1') ?></th>
                <th scope="col"><?= $this->Paginator->sort('extention1') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone2_country_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone2') ?></th>
                <th scope="col"><?= $this->Paginator->sort('extention2') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fax1_country_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fax1') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fax2_country_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fax2') ?></th>
                <th scope="col"><?= $this->Paginator->sort('transaction_currency') ?></th>
                <th scope="col"><?= $this->Paginator->sort('no_of_billing_cycle') ?></th>
                <th scope="col"><?= $this->Paginator->sort('current_billing_cycle') ?></th>
                <th scope="col"><?= $this->Paginator->sort('no_of_whodra') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('un_paid') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_medra') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_whodra') ?></th>
                <th scope="col"><?= $this->Paginator->sort('create_by') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_dt') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modify_by') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified_dt') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sdCompanies as $sdCompany): ?>
            <tr>
                <td><?= $this->Number->format($sdCompany->id) ?></td>
                <td><?= $sdCompany->has('sd_user_type') ? $this->Html->link($sdCompany->sd_user_type->name, ['controller' => 'SdUserTypes', 'action' => 'view', $sdCompany->sd_user_type->id]) : '' ?></td>
                <td><?= h($sdCompany->company_name) ?></td>
                <td><?= h($sdCompany->company_email) ?></td>
                <td><?= h($sdCompany->website) ?></td>
                <td><?= h($sdCompany->address_line1) ?></td>
                <td><?= h($sdCompany->address_line2) ?></td>
                <td><?= h($sdCompany->zipcode) ?></td>
                <td><?= h($sdCompany->city) ?></td>
                <td><?= h($sdCompany->state) ?></td>
                <td><?= h($sdCompany->country) ?></td>
                <td><?= h($sdCompany->cell_country_code) ?></td>
                <td><?= h($sdCompany->cell_phone_no) ?></td>
                <td><?= h($sdCompany->phone1_country_code) ?></td>
                <td><?= h($sdCompany->phone1) ?></td>
                <td><?= h($sdCompany->extention1) ?></td>
                <td><?= h($sdCompany->phone2_country_code) ?></td>
                <td><?= h($sdCompany->phone2) ?></td>
                <td><?= h($sdCompany->extention2) ?></td>
                <td><?= h($sdCompany->fax1_country_code) ?></td>
                <td><?= h($sdCompany->fax1) ?></td>
                <td><?= h($sdCompany->fax2_country_code) ?></td>
                <td><?= h($sdCompany->fax2) ?></td>
                <td><?= $this->Number->format($sdCompany->transaction_currency) ?></td>
                <td><?= $this->Number->format($sdCompany->no_of_billing_cycle) ?></td>
                <td><?= $this->Number->format($sdCompany->current_billing_cycle) ?></td>
                <td><?= $this->Number->format($sdCompany->no_of_whodra) ?></td>
                <td><?= $this->Number->format($sdCompany->status) ?></td>
                <td><?= $this->Number->format($sdCompany->un_paid) ?></td>
                <td><?= $this->Number->format($sdCompany->is_medra) ?></td>
                <td><?= $this->Number->format($sdCompany->is_whodra) ?></td>
                <td><?= $this->Number->format($sdCompany->create_by) ?></td>
                <td><?= h($sdCompany->created_dt) ?></td>
                <td><?= $this->Number->format($sdCompany->modify_by) ?></td>
                <td><?= h($sdCompany->modified_dt) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sdCompany->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sdCompany->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sdCompany->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdCompany->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
