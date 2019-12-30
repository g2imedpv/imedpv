<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdUser[]|\Cake\Collection\CollectionInterface $sdUsers
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <!-- <li class="heading"><?= __('Actions') ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd User'), ['action' => 'add']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('List Sd Roles'), ['controller' => 'SdRoles', 'action' => 'index']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd Role'), ['controller' => 'SdRoles', 'action' => 'add']) ?></li> -->
        <li><?= $this->Html->link(__('Company List'), ['controller' => 'SdCompanies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Add Company'), ['controller' => 'SdCompanies', 'action' => 'add']) ?></li>
        <!-- <li><?= $this->Html->link(__('List Sd Activity Logs'), ['controller' => 'SdActivityLogs', 'action' => 'index']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd Activity Log'), ['controller' => 'SdActivityLogs', 'action' => 'add']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('List Sd Cases'), ['controller' => 'SdCases', 'action' => 'index']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd Case'), ['controller' => 'SdCases', 'action' => 'add']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('List Sd Product Workflows'), ['controller' => 'SdProductWorkflows', 'action' => 'index']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd Product Workflow'), ['controller' => 'SdProductWorkflows', 'action' => 'add']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('List Sd User Assignments'), ['controller' => 'SdUserAssignments', 'action' => 'index']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd User Assignment'), ['controller' => 'SdUserAssignments', 'action' => 'add']) ?></li> -->
    </ul>
</nav>
<div class="sdUsers index large-9 medium-8 columns content" style="table-layout:fixed; width:230%;">
    <h3><?= __('User List') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_role_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sd_company_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('firstname') ?></th>
                <th scope="col"><?= $this->Paginator->sort('lastname') ?></th>
                <th scope="col"><?= $this->Paginator->sort('username') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('password') ?></th>
                <th scope="col"><?= $this->Paginator->sort('thumbnail') ?></th>
                <th scope="col"><?= $this->Paginator->sort('site_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('site_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone_country_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('extention') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cell_country_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cell_phone_no') ?></th>
                <th scope="col"><?= $this->Paginator->sort('verification') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone_alert') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email_alert') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_never') ?></th>
                <th scope="col"><?= $this->Paginator->sort('account_expire_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_email_verified') ?></th>
                <th scope="col"><?= $this->Paginator->sort('reset_password_expire_time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_import_user') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_medra') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_whodra') ?></th>
                <th scope="col"><?= $this->Paginator->sort('job_title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('assign_protocol') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('default_language') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_imedsae_tracking') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_imed_safety_database') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_by') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_dt') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified_by') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified_dt') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sdUsers as $sdUser): ?>
            <tr>
                <td><?= $this->Number->format($sdUser->id) ?></td>
                <td><?= $sdUser->has('sd_role') ? $this->Html->link($sdUser->sd_role->id, ['controller' => 'SdRoles', 'action' => 'view', $sdUser->sd_role->id]) : '' ?></td>
                <td><?= $sdUser->has('sd_company') ? $this->Html->link($sdUser->sd_company->id, ['controller' => 'SdCompanies', 'action' => 'view', $sdUser->sd_company->id]) : '' ?></td>
                <td><?= h($sdUser->firstname) ?></td>
                <td><?= h($sdUser->lastname) ?></td>
                <td><?= h($sdUser->username) ?></td>
                <td><?= h($sdUser->email) ?></td>
                <td><?= h($sdUser->password) ?></td>
                <td><?= h($sdUser->thumbnail) ?></td>
                <td><?= h($sdUser->site_number) ?></td>
                <td><?= h($sdUser->site_name) ?></td>
                <td><?= h($sdUser->title) ?></td>
                <td><?= h($sdUser->phone_country_code) ?></td>
                <td><?= h($sdUser->phone) ?></td>
                <td><?= h($sdUser->extention) ?></td>
                <td><?= h($sdUser->cell_country_code) ?></td>
                <td><?= h($sdUser->cell_phone_no) ?></td>
                <td><?= h($sdUser->verification) ?></td>
                <td><?= $this->Number->format($sdUser->phone_alert) ?></td>
                <td><?= $this->Number->format($sdUser->email_alert) ?></td>
                <td><?= $this->Number->format($sdUser->is_never) ?></td>
                <td><?= h($sdUser->account_expire_date) ?></td>
                <td><?= h($sdUser->is_email_verified) ?></td>
                <td><?= h($sdUser->reset_password_expire_time) ?></td>
                <td><?= h($sdUser->is_import_user) ?></td>
                <td><?= $this->Number->format($sdUser->is_medra) ?></td>
                <td><?= $this->Number->format($sdUser->is_whodra) ?></td>
                <td><?= h($sdUser->job_title) ?></td>
                <td><?= $this->Number->format($sdUser->assign_protocol) ?></td>
                <td><?= $this->Number->format($sdUser->status) ?></td>
                <td><?= h($sdUser->default_language) ?></td>
                <td><?= $this->Number->format($sdUser->is_imedsae_tracking) ?></td>
                <td><?= $this->Number->format($sdUser->is_imed_safety_database) ?></td>
                <td><?= $this->Number->format($sdUser->created_by) ?></td>
                <td><?= h($sdUser->created_dt) ?></td>
                <td><?= $this->Number->format($sdUser->modified_by) ?></td>
                <td><?= h($sdUser->modified_dt) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sdUser->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sdUser->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sdUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdUser->id)]) ?>
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
