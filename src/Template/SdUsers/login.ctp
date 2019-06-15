<!-- This Login Page was applied layout which located in template/layout/login.ctp -->
<?php $this->assign('title', 'iMedPV'); ?>
<?= $this->Form->create('login',['class'=>'card mx-auto w-50 cardcolor']) ?>
      <div class="my-auto border border-white p-2 rounded">
        <a href="/sd-users/setLanguage/en_US" class="mx-2"><?php echo __("English");?>
          <!-- <img class="flag" src="/img/flags/4x3/us.svg" href="/sd-users/setLanguage/en_US" alt="English Version" title="English Version"> -->
        </a>
        <a href="/sd-users/setLanguage/zh_CN" class="mx-2"><?php echo __("Event");?>
          <!-- <img class="flag" src="/img/flags/4x3/cn.svg" href="/sd-users/setLanguage/zh_CN" alt="Chinese Version" title="Chinese Version"> -->
        </a>
      </div>
<img src="/img/logo-mds.png" class="rounded mx-auto d-block m-5" alt="G2-MDS">
    <div class="form-group text-white w-75 mx-auto">
        <?= $this->Form->control('email', ['type'=>'user', 'class'=>'form-control', 'placeholder'=>'E-mail Address', 'label'=>['id'=>'user', 'text'=>__('E-mail')]]) ?>
    </div>
    <div class="form-group text-white w-75 mx-auto">
        <?= $this->Form->control('password',['type'=>'password', 'class'=>'form-control', 'placeholder'=>'Password', 'label'=>['id'=>'user','text'=>__('Password')]]) ?>
    </div>
    <?= $this->Form->button('Login',['type'=>'submit','class'=>'btn btn-primary w-50 mx-auto my-4']) ?>
<?= $this->Form->end() ?>
