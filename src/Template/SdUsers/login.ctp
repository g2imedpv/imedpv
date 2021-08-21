<!-- This Login Page was applied layout which located in template/layout/login.ctp -->
<?php $this->assign('title', 'iMedPV'); ?>
<?= $this->Html->css('login.css') ?>
<?= $this->Html->script('animation/loadingAnimation.js') ?>
<?= $this->Form->create('login',
    [
        'class'=>'card mx-auto w-50 cardcolor'
    ]
    ) ?>
<img src="/img/logo-mds.png" class="rounded d-block mx-auto my-3 topLogo" alt="G2-MDS">
    <div class="form-group text-white w-75 mx-auto">
        <?= $this->Form->control('email',
        [
            'type'=>'user',
            'class'=>'form-control',
            'required'=>true,
            'placeholder'=>__('E-mail'),
            'label'=>['id'=>'user', 'text'=>__('E-mail')]
        ]) ?>
    </div>
    <div class="form-group text-white w-75 mx-auto">
        <?= $this->Form->control('password',
        [
            'type'=>'password',
            'class'=>'form-control',
            'required'=>true,
            'placeholder'=>__('Password'),
            'label'=>['id'=>'user','text'=>__('Password')]
        ]) ?>
    </div>
    <div class="form-group text-white w-75 mx-auto"  style="text-align:center">
        <label class="d-block" style="text-align:left"><?php echo __("Select Language");?></label>
        <div class="text-left">
            <a class="btn text-white mr-3 flag us_flag" href="/sd-users/setLanguage/en_US" role="button"><?php echo __("English");?></a>
            <a class="btn text-white flag cn_flag" href="/sd-users/setLanguage/zh_CN" role="button"><?php echo __("Chinese");?></a>
        </div>
    </div>
    <?= $this->Form->button(__("Login"),['type'=>'submit','class'=>'btn btn-primary w-50 mx-auto my-4 login']) ?>
<?= $this->Form->end() ?>
