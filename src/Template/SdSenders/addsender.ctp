<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdSender $sdSender
 */
?>

<title><?php echo __("Sender Information")?></title>

<div class="card text-center w-75 my-3 mx-auto">
  <div class="card-header">
    <h3><?php echo __("Sender Information")?></h3>
  </div>
  <div class="card-body">
    <?= $this->Form->create('addsender') ?>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label><?php echo __("Sender Company")?></label>
            <input type="text" class="form-control" value="<?= h($company) ?>" disabled>
            <input type="text" style='display:none' name='sender[sd_company_id]' value="<?= $this->request->getSession()->read('Auth.User.company_id') ?>" >
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.1 Sender Type")?></label>
            <select class="form-control" name='sender[type]' required >
                <option value="" ><?php echo __("Select Element")?></option>
                <?php foreach($senderType as $key => $senderTypeOption): ?>
                    <option value="<?php echo $senderTypeOption['0']?>" <?= $sdSender['type'] == $senderTypeOption['0'] ? 'selected="true"' : '' ?> ><?php echo __($senderTypeOption['1'])?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.2 Sender’s Organisation")?></label>
            <input type="text" class="form-control" name='sender[organisation]' value='<?= $sdSender == null ? '' : $sdSender->organisation ?>'>
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.3.1 Sender’s Department")?></label>
            <input type="text" class="form-control" name='sender[department]' value='<?= $sdSender == null ? '' : $sdSender->department ?>'>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.3.2 Sender’s Title")?></label>
            <input type="text" class="form-control" name='sender[title]' value='<?= $sdSender == null ? '' : $sdSender->title ?>'>
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.3.3 Sender’s Given Name")?></label>
            <input type="text" class="form-control" name='sender[given_name]' value='<?= $sdSender == null ? '' : $sdSender->given_name ?>'>
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.3.4 Sender’s Middle Name")?></label>
            <input type="text" class="form-control" name='sender[middle_name]' value='<?= $sdSender == null ? '' : $sdSender->middle_name ?>'>
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.3.5 Sender’s Family Name")?></label>
            <input type="text" class="form-control" name='sender[family_name]' value='<?= $sdSender == null ? '' : $sdSender->family_name ?>'>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.4.1 Sender’s Street Address")?></label>
            <input type="text" class="form-control" name='sender[street]' value='<?= $sdSender == null ? '' : $sdSender->street ?>'>
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.4.2 Sender’s City")?></label>
            <input type="text" class="form-control" name='sender[city]' value='<?= $sdSender == null ? '' : $sdSender->city ?>'>
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.4.3 Sender’s State or Province")?></label>
            <input type="text" class="form-control" name='sender[state]' value='<?= $sdSender == null ? '' : $sdSender->state ?>'>
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.4.4 Sender’s Postcode")?></label>
            <input type="text" class="form-control" name='sender[postcode]' value='<?= $sdSender == null ? '' : $sdSender->postcode ?>'>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.4.5 Sender’s Country Code")?></label>
            <select class="form-control" name='sender[country]' id="">
                <option value=""><?php echo __("Select Element")?></option>
                <?php foreach($country as $key => $countries): ?>
                    <option value="<?php echo $countries['0']?>" <?= $sdSender['country'] == $countries['0'] ? 'selected="true"' : '' ?> ><?php echo __($countries['1'])?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.4.6 Sender’s Telephone")?></label>
            <input type="text" class="form-control" name='sender[telephone]' value='<?= $sdSender == null ? '' : $sdSender->telephone ?>'>
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.4.7 Sender’s Fax")?></label>
            <input type="text" class="form-control" name='sender[fax]' value='<?= $sdSender == null ? '' : $sdSender->fax ?>'>
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("C.3.4.8 Sender’s E-mail Address")?></label>
            <input type="text" class="form-control" name='sender[email]' value='<?= $sdSender == null ? '' : $sdSender->email ?>'>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label><?php echo __("C.1.CN.3持有人标识")?></label>
            <input type="text" class="form-control" name='sender[cn_mark]' value='<?= $sdSender == null ? '' : $sdSender->cn_mark ?>'>
        </div>
    </div>

    <div class='d-flex justify-content-center'>
        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-info w-25 mx-2 text-center']) ?>
    </div>
    <?= $this->Form->end() ?>
  </div>

</div>
