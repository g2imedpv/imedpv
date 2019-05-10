<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdContact[]|\Cake\Collection\CollectionInterface $sdContacts
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Sd Contact'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdContacts index large-9 medium-8 columns content">
    <h3><?= __('Sd Contacts') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('contact_type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('authority') ?></th>
                <th scope="col"><?= $this->Paginator->sort('data_privacy') ?></th>
                <th scope="col"><?= $this->Paginator->sort('blinded_report') ?></th>
                <th scope="col"><?= $this->Paginator->sort('contactId') ?></th>
                <th scope="col"><?= $this->Paginator->sort('preferred_route') ?></th>
                <th scope="col"><?= $this->Paginator->sort('format_type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('given_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('family_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('middle_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('address') ?></th>
                <th scope="col"><?= $this->Paginator->sort('address_extension') ?></th>
                <th scope="col"><?= $this->Paginator->sort('city') ?></th>
                <th scope="col"><?= $this->Paginator->sort('state_province') ?></th>
                <th scope="col"><?= $this->Paginator->sort('zipcode') ?></th>
                <th scope="col"><?= $this->Paginator->sort('country') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone_extension') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fax') ?></th>
                <th scope="col"><?= $this->Paginator->sort('fax_extension') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email_address') ?></th>
                <th scope="col"><?= $this->Paginator->sort('website') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sdContacts as $sdContact): ?>
            <tr>
                <td><?= $this->Number->format($sdContact->id) ?></td>
                <td><?= $this->Number->format($sdContact->contact_type) ?></td>
                <td><?= $this->Number->format($sdContact->authority) ?></td>
                <td><?= h($sdContact->data_privacy) ?></td>
                <td><?= $this->Number->format($sdContact->blinded_report) ?></td>
                <td><?= h($sdContact->contactId) ?></td>
                <td><?= $this->Number->format($sdContact->preferred_route) ?></td>
                <td><?= $this->Number->format($sdContact->format_type) ?></td>
                <td><?= h($sdContact->title) ?></td>
                <td><?= h($sdContact->given_name) ?></td>
                <td><?= h($sdContact->family_name) ?></td>
                <td><?= h($sdContact->middle_name) ?></td>
                <td><?= h($sdContact->address) ?></td>
                <td><?= h($sdContact->address_extension) ?></td>
                <td><?= h($sdContact->city) ?></td>
                <td><?= h($sdContact->state_province) ?></td>
                <td><?= h($sdContact->zipcode) ?></td>
                <td><?= $this->Number->format($sdContact->country) ?></td>
                <td><?= h($sdContact->phone) ?></td>
                <td><?= h($sdContact->phone_extension) ?></td>
                <td><?= h($sdContact->fax) ?></td>
                <td><?= h($sdContact->fax_extension) ?></td>
                <td><?= h($sdContact->email_address) ?></td>
                <td><?= h($sdContact->website) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sdContact->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sdContact->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sdContact->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdContact->id)]) ?>
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
