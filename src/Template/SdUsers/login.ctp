<!-- This Login Page was applied layout which located in template/layout/login.ctp -->
<?php $this->assign('title', 'iMedPV'); ?>
<style>
  .flag {
      width: 30px;
      height: 20px;
  }
</style>
<?= $this->Form->create('login',['class'=>'card mx-auto w-50 cardcolor']) ?>
<img src="/img/logo-mds.png" class="rounded mx-auto d-block m-5" alt="G2-MDS">
    <div class="form-group text-white w-75 mx-auto">
        <?= $this->Form->control('email', ['type'=>'user', 'class'=>'form-control', 'placeholder'=>__('E-mail'), 'label'=>['id'=>'user', 'text'=>__('E-mail')]]) ?>
    </div>
    <div class="form-group text-white w-75 mx-auto">
        <?= $this->Form->control('password',['type'=>'password', 'class'=>'form-control', 'placeholder'=>__('Password'), 'label'=>['id'=>'user','text'=>__('Password')]]) ?>
    </div>
    <div class="form-group text-white w-75 mx-auto"  style="text-align:center">
        <label class="d-block" style="text-align:left"><?php echo __("Select Language");?></label>
        <div class="text-left">
            <a class="btn btn-sm btn-outline-primary mr-3" href="/sd-users/setLanguage/en_US" role="button"><?php echo __("English");?></a>
            <a class="btn btn-sm btn-outline-primary" href="/sd-users/setLanguage/zh_CN" role="button"><?php echo __("Chinese");?></a>
        </div>
    </div>
    <?= $this->Form->button(__("Login"),['type'=>'submit','class'=>'btn btn-primary w-50 mx-auto my-4']) ?>
<?= $this->Form->end() ?>
