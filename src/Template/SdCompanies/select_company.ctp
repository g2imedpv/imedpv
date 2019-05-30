<div class="card login w-50 m-auto cardcolor">
    <?= $this->Form->create($sdCompany);?>
    <div class="card-body">
        <img src="/img/logo-mds.png" class="rounded mx-auto d-block mt-5" alt="G2-MDS">
        <div class="mt-5">
            <div class="form-group mx-auto w-75">
                <label class="text-white">Select Company</label>
                <select class="form-control" name="company_id" id="company_id" placeholder="Please select your company">
                <?php
                    foreach($sdCompany as $company)
                    {
                        echo "<option value=\"".$company['id']."\">".$company['company_name']."</option>";
                    }
                ?>
                </select>
            </div>
        </div>
            <button type="submit" class="btn btn-primary d-block mx-auto w-50 mt-4"> Confirm  </button>
    </div>
    <?= $this->Form->end();?>
</div>