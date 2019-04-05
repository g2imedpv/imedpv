<?php
//echo "I am here<br>";
//debug($sdTabs);
//foreach ($sdTabs as $eachTab){  debug($eachTab->tab_name);  }

// Call to use widget
echo $this->element('widget');
echo $this->element('generatepdf');
?>
<script type="text/javascript">
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
    var readonly =  <?php if($this->request->getQuery('readonly')!=1){$readonly = 0;}
                        else{$readonly = 1;};echo $readonly;?>;
    var caseNo = "<?= $caseNo ?>";
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    var version = <?= $version ?>;
    var tabId = <?= $tabid?>;
    var section = <?php $sdSections;
    echo json_encode($sdSections)?>;
    var caseId = <?= $caseId ?>;
    jQuery(function($) {
        $(document).ready(function () {
            $("[id$=page_number-1]").css('font-weight', 'bold');
    });
    })
</script>
<title>Data Entry</title>
<head>
    <?= $this->Html->script('dataentry/dataEntryMain.js') ?>
    <?= $this->Html->script('dataentry/fieldLogic.js') ?>
</head>
<?php if($this->request->getQuery('readonly')!=1):?>
<!-- Data Entry Top Bar -->

<ul class="topbar nav justify-content-end pt-2 pb-2" id="topbar">

    <!-- "Case Number" Display -->
    <span class="caseNumber" id="caseNumber" title="Case Number">
        Full Data Entry - <b><?= $caseNo ?></b> [<?= $product_name?>]<b>(Version:<?= $version?>)</b>
    </span>

    <!-- "Search" Button -->
    <li class="nav-item">
        <button class="btn btn-outline-info" title="Search" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-search"></i> Search</button>
        </b>
        <div class="dropdown-menu p-3">
            <div class="form-group">
                <input type="text" class="form-control" id="searchFieldKey" placeholder="Search Field Here">
                <!-- <button type="submit" class="btn btn-primary mx-2">Search</button> -->
            </div>
            <div id="searchFieldResult"></div>
        </div>
    </li>

    <!-- "Version Switch" Dropdown Button -->
    <li class="nav-item">
        <a class="btn btn-outline-warning" href="#" title="Version Switch" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-code-branch"></i> Switch Version
        </a>
        <?php
        if(sizeof($case_versions->toList())>1){
            echo "<div class=\"dropdown-menu\">";
            foreach($case_versions as $case_version_detail){
                echo "<a class=\"dropdown-item\" href=\"/sd-tabs/showdetails/1?caseNo=".$caseNo."&version=".$case_version_detail['version_no']."\">".$case_version_detail['version_no']."</a>";
            }
        }
        echo "</div>";
        ?>
    </li>

    <!-- "Compare" Button -->
    <!-- <li class="nav-item">
        <a class="btn btn-outline-info" href="#" title="Version Compare"><i class="far fa-copy"></i> Compare</a>
    </li> -->

    <!-- "Documents" Button -->
    <li class="nav-item">
        <a class="btn btn-outline-info" href="/sd-documents/add_documents/<?= $caseId ?>" title="Documents Check" target="_blank"><i class="far fa-file-alt"></i> Documents</a>
    </li>

    <!-- "Print" Dropdown Button -->
    <li class="nav-item">
        <a class="btn btn-outline-info" href="#" title="Print Out" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-print"></i> Print Out
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" target="_blank" href="/sd-export/genCIOMS/<?php echo $caseId ?>">CIOMS</a>
            <a class="dropdown-item" target="_blank" href="/sd-export/genFDApdf/<?php echo $caseId ?>">FDA</a>
            <a class="dropdown-item" target="_blank" href="/sd-xml-structures/genXML/<?php echo $caseId ?>">XML</a>
            <!-- Add this if location had details
            <div role="separator" class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
            -->
        </div>
    </li>

    <!-- "Export" Dropdown Button -->
    <!-- <li class="nav-item">
        <a class="btn btn-outline-info" href="#" title="Export" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-file-export"></i> Export
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">CIOMS</a>
            <a class="dropdown-item" href="#">FDA</a>
            <a class="dropdown-item" href="#">XML</a>
            <div role="separator" class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
        </div>
    </li> -->

    <!-- "Save All" Button -->
    <li class="nav-item">
    <?php if($writePermission==1){
       echo "<button class=\"btn btn-light text-success mx-1\" title=\"Sign Off\" role=\"button\" data-toggle=\"modal\" data-target=\".signOff\" onclick=\"action(1)\"><i class=\"fas fa-share-square\"></i> Next Step</button>";
       echo "<button class=\"btn btn-light text-success mx-1\" title=\"Push Backward\" role=\"button\" data-toggle=\"modal\" data-target=\".signOff\" onclick=\"action(2)\"><i class=\"fab fa-pushed\"></i> Previous Step</button>";
    }?>
    </li>

    <?php endif;?>
    <div class="modal fade signOff" tabindex="-1" role="dialog" aria-labelledby="signOff" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div id="action-text-hint"></div>
            </div>
        </div>
    </div>
