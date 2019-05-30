<!-- This Login Page was applied layout which located in template/layout/login.ctp -->
<?php $this->assign('title', 'iMedPV'); ?>
<?= $this->Form->create('login',['class'=>'card mx-auto w-50 cardcolor']) ?>
<img src="/img/logo-mds.png" class="rounded mx-auto d-block m-5" alt="G2-MDS">
    <div class="form-group text-white w-75 mx-auto">
        <?= $this->Form->control('email', ['type'=>'user', 'class'=>'form-control', 'placeholder'=>'E-mail Address', 'label'=>['id'=>'user', 'text'=>'E-mail']]) ?>
    </div>
    <div class="form-group text-white w-75 mx-auto">
        <?= $this->Form->control('password',['type'=>'password', 'class'=>'form-control', 'placeholder'=>'Password', 'label'=>['id'=>'user','text'=>'Password']]) ?>
    </div>
    <?= $this->Form->button('Login',['type'=>'submit','class'=>'btn btn-primary w-50 mx-auto my-4']) ?>
<?= $this->Form->end() ?>
