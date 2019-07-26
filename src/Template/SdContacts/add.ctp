<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdContact $sdContact
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Sd Contacts'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="sdContacts form large-9 medium-8 columns content">
    <?= $this->Form->create($sdContact) ?>
    <fieldset>
        <legend><?= __('Add Sd Contact') ?></legend>
        <?php
            echo $this->Form->control('contact_type');
            echo $this->Form->control('authority');
            echo $this->Form->control('data_privacy');
            echo $this->Form->control('blinded_report');
            echo $this->Form->control('contactId');
            echo $this->Form->control('preferred_route');
            echo $this->Form->control('format_type');
            echo $this->Form->control('title');
            echo $this->Form->control('given_name');
            echo $this->Form->control('family_name');
            echo $this->Form->control('middle_name');
            echo $this->Form->control('address');
            echo $this->Form->control('address_extension');
            echo $this->Form->control('city');
            echo $this->Form->control('state_province');
            echo $this->Form->control('zipcode');
            echo $this->Form->control('country');
            echo $this->Form->control('phone');
            echo $this->Form->control('phone_extension');
            echo $this->Form->control('fax');
            echo $this->Form->control('fax_extension');
            echo $this->Form->control('email_address');
            echo $this->Form->control('website');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