</ul>
<div class="maintab container-fluid">
<?php
     $sectionNavCell = $this->cell('SectionNav',[$tabid,$caseNo,$readonly,$version]);
     echo $sectionNavCell;
?>

<!-- Data Entry Body -->
<div class="dataentry">
    <?= $this->Form->create($sdSections,['id'=> 'dataEntry']);?>
    <?php
        $setNo = "1";
        $exsitSectionNo = [];
        foreach($sdSections as $key => $sdSection){
            $exsitSectionNo[$key] = $sdSection['id'];
        }
        // for($i = 0; $i < sizeof($sdSections); $i++){
        //     $exsitSectionNo[$i] = $sdSections->id;
        //     next($sdSections);
        // }
        displaySectionInTabList($sdSections);
        echo "<div class=\"tab-content\" id=\"nav-tabContent\">";
        foreach($sdSections as $sdSection_detail){
            $exsitSectionNo = displaySection($sdSection_detail, $exsitSectionNo, $sdSections, $setNo, $this, $activitySectionPermissions);
        }
        echo "</div>";

    ?>
    <?php if(($writePermission)&&($this->request->getQuery('readonly')!=1)):?>
    <div class="text-center">
        <button type="submit" class="completeBtn w-25 btn btn-success">Complete</button>
    </div>
    <?php endif;?>
    <hr class="d-inline-block w-100">
    <?= $this->Form->end() ?>
    <?php
    if($this->request->getQuery('readonly')!=1){
        if($tabid==1){
            $sectionTableCell = $this->cell('SectionTable::general', [$caseId]);
            echo $sectionTableCell;
        }elseif($tabid==2)
        {
            $sectionTableCell = $this->cell('SectionTable::reporter', [$caseId]);
            echo $sectionTableCell;
        }elseif($tabid==3)
        {
            $sectionTableCell = $this->cell('SectionTable::patient', [$caseId]);
            echo $sectionTableCell;
        }elseif($tabid==4)
        {
            $sectionTableCell = $this->cell('SectionTable::product', [$caseId]);
            echo $sectionTableCell;
        }elseif($tabid==5)
        {
            $sectionTableCell = $this->cell('SectionTable::event', [$caseId]);
            echo $sectionTableCell;
        }
    }
    ?>
</div>

<?php
/*
* Show sections in tab list
* Modified by Chloe Wang @ April 2, 2019
*/
function displaySectionInTabList($sections)
{
    echo "<nav>";
    echo "<div class=\"nav nav-tabs\" id=\"nav-tab\" role=\"tablist\">";
    foreach ($sections as $eachSection)
    {
        if ($eachSection->section_level == 2)
        {
            echo "<a class=\"nav-item";
            if($eachSection->display_order ==10) echo" active";
            echo " nav-link\" id=\"nav-".$eachSection->id."-tab\" data-toggle=\"tab\" href=\"#secdiff-".$eachSection->id."\" role=\"tab\" aria-controls=\"secdiff-".$eachSection->id."\" aria-selected=\"true\">";
            echo $eachSection->section_name;
            echo "</a>";
        }
    }
    echo "</div>";
    echo "</nav>";
}
function displaySection($section, $exsitSectionNo, $sdSections, $setNo, $html, $permission){
    if(empty($exsitSectionNo)) return null;
    if(in_array($section->id,$exsitSectionNo)){
        $sectionKey = array_search($section->id,$exsitSectionNo);
        if(!empty($section->child_section)) {
            echo"<div class=\"tab-pane";
            if($section->display_order ==10)echo" show active\"";else echo " fade\"";
            echo " aria-labelledby=\"nav-".$section->id."-tab\" role=\"tabpanel\" class=\"secdiff\" id=\"secdiff-".$section->id."\">";
        }
        displaySingleSection($section, $setNo, $sectionKey, $html, $permission);

        $exsitSectionNo[$sectionKey]= null;
        if(!empty($section->child_section)){
            $child_array = explode(",",$section->child_section);
            foreach($child_array as $Key => $sdSectionKey){
                if(array_search($sdSectionKey,$exsitSectionNo))
                $exsitSectionNo=displaySection($sdSections[array_search($sdSectionKey,$exsitSectionNo)],$exsitSectionNo,$sdSections, $setNo, $html, $permission);
            }
            echo"</div>";
        }

        return $exsitSectionNo;

    }
    return $exsitSectionNo;
}

