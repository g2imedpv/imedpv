<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdContact $sdContact
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sd Contact'), ['action' => 'edit', $sdContact->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sd Contact'), ['action' => 'delete', $sdContact->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sdContact->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sd Contacts'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sd Contact'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sdContacts view large-9 medium-8 columns content">
    <h3><?= h($sdContact->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Contact Type') ?></th>
            <td><?= h($sdContact->contact_type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Authority') ?></th>
            <td><?= h($sdContact->authority) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Blinded Report') ?></th>
            <td><?= h($sdContact->blinded_report) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('ContactId') ?></th>
            <td><?= h($sdContact->contactId) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Preferred Route') ?></th>
            <td><?= h($sdContact->preferred_route) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Format Type') ?></th>
            <td><?= h($sdContact->format_type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($sdContact->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Given Name') ?></th>
            <td><?= h($sdContact->given_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Family Name') ?></th>
            <td><?= h($sdContact->family_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Middle Name') ?></th>
            <td><?= h($sdContact->middle_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address') ?></th>
            <td><?= h($sdContact->address) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address Extension') ?></th>
            <td><?= h($sdContact->address_extension) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('City') ?></th>
            <td><?= h($sdContact->city) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('State Province') ?></th>
            <td><?= h($sdContact->state_province) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Zipcode') ?></th>
            <td><?= h($sdContact->zipcode) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Country') ?></th>
            <td><?= h($sdContact->country) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phone') ?></th>
            <td><?= h($sdContact->phone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phone Extension') ?></th>
            <td><?= h($sdContact->phone_extension) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fax') ?></th>
            <td><?= h($sdContact->fax) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fax Extension') ?></th>
            <td><?= h($sdContact->fax_extension) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email Address') ?></th>
            <td><?= h($sdContact->email_address) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Website') ?></th>
            <td><?= h($sdContact->website) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sdContact->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Data Privacy') ?></th>
            <td><?= $sdContact->data_privacy ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
