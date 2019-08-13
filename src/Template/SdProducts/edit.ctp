<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdProduct $sdProduct
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sdProduct->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sdProduct->id)]
            )
        ?></li>
        <!-- <li><?= $this->Html->link(__('List Sd Products'), ['action' => 'index']) ?></li> -->
        <li><?= $this->Html->link(__('Companies'), ['controller' => 'SdCompanies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Add Company'), ['controller' => 'SdCompanies', 'action' => 'add']) ?></li>
        <!-- <li><?= $this->Html->link(__('List Sd Product Workflows'), ['controller' => 'SdProductWorkflows', 'action' => 'index']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd Product Workflow'), ['controller' => 'SdProductWorkflows', 'action' => 'add']) ?></li> -->
    </ul>
</nav>
<div class="sdProducts form mx-auto my-3 formContainer text-center" style="width:60%;">
    <?= $this->Form->create($sdProduct) ?>
    <fieldset>
        <p class="pageTitle">
            <?php echo __("Edit Product");?>
        </p>
        <div class="form-row">
            <div class="form-group col-md-4">
                <?php
                   //debug($sdProduct['study_name']);die();
                   echo "<div class=\"input text required\"><label for=\"product-name\">Product Name</label><input class=\"form-control\" type=\"text\" name=\"product_name\" required=\"required\" maxlength=\"255\" id=\"product-name\" value=\"$sdProduct->product_name\" ></div>";
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                    echo "<div class=\"input text required\"><label for=\"study-no\">Study No</label><input class=\"form-control\" type=\"text\" name=\"study_no\" required=\"required\" id=\"study-no\" value=\"$sdProduct->study_no\"></input></div>";
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php
                echo "<div class=\"input text required\"><label for=\"study-name\">Study Name</label><input class=\"form-control\" type=\"text\" name=\"study_name\" required=\"required\" maxlength=\"100\" id=\"study-name\" value=\"$sdProduct->study_name\"></input></div>";
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?php
                echo "<div class=\"input text required\"><label for=\"whodd-decode\">W H O D D Decode</label><input class=\"form-control\" type=\"text\" name=\"WHODD_decode\" required=\"required\" maxlength=\"50\" id=\"whodd-decode\" value=\"$sdProduct->WHODD_decode\"></div>";
                ?>
            </div>
            <div class="form-group col-md-6">
                <?php
                    echo "<div class=\"input select\"><label for=\"sd-company-id\">Sd Company</label><select class=\"form-control\" name=\"sd_company_id\" id=\"sd-company-id\"><option value=\"1\" selected=\"selected\">1</option><option value=\"2\">2</option><option value=\"7\">7</option><option value=\"8\">8</option><option value=\"3\">3</option><option value=\"5\">5</option><option value=\"4\">4</option><option value=\"6\">6</option></select></div>";
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <?php
                    echo "<div class=\"input text required\"><label for=\"short-desc\">Short Desc</label><input class=\"form-control\" type=\"text\" name=\"short_desc\" required=\"required\" maxlength=\"255\" id=\"short-desc\" value=\"$sdProduct->short_desc\"></div>";
                ?>
                </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <?php
                echo "<div class=\"input text required\"><label for=\"product-desc\">Product Desc</label><input class=\"form-control\" type=\"text\" name=\"product_desc\" required=\"required\" maxlength=\"90\" id=\"product-desc\" value=\"$sdProduct->product_desc\"></div>";
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                echo "<div class=\"input text required\"><label for=\"blinding-tech\">Blinding Tech</label><input class=\"form-control\" type=\"text\" name=\"blinding_tech\" required=\"required\" maxlength=\"50\" id=\"blinding-tech\" value=\"$sdProduct->blinding_tech\"></div>";
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                    echo "<div class=\"input number required\"><label for=\"sd-product-flag\">Sd Product Flag</label><input class=\"form-control\" type=\"number\" name=\"sd_product_flag\" required=\"required\" id=\"sd-product-flag\" value=\"$sdProduct->sd_product_flag\"></div>";
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                    echo "<div class=\"input text required\"><label for=\"whodd-code\">W H O D D Code</label><input class=\"form-control\" type=\"text\" name=\"WHODD_code\" required=\"required\" maxlength=\"50\" id=\"whodd-code\" value=\"$sdProduct->WHODD_code\"></div>";
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo "<div class=\"input text required\"><label for=\"whodd-name\">W H O D D Name</label><input class=\"form-control\" type=\"text\" name=\"WHODD_name\" required=\"required\" maxlength=\"100\" id=\"whodd-name\" value=\"$sdProduct->WHODD_name\"></div>";
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                echo "<div class=\"input text required\"><label for=\"mfr-name\">Mfr Name</label><input class=\"form-control\" type=\"text\" name=\"mfr_name\" required=\"required\" maxlength=\"100\" id=\"mfr-name\" value=\"$sdProduct->mfr_name\"></div>";
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                    echo "<div class=\"input text required\"><label for=\"start-date\">Start Date</label><input  class=\"form-control\" type=\"text\" name=\"start_date\" required=\"required\" maxlength=\"10\" id=\"start-date\" value=\"$sdProduct->start_date\"></div>";
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                    echo "<div class=\"input text required\"><label for=\"end-date\">End Date</label><input class=\"form-control\" type=\"text\" name=\"end_date\" required=\"required\" maxlength=\"10\" id=\"end-date\" value=\"$sdProduct->end_date\"></div>";
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo "<div class=\"input number required\"><label for=\"status\">Status</label><input class=\"form-control\" type=\"number\" name=\"status\" required=\"required\" id=\"status\" value=\"$sdProduct->status\"></div>";
                ?>
            </div>
        </div>
    </fieldset>
    <button type="submit" class="btn btn-success w-25 mt-3 mx-auto"><?php echo __("Submit");?></button>
    <?= $this->Form->end() ?>
</div>