function displaySingleSection($section, $setNo, $sectionKey, $html, $permission){
    $i = 0;
    if($permission == null) $permission = 1;
    else $permission = $permission[$section['id']];
    if($section->section_level == 2){
        echo "<div id=\"section_label-".$section->id."\" class=\"subtabtitle col-md-12\">".$section->section_name."</div>";
        if(($section->is_addable == 1)&&($permission==1))
        {
            echo "<div id=\"pagination-l2-section-".$section->id."\">";
            echo "<input type=\"button\" id=\"delete_section-".$section->id."\"  class=\"float-right px-3 mx-3 btn btn-outline-danger\" onclick=\"l2deleteSection(".$section->id.")\" value=\"Delete\" style=\"display:none\">";
            echo "<input type=\"button\" id=\"child_section-";
            $child_array = explode(",",$section->child_section);
            foreach($child_array as $Key => $sdSectionKey) echo "[".$sdSectionKey."]";
            echo "-sectionKey-".$sectionKey."-setNo-1-section-".$section->id."\" onclick=\"level2setPageChange(".$section->id.",1,1)\" class=\"float-right px-3 mx-3 btn btn-info\" value=\"Add\">";
            echo "</div>";
            echo "<div class=\"showpagination\" id=\"showpagination-".$section->id."\"></div>";
        }
    }elseif($section->section_level ==1 ){

        $max_set_No = 0;
        foreach($section->sd_section_structures as $sd_section_structureK =>$sd_section_structure_detail){
            foreach ($sd_section_structure_detail->sd_field->sd_field_values as $key_detail_field_values=>$value_detail_field_values){
                if($value_detail_field_values->set_number>=$max_set_No)
                    $max_set_No = $value_detail_field_values->set_number;
            }
        }
        echo "<div class=\"header-section\">";
        echo "<h3 id=\"section_label-".$section->id."\"class=\"secspace\">".$section->section_name;
        echo "<input id=\"save-btn".$section->id."-".$sectionKey."\" onclick=\"saveSection(".$section->id.")\" class=\"ml-3 px-5 btn btn-outline-primary\" type=\"button\" value=\"Save\" style=\"display:none\">";
        //echo"<a role=\"button\" id=\"save-btn".$section->id."-".$sectionKey."\" onclick=\"saveSection(".$section->id.")\" class=\"ml-3 px-5 btn btn-outline-secondary\" aria-pressed=\"true\" style=\"display:none\">Save</a>";        // Pagination
        echo "</h3>";
            if(($section->is_addable == 1)&&($permission==1))
            {
                echo "<div id=\"pagination-section-".$section->id."\" class=\"DEpagination\">";
                if($max_set_No != 0)
                //echo "<a role=\"button\" id=\"delete-btn".$section->id."-".$sectionKey."\" onclick=\"deleteSection(".$section->id.")\" class=\"ml-3 px-5 btn btn-outline-secondary\" aria-pressed=\"true\">delete</a>";
                echo "<input id=\"delete-btn".$section->id."-".$sectionKey."\" onclick=\"deleteSection(".$section->id.")\" class=\"ml-3 px-5 btn btn-outline-danger\" type=\"button\" value=\"Delete\">";

                echo "<input type=\"button\" id=\"add_set-".$section->id."-sectionKey-".$sectionKey."-setNo-".$max_set_No."\" onclick=\"setPageChange(".$section->id.",1,1)\" class=\"float-right px-3 mx-3 btn btn-info\" value=\"Add\"";
                if($max_set_No == 0) echo "style=\"display:none\">";
                echo "<nav class=\"float-right ml-3\" title=\"Pagination\" aria-label=\"Page navigation example\">";
                echo "<ul class=\"pagination mb-0 mx-2\">";
                echo    "<li class=\"page-item\" id=\"left_set-".$section->id."-sectionKey-".$sectionKey."-setNo-1\" onclick=\"setPageChange(".$section->id.",0)\">";
                echo    "<a class=\"page-link\" aria-label=\"Previous\">";
                echo        "<span aria-hidden=\"true\">&laquo;</span>";
                echo        "<span class=\"sr-only\">Previous</span>";
                echo    "</a>";
                echo    "</li>";
                if($max_set_No != 0){
                    for($pageNo = 1; $pageNo<=$max_set_No; $pageNo++ ){
                        echo    "<li class=\"page-item\" id=\"section-".$section->id."-page_number-".$pageNo."\" onclick=\"setPageChange(".$section->id.",".$pageNo.")\"><a class=\"page-link\">".$pageNo."</a></li>";
                    }
                }else{
                    echo    "<li class=\"page-item\" style=\"font-weight:bold\" id=\"section-".$section->id."-page_number-1\" onclick=\"setPageChange(".$section->id.",1)\"><a class=\"page-link\">1</a></li>";

                }
                echo    "<li class=\"page-item\" id=\"right_set-".$section->id."-sectionKey-".$sectionKey."-setNo-1\" onclick=\"setPageChange(".$section->id.",2)\">";
                echo    "<a class=\"page-link\" aria-label=\"Next\">";
                echo        "<span aria-hidden=\"true\">&raquo;</span>";
                echo        "<span class=\"sr-only\">Next</span>";
                echo    "</a>";
                echo    "</li>";
                echo "</ul>";
                echo "</nav>";
                echo"</div>";
            }
            echo "<div id=\"addbtnalert-".$section->id."\" class=\"addbtnalert mx-3 alert alert-danger\" role=\"alert\" style=\"display:none;\">You are adding a new record</div>";
            echo"</div>";
        echo "<div class=\"fieldInput\">";
        echo "<hr class=\"my-2\">";
        $length_taken = 0;
        $cur_row_no = 0;
        foreach($section->sd_section_structures as $sd_section_structureK =>$sd_section_structure_detail){
            if($i == 0){
                $length_taken = 0;
                $cur_row_no = $sd_section_structure_detail->row_no;
                echo"<div class=\"form-row\">";
            }
            elseif($cur_row_no != $sd_section_structure_detail->row_no){
                $length_taken = 0;
                $cur_row_no = $sd_section_structure_detail->row_no;
                echo"</div><div class=\"form-row\">";
            }
            $j = -1;
            $jflag = 0;
            foreach ($sd_section_structure_detail->sd_field->sd_field_values as $key_detail_field_values=>$value_detail_field_values){
                $jflag ++;
                if($value_detail_field_values->set_number==$setNo){
                    $j = $key_detail_field_values;
                }
            }
            if ($j==-1) $j = $jflag;
            echo "<div id=\"section-".$section->id."-field-".$sd_section_structure_detail->sd_field->id."\" class=\"form-group col-md-".$sd_section_structure_detail->field_length." offset-md-".($sd_section_structure_detail->field_start_at-$length_taken)."\">";
            echo "<label id= \"section-".$section->id."-field_label-".$sd_section_structure_detail->sd_field->id."\" >".$sd_section_structure_detail->sd_field->field_label."</label>";
            if(!empty($sd_section_structure_detail->sd_field->comment))
            echo " <a id=\"field_helper-".$sd_section_structure_detail->sd_field->id."\" tabindex=\"0\" role=\"button\" data-toggle=\"popover\" title=\"Field Helper\" data-content=\"<div>".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->comment)."</div>\"><i class=\"qco fas fa-info-circle\"></i></a>";
            // if(!empty($sd_section_structure_detail->sd_section_values)) print_r($section->sd_section_sets[0]->set_no);
                $id_idHolder = 'section-'.$section->id.'-sd_section_structures-'.$i.'-sd_field_values-'.$j.'-id';
                $id_nameHolder = 'sd_field_values['.$section->id.']['.$sd_section_structureK.'][id]';
                $field_value_nameHolder = 'sd_field_values['.$section->id.']['.$sd_section_structureK.'][field_value]';
                if($permission==1){
                    if((!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))){
                        echo"<input id= ".$id_idHolder." name=".$id_nameHolder." value=".$sd_section_structure_detail->sd_field->sd_field_values[$j]->id." type=\"hidden\">";
                    }else{
                        echo"<input id= ".$id_idHolder." name=".$id_nameHolder." value=\"\" type=\"hidden\">";
                    }
                    if($sd_section_structure_detail->is_required) echo "<i class=\"fas fa-asterisk reqField\"></i>" ;
                    echo "<input id= \"section-".$section->id."-is_required-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][is_required]\" value=";
                    if($sd_section_structure_detail->is_required) echo "1" ;else echo "0";
                    echo " type=\"hidden\">";
                    echo "<div id= \"section-".$section->id."-error_message-".$sd_section_structure_detail->sd_field->id."\" style=\"display:none\"></div>";
                    echo "<input id= \"section-".$section->id."-set_number-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][set_number]\" value=".$setNo." type=\"hidden\">";
                    echo "<input id= \"section-".$section->id."-sd_field_id-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][sd_field_id]\" value=".$sd_section_structure_detail->sd_field->id." type=\"hidden\">";
                }
                    switch($sd_section_structure_detail->sd_field->sd_element_type->type_name){
                        case 'select':
                            echo "<select class=\"form-control\" id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."\" name=".$field_value_nameHolder.">";
                                echo"<option id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."-option-null\" value=\"null\" ></option>";
                                foreach($sd_section_structure_detail->sd_field->sd_field_value_look_ups as $option_no=>$option_detail){
                                    echo "<option id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" value=".$option_detail['value'];
                                    if($permission==2) echo " disabled";
                                    if(!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])&&$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value==$option_detail['value'])
                                    echo " selected=\"true\"";
                                    echo ">".$option_detail['caption']."</option>";
                                };
                            echo"</select>";
                            continue;
                        case 'text':
                            echo "<input id=\"section-".$section->id."-field_rule-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][field_rule]\" value=".$sd_section_structure_detail->sd_field->field_length."-".$sd_section_structure_detail->sd_field->field_type." type=\"hidden\">";
                            echo "<input id=\"section-".$section->id."-text-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder." type=\"text\"";
                            if($permission==2) echo " disabled ";
                            echo (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?"value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":null;
                            echo ">";
                            continue;
                        case 'radio':
                            echo "<div id=\"section-".$section->id."-radio-".$sd_section_structure_detail->sd_field->id."\">";
                            foreach($sd_section_structure_detail->sd_field->sd_field_value_look_ups as $option_no=>$option_detail){
                                echo "<div class=\"custom-control custom-radio custom-control-inline\">";
                                echo "<input class=\"custom-control-input\" id=\"section-".$section->id."-radio-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" name=".$field_value_nameHolder." type=\"radio\" value=";
                                echo $option_detail['value'];
                                if($permission==2) echo " disabled";
                                if(!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])&&$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value==$option_detail['value'])
                                echo " checked=true";
                                echo "><label class=\"custom-control-label\" for=\"section-".$section->id."-radio-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" >".$option_detail['caption']."</label>";
                                echo "</div>";
                            }
                            echo "</div>";
                            continue;
                        case 'checkbox':
                            echo "<div class=\"form-row\" id=\"section-".$section->id."-checkbox-".$sd_section_structure_detail->sd_field->id."\">";
                            $valuesSet = array();
                            $valueCount = 0;
                            if(!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])) $valuesSet = $sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value;
                            foreach($sd_section_structure_detail->sd_field->sd_field_value_look_ups as $option_no=>$option_detail){
                                echo "<div class=\"custom-control custom-radio custom-control-inline col-md-3\">";
                                echo "<input id=\"section-".$section->id."-checkbox-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" class=\"checkboxstyle\"  name=".$field_value_nameHolder." value=";
                                echo $option_detail['value'];
                                if($permission==2) echo " disabled";
                                if((!empty($valuesSet))&&(substr($valuesSet, $option_detail['value']-1,1)==1))
                                echo " checked=\"true\"";
                                echo " type=\"checkbox\" ><label class=\"form-check-label\" for=\"checkbox-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\">".$option_detail['caption']."</label>";
                                echo "</div>";
                                $valueCount ++;
                            }
                            if($permission==1){
                                echo "<input id=\"section-".$section->id."-checkbox-".$sd_section_structure_detail->sd_field->id."-".$valueCount."-final\" class=\"checkboxstyle\"  name=".$field_value_nameHolder." value=\"";
                                if (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])){
                                    echo $sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value;
                                }
                                else { for($y = 0; $y<$valueCount;$y++)echo "0";
                                }
                                echo "\" type=\"hidden\">";
                            }
                            echo"</div>";
                            continue;
                        case 'textarea':
                            echo "<input id=\"section-".$section->id."-field_rule-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][field_rule]\" value=".$sd_section_structure_detail->sd_field->field_length."-".$sd_section_structure_detail->sd_field->field_type." type=\"hidden\">";
                            echo "<textarea id=\"section-".$section->id."-textarea-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder;
                            if($permission==2) echo " disabled";
                            echo " rows=".$sd_section_structure_detail->field_height.">";
                            echo (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value):null;
                            echo "</textarea>";
                            continue;
                        case 'date':
                            echo "<input id=\"section-".$section->id."-field_rule-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][field_rule]\" value=".$sd_section_structure_detail->sd_field->field_length."-".$sd_section_structure_detail->sd_field->field_type." type=\"hidden\">";
                            echo "<input type=\"text\" class=\"form-control\" name=".$field_value_nameHolder." id=\"section-".$section->id."-date-".$sd_section_structure_detail->sd_field->id."\" ";
                            if($permission==2) echo " disabled ";
                            echo "value=\"";
                            echo (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value:null;
                            echo "\">";
                            continue;
                        case 'whodra browser':
                            if($permission==1){
                                $whoddCell = $html->cell('Whodd',[$sd_section_structure_detail->sd_field->id]);
                                echo $whoddCell;
                                echo "<input readonly=\"readonly\" style=\"float:left\" id=\"section-".$section->id."-whodracode-".$sd_section_structure_detail->sd_field->id."\" class=\"col-md-5 form-control\" name=".$field_value_nameHolder." type=\"text\"";
                                echo (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?"value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":null;
                                echo " >";
                            }
                            continue;
                        case 'whodra show':
                            echo "<input id=\"section-".$section->id."-whodraname-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder." type=\"text\"";
                            echo (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?"value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":null;
                            if($permission==2) echo " disabled ";
                            echo " readonly=\"readonly\">";
                            continue;
                        case 'Meddra browser':
                            $meddraCell = $html->cell('Meddra',[$sd_section_structure_detail->sd_field->id]);
                            echo $meddraCell;
                            continue;
                        case 'Meddra show':
                            echo "<input id=\"section-".$section->id."-".$sd_section_structure_detail->sd_field->descriptor."-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder." type=\"text\"";
                            echo (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?"value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":null;
                            echo "readonly=\"readonly\">";
                            continue;
                        case 'precise date':
                            echo "<input id=\"section-".$section->id."-field_rule-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][field_rule]\" value=".$sd_section_structure_detail->sd_field->field_length."-".$sd_section_structure_detail->sd_field->field_type." type=\"hidden\">";
                            echo "<input type=\"date\" class=\"form-control\" name=".$field_value_nameHolder." id=\"section-".$section->id."-date-".$sd_section_structure_detail->sd_field->id."\" ";
                            if($permission==2) echo " disabled ";
                            echo "value=\"";
                            echo (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value:null;
                            echo "\">";
                            continue;
                    }
                echo"</div>";
            $i++;
            $length_taken = $sd_section_structure_detail->field_length + $sd_section_structure_detail->field_start_at;
        }
        if($i!=0) echo"</div>";
        echo "</div>";
    }
}
?>
