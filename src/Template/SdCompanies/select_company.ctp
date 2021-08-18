<?= $this->Html->script('animation/loadingAnimation.js') ?>
<div class="card login w-50 m-auto cardcolor">
    <?= $this->Form->create($sdCompany);?>
    <div class="card-body">
        <img src="/img/logo-mds.png" class="rounded d-block mx-auto my-3" style='width:300px' alt="G2-MDS">
        <div class="mt-5">
            <div class="form-group mx-auto w-75">
                <label class="text-white"><?php echo __("Select Company");?></label>
                <select class="form-control" name="company_id" id="company_id" placeholder="Please select your company">
                <?php
                    echo "<option value=\"".$userCompany['id']."\">".$userCompany['company_name']."</option>";
                    foreach($sdCompany as $company)
                    {
                        echo "<option value=\"".$company['id']."\">".$company['company_name']."</option>";
                    }
                ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary d-block mx-auto w-50 mt-4 login"><?php echo __("Confirm");?>   </button>
    </div>
    <?= $this->Form->end();?>
</div>