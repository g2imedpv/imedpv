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
    var autoChangeflag = false;
    var caseNo = "<?= $caseNo ?>";
    var userId = <?php echo $this->request->getSession()->read('Auth.User.id')?>;
    var version = <?= $version ?>;
    var distribution_id = <?php if(empty($distribution_id)) echo "null"; else echo $distribution_id;?>;
    var tabId = <?= $tabid?>;
    var tableFields ="";
    var dynamic_options = <?php if(empty($dynamic_options)) echo"null"; else echo json_encode($dynamic_options)?>;
    var section = <?php $sdSections;
    echo json_encode($sdSections)?>;
    var caseId = <?= $caseId ?>;
    // jQuery(function($) {
    //     $(document).ready(function () {
    //         $("[id$=page_number-1]").css('font-weight', 'bold');
    // });
    // })
</script>
<title>Data Entry</title>
<head>
    <?= $this->Html->script('dataentry/dataEntryMain.js') ?>
    <?= $this->Html->script('dataentry/fieldLogic.js') ?>
    <?= $this->Html->script('meddra.js') ?>
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
        <a class="btn btn-outline-info" href="#" title="Version Switch" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

    <!-- "Export" Dropdown Button -->
    <li class="nav-item">
        <a class="btn btn-outline-info" href="#" title="Export" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-print"></i> Export
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

    <!-- "Print" Dropdown Button -->
    <li class="nav-item">
        <a class="btn btn-outline-info" href="#" title="Print"  role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-file-export"></i> Print Out
        </a>
    </li>

    <!-- "Save All" Button -->
    <li class="nav-item">
    <?php if($writePermission==1){
       echo "<button class=\"btn btn-outline-warning mx-1\" title=\"Sign Off\" role=\"button\" data-toggle=\"modal\" data-target=\".signOff\" onclick=\"action(1)\"><i class=\"fas fa-share-square\"></i> Next Step</button>";
       echo "<button class=\"btn btn-outline-warning mx-1\" title=\"Push Backward\" role=\"button\" data-toggle=\"modal\" data-target=\".signOff\" onclick=\"action(2)\"><i class=\"fab fa-pushed\"></i> Previous Step</button>";
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
    <div class="subtabtitle"><?= $sdSections[0]['section_name']?></div>
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
        $result = displaySection($sdSections[0],$sdSections,[],$exsitSectionNo, $this, $activitySectionPermissions, $dynamic_options);
        print_r($result['field_Text']);
        print_r($result['child_Field_Text']);
        print_r($result['child_Div_Text']);
        // displaySectionInTabList($result['nav_Text']);
        // echo "<div class=\"tab-content\" id=\"nav-tabContent\">";
        // foreach($sdSections as $sdSection_detail){
        //     $exsitSectionNo = displaySectionOld($sdSection_detail, $exsitSectionNo, $sdSections, $setNo, $this, $activitySectionPermissions);
        // }
        // echo "</div>";

    ?>
    <?php if(($writePermission)&&($this->request->getQuery('readonly')!=1)):?>
    <div class="text-center">
    <button type="submit" class="completeBtn w-25 btn btn-success mb-5">Complete</button>
    </div>
    <?php endif;?>
    <?= $this->Form->end() ?>
    <?php 
    //add document button form
    echo $this->element('documentbtn');
    ?>
    <?php
    // if($this->request->getQuery('readonly')!=1){
    //     if($tabid==1){
    //         $sectionTableCell = $this->cell('SectionTable::general', [$caseId]);
    //         echo $sectionTableCell;
    //     }elseif($tabid==2)
    //     {
    //         $sectionTableCell = $this->cell('SectionTable::reporter', [$caseId]);
    //         echo $sectionTableCell;
    //     }elseif($tabid==3)
    //     {
    //         $sectionTableCell = $this->cell('SectionTable::patient', [$caseId]);
    //         echo $sectionTableCell;
    //     }elseif($tabid==4)
    //     {
    //         $sectionTableCell = $this->cell('SectionTable::product', [$caseId]);
    //         echo $sectionTableCell;
    //     }elseif($tabid==5)
    //     {
    //         $sectionTableCell = $this->cell('SectionTable::event', [$caseId]);
    //         echo $sectionTableCell;
    //     }
    // }
    // ?>
</div>

<?php
/*
* Show sections in tab list
* Modified by Chloe Wang @ April 2, 2019
*/
function displaySectionInTabList($sections)
{
    echo "<nav><div class=\"nav nav-tabs\" id=\"nav-tab\" role=\"tablist\">";
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
function displaySectionOld($section, $exsitSectionNo, $sdSections, $setNo, $html, $permission){
    if(empty($exsitSectionNo)) return null;
    if(in_array($section->id,$exsitSectionNo)){
        $sectionKey = array_search($section->id,$exsitSectionNo);
        if(!empty($section->child_section)) {
            echo"<div class=\"tab-pane";
            if($section->display_order ==10)echo" show active\"";else echo " fade\"";
            echo " aria-labelledby=\"nav-".$section->id."-tab\" role=\"tabpanel\" class=\"secdiff\" id=\"secdiff-".$section->id."\">";
        }
        displaySingleSection($section, $setNo, $sectionKey, $html, $permission, $dynamic_options);

        $exsitSectionNo[$sectionKey]= null;
        if(!empty($section->child_section)){
            $child_array = explode(",",$section->child_section);
            foreach($child_array as $Key => $sdSectionKey){
                if(array_search($sdSectionKey,$exsitSectionNo))
                $exsitSectionNo=displaySectionOld($sdSections[array_search($sdSectionKey,$exsitSectionNo)],$exsitSectionNo,$sdSections, $setNo, $html, $permission);
            }
            echo"</div>";
        }

        return $exsitSectionNo;

    }
    return $exsitSectionNo;
}
function displayTitle($sectionId, $section_name, $sectionKey, $permission){
    $text ="";
    // $max_set_No = 0;
    // foreach($section->sd_section_structures as $sd_section_structureK =>$sd_section_structure_detail){
    //     foreach ($sd_section_structure_detail->sd_field->sd_field_values as $key_detail_field_values=>$value_detail_field_values){
    //         if($value_detail_field_values->set_number>=$max_set_No)
    //             $max_set_No = $value_detail_field_values->set_number;
    //     }
    // }
    $text =$text. "<div class=\"header-section panel-heading\">";//Todo
    $text =$text. "<h3 id=\"section_label-".$sectionId."\"class=\"secspace panel-title-dataentry\">";
    $text =$text."<a data-toggle=\"collapse\" href=\"#input-".$sectionId."-sectionKey-".$sectionKey.",#card-summary-".$sectionId.",#addbtn-".$sectionId.",#deletebtn-".$sectionId.",#pagination-section-".$sectionId.",#save-btn".$sectionId."-".$sectionKey."\">".$section_name."</a></h3>";
    //echo"<a role=\"button\" id=\"save-btn".$sectionId."-".$sectionKey."\" onclick=\"saveSection(".$sectionId.")\" class=\"ml-3 px-5 btn btn-outline-secondary\" aria-pressed=\"true\" style=\"display:none\">Save</a>";        // Pagination
    $text =$text. "</h3>";
        // if(($section->is_addable == 1)&&($permission==1))
        // {
        //     $text =$text. "<div id=\"pagination-section-".$sectionId."\" class=\"DEpagination\">";
        //     if($max_set_No != 0)
        //     //echo "<a role=\"button\" id=\"delete-btn".$sectionId."-".$sectionKey."\" onclick=\"deleteSection(".$sectionId.")\" class=\"ml-3 px-5 btn btn-outline-secondary\" aria-pressed=\"true\">delete</a>";
        //     $text =$text. "<input id=\"delete-btn".$sectionId."-".$sectionKey."\" onclick=\"deleteSection(".$sectionId.")\" class=\"ml-3 px-5 btn btn-outline-danger\" type=\"button\" value=\"Delete\">";

        //     $text =$text. "<input type=\"button\" id=\"add_set-".$sectionId."-sectionKey-".$sectionKey."-setNo-".$max_set_No."\" onclick=\"setPageChange(".$sectionId.",1,1)\" class=\"float-right px-3 mx-3 btn btn-info\" value=\"Add\"";
        //     if($max_set_No == 0) $text =$text. "style=\"display:none\">";
        //     $text =$text. "<nav class=\"float-right ml-3\" title=\"Pagination\" aria-label=\"Page navigation example\">";
        //     $text =$text. "<ul class=\"pagination mb-0 mx-2\">";
        //     $text =$text.    "<li class=\"page-item\" id=\"left_set-".$sectionId."-sectionKey-".$sectionKey."-setNo-1\" onclick=\"setPageChange(".$sectionId.",0)\">";
        //     $text =$text.    "<a class=\"page-link\" aria-label=\"Previous\">";
        //     $text =$text.        "<span aria-hidden=\"true\">&laquo;</span>";
        //     $text =$text.        "<span class=\"sr-only\">Previous</span>";
        //     $text =$text.    "</a>";
        //     $text =$text.    "</li>";
        //     if($max_set_No != 0){
        //         for($pageNo = 1; $pageNo<=$max_set_No; $pageNo++ ){
        //             $text =$text.    "<li class=\"page-item\" id=\"section-".$sectionId."-page_number-".$pageNo."\" onclick=\"setPageChange(".$sectionId.",".$pageNo.")\"><a class=\"page-link\">".$pageNo."</a></li>";
        //         }
        //     }else{
        //         $text =$text.    "<li class=\"page-item\" style=\"font-weight:bold\" id=\"section-".$sectionId."-page_number-1\" onclick=\"setPageChange(".$sectionId.",1)\"><a class=\"page-link\">1</a></li>";

        //     }
        //     $text =$text.    "<li class=\"page-item\" id=\"right_set-".$sectionId."-sectionKey-".$sectionKey."-setNo-1\" onclick=\"setPageChange(".$sectionId.",2)\">";
        //     $text =$text.    "<a class=\"page-link\" aria-label=\"Next\">";
        //     $text =$text.        "<span aria-hidden=\"true\">&raquo;</span>";
        //     $text =$text.        "<span class=\"sr-only\">Next</span>";
        //     $text =$text.    "</a>";
        //     $text =$text.    "</li>";
        //     $text =$text. "</ul>";
        //     $text =$text. "</nav>";
        //     $text =$text."</div>";
        // }
        $text =$text. "<div id=\"addbtnalert-".$sectionId."\" class=\"addbtnalert mx-3 alert alert-danger\" role=\"alert\" style=\"display:none;\">You are adding a new record</div>";
        $text =$text."</div>";
    return $text;
}
function displaySummary($SectionInfo, $setArray, $section_level, $section_key, $dynamic_options){
    
    $fields = $SectionInfo->sd_section_summary->sdFields;
    $sectionId = $SectionInfo->id;
    $text = "";
    $text = $text."<div class='card mt-1 mb-2 panel-collapse show' id='card-summary-".$sectionId."'>";
    $text = $text."<div class='card-header '>";
    $text = $text."<div id='summary-".$sectionId."' class='layer".$section_level."' style=\"overflow:scroll; max-height:380px;\">";
    $text = $text."<input type=\"hidden\" id='setArray-".$sectionId."' value='";
    for($i = sizeof($setArray);$i>0;$i--){
        $text = $text.$setArray[$i-1].",";
    } 
    $text = $text."'>";
    $text = $text."<input type=\"hidden\" id='setArrayValue-".$sectionId."' value='";
    $setV = "";
    for($i = 0; $i < sizeof($setArray) ;$i ++){
        if($i) $setV = $setV."1,";
    } 
    $text = $text.substr($setV,0,-1);
    $text = $text."'>";
    $text = $text."<table class=\"table table-bordered table-hover layer".$section_level."\" id=\"sectionSummary-".$sectionId."-sectionKey-".$section_key."\">";
    $text = $text."<thead>";
    $text = $text."<tr>";
    $text = $text."<th>SetNo</th>";
    foreach($fields as $field_detail){
        $text = $text."<th scope=\"col\" id=\"col-".$sectionId."-".$field_detail->id."\">".$field_detail->field_label."</th>";

    }
    $text = $text."<th scope=\"col\">Action</th>";
    $row = 1;
    $text = $text."</thead>";
    $text = $text."<tbody>";
    do{
        $rowtext = "";
        $noValue = sizeof($fields);
        foreach($fields as $field_detail){
            $noMatchFlag = 0;
            foreach($field_detail->sd_field_values as $field_value){
                $setArray = explode(',',$field_value->set_number);
                //Only display the first set
                $levelMatch = 1;
                for($i = sizeof($setArray) - 1 ; $i > 0 ; $i--){
                    if($setArray[$i] != '1' && $setArray[$i] != '*'){
                        $levelMatch = 0;
                        break;
                    }
                }
                if($levelMatch  && $setArray[0] == $row){
                    $rowtext = $rowtext."<td id=\"section-".$sectionId."-row-".$row."-td-".$field_detail->id."\">";
                    if($field_detail->sd_element_type_id != 1 && $field_detail->sd_element_type_id != 13 && $field_detail->sd_element_type_id != 3 && $field_detail->sd_element_type_id != 4)
                        $rowtext = $rowtext.$field_value->field_value;
                    else if($field_detail->sd_element_type_id == 13){
                        $rowtext = $rowtext.$dynamic_options[explode('-',$field_detail->descriptor)[1]][$field_value->field_value];
                    }
                    else {
                        // debug($field_value->field_value);
                        foreach($field_detail->sd_field_value_look_ups as $look_ups){
                            if($look_ups->value == $field_value->field_value){
                                $rowtext = $rowtext.$look_ups->caption;
                                break;
                            }
                        }
                    }
                    $rowtext = $rowtext."</td>";
                    $noValue --;
                    $noMatchFlag = 1;
                    continue;
                }
            }
            if(!$noMatchFlag) $rowtext = $rowtext."<td id=\"section-".$sectionId."-row-".$row."-td-".$field_detail->id."\"></td>";
            
        }
        if($noValue != sizeof($fields)) {
            $text = $text."<tr ";
            if($row==1) $text = $text."class=\"selected-row\" ";
            $text = $text."id=\"section-".$sectionId."-row-".$row."\" onclick=\"setPageChange(".$sectionId.",".$row.")\" ><td>".$row."</td>".$rowtext."
                                                <td><button type=\"button\" class='btn btn-outline-danger' onclick='deleteSection(".$sectionId.",".$row.",". $section_key.")' role='button' title='show'><i class='fas fa-trash-alt'></i></button></td></tr>";
        }//TODO ADD JS FUNCTION TO DISPLAY SET
        $row++;
    }while($noValue != sizeof($fields));
    $addtext = "<button type=\"button\" id=\"addbtn-".$sectionId."\" onclick=\"setPageChange(".$sectionId.",".(int)($row-1).",1)\" class='btn btn-outline-primary ml-3 mb-2 panel-collapse show' role='button' title='add'><i class='fas fa-plus'></i> Add</button><br><br>";
    $text = $text."</tr>"; 
    $text = $text."</tbody>";
    $text = $text."</table>";
    $text = $text."</div>";
    $text = $text."</div>"; 
    $text =$text. "</div>";
    if($sectionId!="44" && $sectionId!="65" && $row > 2)
        $text = $addtext.$text;
    return $text;
}
function displaySingleSection($section, $setArray, $sectionKey, $html, $permission, $dynamic_options){
    
    $i = 0;
    $text ="";
    if($permission == null) $permission = 1;
    else $permission = $permission[$section['id']];
    // if($section->section_level == 2){
    //     $text =$text. "<div id=\"section_label-".$section->id."\" class=\"subtabtitle col-md-12\">".$section->section_name."</div>";
    //     if(($section->is_addable == 1)&&($permission==1))
    //     {
    //         $text =$text. "<div id=\"pagination-l2-section-".$section->id."\">";
    //         $text =$text. "<input type=\"button\" id=\"delete_section-".$section->id."\"  class=\"float-right px-3 mx-3 btn btn-outline-danger\" onclick=\"l2deleteSection(".$section->id.")\" value=\"Delete\" style=\"display:none\">";
    //         $text =$text. "<input type=\"button\" id=\"child_section-";
    //         $child_array = explode(",",$section->child_section);
    //         foreach($child_array as $Key => $sdSectionKey) echo "[".$sdSectionKey."]";
    //         $text =$text. "-sectionKey-".$sectionKey."-setNo-1-section-".$section->id."\" onclick=\"level2setPageChange(".$section->id.",1,1)\" class=\"float-right px-3 mx-3 btn btn-info\" value=\"Add\">";
    //         $text =$text. "</div>";
    //         $text =$text. "<div class=\"showpagination\" id=\"showpagination-".$section->id."\"></div>";
    //     }
    // }elseif($section->section_level ==1 ){
        $text =$text. "<div class=\"fieldInput panel-collapse show\" id=\"input-".$section->id."-sectionKey-".$sectionKey."\">";
        if(!empty($setArray)){
            $text =$text. "<input type =\"hidden\" name=\"section[".$section->id."]\" value=\"";
            $sectionsText = "";
            for($i = sizeof($setArray); $i > 0; $i--){
                $sectionsText =$sectionsText.$setArray[$i-1].":1,";
            }
            $text = $text.substr($sectionsText,0,-1);
            $text =$text. "\">";
        }
        $text =$text. "<hr class=\"my-2\">";
        $length_taken = 0;
        $cur_row_no = 0;
        foreach($section->sd_section_structures as $sd_section_structureK =>$sd_section_structure_detail){
            if($i == 0){
                $length_taken = 0;
                $cur_row_no = $sd_section_structure_detail->row_no ;
                $text =$text."<div class=\"form-row \">";
            }
            elseif($cur_row_no != $sd_section_structure_detail->row_no){
                $length_taken = 0;
                $cur_row_no = $sd_section_structure_detail->row_no;
                $text =$text."</div><div class=\"form-row \">";
            }
            $j = sizeof($sd_section_structure_detail->sd_field->sd_field_values);
            if(empty($setArray)){
                $j = sizeof($sd_section_structure_detail->sd_field->sd_field_values)-1;
                if(sizeof($sd_section_structure_detail->sd_field->sd_field_values)==0) $j++;
            }
            if(!empty($setArray)){
                foreach ($sd_section_structure_detail->sd_field->sd_field_values as $key_detail_field_values=>$value_detail_field_values){
                    $levelMatch = 1;
                    $sectionSetArray = explode(',',$value_detail_field_values->set_number);
                    if($sd_section_structure_detail->sd_field->id == '149'&& $section->id=='44'){
                        if($sectionSetArray[0]=='1'){
                            $j = $key_detail_field_values;break;
                        }
                    }else {
                        for($count = sizeof($sectionSetArray) - 1 ; $count >= 0 ; $count--){
                            if($sectionSetArray[$count] != '1'){
                                $levelMatch = 0;
                                break;               
                            }
                        }
                        if(!$levelMatch) continue;
                        $j = $key_detail_field_values;
                    }
                }
            }
            $text =$text. "<div id=\"section-".$section->id."-field-".$sd_section_structure_detail->sd_field->id."\" class=\"form-group col-md-".$sd_section_structure_detail->field_length." offset-md-".($sd_section_structure_detail->field_start_at-$length_taken)."\">";
            $text =$text. "<label id= \"section-".$section->id."-field_label-".$sd_section_structure_detail->sd_field->id."\" >".$sd_section_structure_detail->sd_field->field_label."</label>";
            if(!empty($sd_section_structure_detail->sd_field->comment))
            $text =$text. " <a id=\"field_helper-".$sd_section_structure_detail->sd_field->id."\" tabindex=\"0\" role=\"button\" data-toggle=\"popover\" title=\"Field Helper\" data-content=\"<div>".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->comment)."</div>\"><i class=\"qco fas fa-info-circle\"></i></a>";
            // if(!empty($sd_section_structure_detail->sd_section_values)) print_r($section->sd_section_sets[0]->set_no);
                $id_idHolder = 'section-'.$section->id.'-sd_section_structures-'.$i.'-sd_field_values-'.$j.'-id';
                $id_nameHolder = 'sd_field_values['.$section->id.']['.$sd_section_structureK.'][id]';
                $field_value_nameHolder = 'sd_field_values['.$section->id.']['.$sd_section_structureK.'][field_value]';
                if($permission==1){
                    if((!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))){
                        $text =$text."<input id= ".$id_idHolder." name=".$id_nameHolder." value=".$sd_section_structure_detail->sd_field->sd_field_values[$j]->id." type=\"hidden\">";
                    }else{
                        $text =$text."<input id= ".$id_idHolder." name=".$id_nameHolder." value=\"\" type=\"hidden\">";
                    }
                    if($sd_section_structure_detail->is_required) $text =$text. "<i class=\"fas fa-asterisk reqField\"></i>" ;
                    $text =$text. "<input id= \"section-".$section->id."-is_required-".$sd_section_structure_detail->sd_field->id."\" value=";
                    if($sd_section_structure_detail->is_required) $text =$text. "1" ;else $text =$text. "0";
                    $text =$text. " type=\"hidden\">";
                    $text =$text. "<div id= \"section-".$section->id."-error_message-".$sd_section_structure_detail->sd_field->id."\" style=\"display:none\"></div>";
                    //  $text =$text. "<input id= \"section-".$section->id."-set_number-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][set_number]\" value=".$setNo." type=\"hidden\">";    
                    $text =$text. "<input id= \"section-".$section->id."-sd_field_id-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][sd_field_id]\" value=".$sd_section_structure_detail->sd_field->id." type=\"hidden\">";
                }
                    switch($sd_section_structure_detail->sd_field->sd_element_type->type_name){
                        case 'select':
                            $text =$text. "<select class=\"form-control\" id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."\" name=".$field_value_nameHolder.">";
                            $text =$text."<option id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."-option-null\" value=\"\" ></option>";
                            foreach($sd_section_structure_detail->sd_field->sd_field_value_look_ups as $option_no=>$option_detail){
                                $text =$text. "<option id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" value=".$option_detail['value'];
                                if($permission==2) $text =$text. " disabled";
                                if(!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])&&$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value==$option_detail['value'])
                                $text =$text. " selected=\"true\"";
                                $text =$text. ">".$option_detail['caption']."</option>";
                            };
                            $text =$text."</select>";
                            continue;
                        case 'unspecifiedDate':
                            $text =$text. "<input id=\"section-".$section->id."-field_rule-".$sd_section_structure_detail->sd_field->id."\" value=".$sd_section_structure_detail->sd_field->field_length."-".$sd_section_structure_detail->sd_field->field_type." type=\"hidden\">";
                            $text =$text. "<input class=\"form-control\" name=".$field_value_nameHolder." id=\"section-".$section->id."-unspecifieddate-".$sd_section_structure_detail->sd_field->id."\" ";
                            if($permission==2) $text =$text. " disabled ";
                            $text =$text. "value=\"";
                            (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text.$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value:$text =$text.null;
                            $text =$text. "\" type=\"hidden\">";
                            $text = $text."<div class=\"form-row\">";
                                $text = $text."<div class=\"col-sm-4\">";
                                    $text = $text."<select class=\"custom-select js-example-basic-single\" placeholder=\"Day\" id=\"unspecified-day_section-".$section->id."-unspecifieddate-".$sd_section_structure_detail->sd_field->id."\" >";
                                        $text = $text."<option value=\"00\">Day</option>";
                                        for($i=1;$i<32;$i++){
                                            $text = $text."<option value=\"".sprintf("%02d",$i)."\"";
                                            if (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])&&(substr($sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value,0,2)==sprintf("%02d",$i))) $text = $text."selected";
                                            $text = $text.">".$i."</option>";
                                        }
                                    $text = $text."</select>";
                                $text = $text."</div>";
                                $text = $text."<div class=\"col-sm-4\">";
                                    $text = $text."<select class=\"custom-select js-example-basic-single\" placeholder=\"Month\" id=\"unspecified-month_section-".$section->id."-unspecifieddate-".$sd_section_structure_detail->sd_field->id."\" >";
                                    $month_str = ['Jan-1','Feb-2','Mar-3','Apr-4','May-5','Jun-6','Jul-7','Aug-8','Sep-9','Oct-10','Nov-11','Dec-12'];
                                        $text = $text."<option value=\"00\">Month</option>";
                                        foreach($month_str as $i => $month){
                                            $text = $text."<option value=\"".sprintf("%02d",$i+1)."\"";
                                            if (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])&&(substr($sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value,2,2)==sprintf("%02d",$i+1))) $text = $text."selected";
                                            $text = $text.">".$month."</option>";
                                        }
                                    $text = $text."</select>";
                                $text = $text."</div>";
                                $text = $text."<div class=\"col-sm-4\">";
                                    $text = $text."<select class=\"custom-select js-example-basic-single yearSelect\" placeholder=\"Year\" id=\"unspecified-year_section-".$section->id."-unspecifieddate-".$sd_section_structure_detail->sd_field->id."\" >";
                                        $text = $text."<option value=\"0000\">Year</option>";
                                        for($i=1900;$i<=2050;$i++){
                                            $text = $text."<option value=\"".sprintf("%04d",$i)."\"";
                                            if (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])&&(substr($sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value,4,4)==sprintf("%02d",$i))) $text = $text."selected";
                                            $text = $text.">".$i."</option>";
                                        }
                                    $text = $text."</select>";
                                $text = $text."</div>";
                            $text = $text."</div>";
                            continue;
                        case 'text':
                            $text =$text. "<input id=\"section-".$section->id."-field_rule-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][field_rule]\" value=".$sd_section_structure_detail->sd_field->field_length."-".$sd_section_structure_detail->sd_field->field_type." type=\"hidden\">";
                            $text =$text. "<input id=\"section-".$section->id."-text-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder." type=\"text\"";
                            if($permission==2) $text =$text. " disabled ";
                             (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text."value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":$text =$text.null;
                            $text =$text. ">";
                            continue;
                        case 'radio':
                        $text =$text. "<div id=\"section-".$section->id."-radio-".$sd_section_structure_detail->sd_field->id."\">";
                            foreach($sd_section_structure_detail->sd_field->sd_field_value_look_ups as $option_no=>$option_detail){
                                $text =$text. "<div class=\"custom-control custom-radio custom-control-inline\">";
                                $text =$text. "<input class=\"custom-control-input\" id=\"section-".$section->id."-radio-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" name=".$field_value_nameHolder." type=\"radio\" value=";
                                $text =$text. $option_detail['value'];
                                if($permission==2) $text =$text. " disabled";
                                if(!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])&&$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value==$option_detail['value'])
                                $text =$text. " checked=true";
                                $text =$text. "><label class=\"custom-control-label\" for=\"section-".$section->id."-radio-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" >".$option_detail['caption']."</label>";
                                $text =$text. "</div>";
                            }
                            $text =$text. "</div>";
                            continue;
                        case 'checkbox':
                        $text =$text. "<div class=\"form-row\" id=\"section-".$section->id."-checkbox-".$sd_section_structure_detail->sd_field->id."\">";
                            $valuesSet = array();
                            $valueCount = 0;
                            if(!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])) $valuesSet = $sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value;
                            foreach($sd_section_structure_detail->sd_field->sd_field_value_look_ups as $option_no=>$option_detail){
                                $text =$text. "<div class=\"custom-control custom-radio custom-control-inline col-md-3\">";
                                $text =$text. "<input id=\"section-".$section->id."-checkbox-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" class=\"checkboxstyle\"  name=".$field_value_nameHolder." value=";
                                $text =$text. $option_detail['value'];
                                if($permission==2) $text =$text. " disabled";
                                if((!empty($valuesSet))&&(substr($valuesSet, $option_detail['value']-1,1)==1))
                                $text =$text. " checked=\"true\"";
                                $text =$text. " type=\"checkbox\" ><label class=\"form-check-label\" for=\"checkbox-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\">".$option_detail['caption']."</label>";
                                $text =$text. "</div>";
                                $valueCount ++;
                            }
                            if($permission==1){
                                $text =$text. "<input id=\"section-".$section->id."-checkbox-".$sd_section_structure_detail->sd_field->id."-".$valueCount."-final\" class=\"checkboxstyle\"  name=".$field_value_nameHolder." value=\"";
                                if (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])){
                                    $text =$text. $sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value;
                                }
                                else { for($y = 0; $y<$valueCount;$y++)$text =$text. "0";
                                }
                                $text =$text. "\" type=\"hidden\">";
                            }
                            $text =$text."</div>";
                            continue;
                        case 'textarea':
                        $text =$text. "<input id=\"section-".$section->id."-field_rule-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][field_rule]\" value=".$sd_section_structure_detail->sd_field->field_length."-".$sd_section_structure_detail->sd_field->field_type." type=\"hidden\">";
                        $text =$text. "<textarea id=\"section-".$section->id."-textarea-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder;
                            if($permission==2) $text =$text. " disabled";
                            $text =$text. " rows=".$sd_section_structure_detail->field_height.">";
                             (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text.str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value):$text =$text.null;
                            $text =$text. "</textarea>";
                            continue;
                        case 'date':
                        $text =$text. "<input id=\"section-".$section->id."-field_rule-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][field_rule]\" value=".$sd_section_structure_detail->sd_field->field_length."-".$sd_section_structure_detail->sd_field->field_type." type=\"hidden\">";
                        $text =$text. "<input  class=\"form-control\" name=".$field_value_nameHolder." id=\"section-".$section->id."-date-".$sd_section_structure_detail->sd_field->id."\" ";
                            if($permission==2) $text =$text. " disabled ";
                            $text =$text. "value=\"";
                            (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text.$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value:$text =$text.null;
                            $text =$text. "\" type=\"hidden\">";
                            $text =$text. "<input type=\"text\" class=\"form-control\" placeholder=\"mm / dd / yyyy\" id=\"specified-date-section-".$section->id."-date-".$sd_section_structure_detail->sd_field->id."\" value=\"\" >";
                            continue;
                        case 'whodra browser':
                            if($permission==1){
                                $whoddCell = $html->cell('Whodd',[$sd_section_structure_detail->sd_field->id]);
                                $text =$text. $whoddCell;
                                $text =$text. "<input readonly=\"readonly\" style=\"float:left\" id=\"section-".$section->id."-whodracode-".$sd_section_structure_detail->sd_field->id."\" class=\"col-md-5 form-control\" name=".$field_value_nameHolder." type=\"text\"";
                                (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text."value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":$text =$text.null;
                                $text =$text. " >";
                            }
                            continue;
                        case 'whodra show':
                        $text =$text. "<input id=\"section-".$section->id."-whodraname-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder." type=\"text\"";
                        (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text."value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":$text =$text.null;
                            if($permission==2) $text =$text. " disabled ";
                            $text =$text. " readonly=\"readonly\">";
                            continue;
                        case 'Meddra browser':
                            $meddraCell = $html->cell('Meddra',[$sd_section_structure_detail->sd_field->descriptor, $sd_section_structure_detail->sd_field->id]);
                            $text =$text.$meddraCell;
                            $text =$text."<input id=\"section-".$section->id."-meddraResult-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder." type=\"hidden\"";
                            (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text."value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":$text =$text.null;
                            if($permission==2) $text =$text. " disabled ";
                            $text =$text. ">";
                            continue;
                        case 'Meddra show':
                            $text =$text. "<input id=\"section-".$section->id."-meddrashow-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder." type=\"text\"";
                            (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text."value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":$text =$text.null;
                            if($permission==2) $text =$text. " disabled ";
                            $text =$text. "readonly=\"readonly\">";
                            continue;
                        case "dynamic select":
                            $text =$text. "<select class=\"form-control\" id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."\" name=".$field_value_nameHolder.">";
                            $text =$text."<option id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."-option-null\" value=\"\" ></option>";
                            foreach($dynamic_options[explode('-',$sd_section_structure_detail->sd_field->descriptor)[1]] as $option_no=>$option_detail){
                                $text =$text. "<option id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."-option-".$option_no."\" value=".$option_no;
                                if($permission==2) $text =$text. " disabled";
                                if(!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])&&$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value==$option_no)
                                $text =$text. " selected=\"true\"";
                                $text =$text. ">".$option_detail."</option>";
                            };
                            $text =$text."</select>";
                            continue;
                    }
                    $text =$text."</div>";
            $i++;
            $length_taken = $sd_section_structure_detail->field_length + $sd_section_structure_detail->field_start_at;
        }
        if($i!=0) $text =$text."</div>";
        $text =$text. "<div class=\"text-center pb-3\"><input id=\"save-btn".$section->id."-".$sectionKey."\" onclick=\"saveSection(".$section->id.",1)\" class=\"ml-3 px-5 btn btn-outline-primary panel-collapse show\" type=\"button\" style=\"display:none\" value=\"Save\"></div>";
        $text =$text. "</div>";
        
    // }
    return $text;
}

function displaySelectBar($sdSections, $setArray, $section_key){   
    $max_set_No = 0;
    foreach($sdSections->sd_section_structures as $sd_section_structureK =>$sd_section_structure_detail){
        foreach ($sd_section_structure_detail->sd_field->sd_field_values as $key_detail_field_values=>$value_detail_field_values){
            $set_array=$value_detail_field_values->set_number;
            if(explode(",",$set_array)[0]>=$max_set_No)
            $max_set_No = explode(",",$set_array)[0];
        }
    }
    $text = "";
    if($sdSections->id!="44" && $sdSections->id!="65" && $max_set_No>0){
        $text = $text."<button type=\"button\" onclick=\"setPageChange(".$sdSections['id'].",".(int)($max_set_No+1).",1)\" id=\"addbtn-".$sdSections['id']."\" class=\"btn btn-outline-primary float-right panel-collapse show\" role=\"button\" title=\"add\"><i class=\"fas fa-plus\"></i>Add</button>";
        $text = $text."<button type=\"button\" onclick=\"deleteSection(".$sdSections['id'].",1,".$section_key.")\" id=\"deletebtn-".$sdSections['id']."\" class=\"btn btn-outline-danger float-right panel-collapse show\" role=\"button\" title=\"delete\"><i class=\"fas fa-trash-alt\"></i>Delete</button>"; 
    }
    $text = $text."<input type=\"hidden\" id='setArray-".$sdSections->id."' value='";
        for($i = sizeof($setArray);$i>0;$i--){
            $text = $text.$setArray[$i-1].",";
        } 
    $text = $text."'>";
    $text = $text. "<div id=\"pagination-section-".$sdSections->id."\" class=\"DEpagination float-right panel-collapse show\">";
    $text =$text. "<ul class=\"pagination mb-0 mx-2\">";
    $text =$text.    "<li class=\"page-item\" id=\"left_set-".$sdSections->id."-sectionKey-".$section_key."-setNo-1\" onclick=\"setPageChange(".$sdSections->id.",0)\" >";
    $text =$text.    "<a class=\"page-link\" aria-label=\"Previous\">";
    $text =$text.        "<span aria-hidden=\"true\">&laquo;</span>";
    $text =$text.        "<span class=\"sr-only\">Previous</span>";
    $text =$text.    "</a>";
    $text =$text.    "</li>";
    if($max_set_No != 0){
        for($pageNo = 1; $pageNo<=$max_set_No; $pageNo++ ){
            $text =$text.    "<li class=\"page-item";
            if($pageNo == 1) $text =$text." selected-page";
            $text =$text."\" id=\"section-".$sdSections->id."-page_number-".$pageNo."\" onclick=\"setPageChange(".$sdSections->id.",".$pageNo.")\"><a class=\"page-link\">".$pageNo."</a></li>";
        }
    }else{
        $text =$text.    "<li class=\"page-item selected-page\" style=\"font-weight:bold\" id=\"section-".$sdSections->id."-page_number-1\" onclick=\"setPageChange(".$sdSections->id.",1)\"><a class=\"page-link\">1</a></li>";

    }
    $text =$text.    "<li class=\"page-item\" id=\"right_set-".$sdSections->id."-sectionKey-".$section_key."-setNo-1\" onclick=\"setPageChange(".$sdSections->id.",2)\">";
    $text =$text.    "<a class=\"page-link\" aria-label=\"Next\">";
    $text =$text.        "<span aria-hidden=\"true\">&raquo;</span>";
    $text =$text.        "<span class=\"sr-only\">Next</span>";
    $text =$text.    "</a>";
    $text =$text.    "</li>";
    $text =$text. "</ul>";
    $text =$text."</div>";
    return $text;
    
}
function displaySection($sdSections, $allsdSections, $setArray, $exsitSectionNo,$html,$permission, $dynamic_options){
    
    if(empty($exsitSectionNo)) return null;
    if(!in_array($sdSections->id,$exsitSectionNo)) return ["exsitSectionNo"=>$exsitSectionNo];
    $sectionKey = array_search($sdSections->id,$exsitSectionNo);
    $field_Text= "";
    if(!$sdSections->section_type)
        $field_Text = $field_Text.displayTitle($sdSections->id, $sdSections->section_name,$sectionKey, $permission);
    if($sdSections->is_addable){
        array_push($setArray, $sdSections->id);
        $section_key=array_search($sdSections->id,$exsitSectionNo);
        if(!empty($sdSections->sd_section_summary))
        $field_Text = $field_Text.displaySummary($sdSections, $setArray, $sdSections->section_level, $section_key,$dynamic_options);
        else {
            $field_Text = $field_Text.displaySelectBar($sdSections, $setArray, $section_key);
        }
    }
    // debug($field_Text);
    $child_Field_Text = "";
    $child_Div_Text = "";
    $child_Nav_Text = "";
    $childchild_Field_Text ="";
    $nav_Text = "<a class=\"nav-item";
    if($sdSections->display_order ==10) $nav_Text = $nav_Text ." active";
    $nav_Text = $nav_Text." nav-link\" id=\"nav-".$sdSections->id."-tab\" data-toggle=\"tab\" href=\"#secdiff-".$sdSections->id."\" role=\"tab\" aria-controls=\"secdiff-".$sdSections->id."\" aria-selected=\"true\">";
    $nav_Text = $nav_Text.$sdSections->section_name;
    $nav_Text = $nav_Text."</a>";
    if(!empty($sdSections['sd_section_structures'])) 
        $field_Text = $field_Text.displaySingleSection($sdSections, $setArray, $sectionKey, $html, $permission, $dynamic_options);//display single section's fields, iterater sectioin's strucutures
    if(empty($sdSections['child_section'])){
        $exsitSectionNo[$sectionKey]= null;
        $result = array("field_Text"=>$field_Text,
                        "nav_Text" => $nav_Text,
                        "child_Field_Text"=>$child_Field_Text,
                        "nav_Text" => $nav_Text,
                        "child_Div_Text" => $child_Div_Text,
                        "exsitSectionNo"=>$exsitSectionNo);//add section label in "field";
        return $result;
    }
    $i = 0;
    $child_sections = explode(",",$sdSections['child_section']);
    foreach($child_sections as $key => $child_section){
        // $i++;if($i == 4) die();
        if(empty($exsitSectionNo)) break;
        $sectionKey = array_search($child_section,$exsitSectionNo);
        if($sectionKey === FALSE) continue;
        $result = displaySection($allsdSections[$sectionKey], $allsdSections, $setArray, $exsitSectionNo, $html, $permission, $dynamic_options);
        if($allsdSections[$sectionKey]['section_type']){
            $child_Nav_Text = $child_Nav_Text.$result['nav_Text'];
            $div_front ="";
            $div_front =  $div_front."<div class=\"tab-pane";
            if($allsdSections[$sectionKey]->display_order ==10)$div_front = $div_front." show active";
            $div_front =$div_front." fade\" aria-labelledby=\"nav-".$allsdSections[$sectionKey]->id."-tab\" role=\"tabpanel\" class=\"secdiff\" id=\"secdiff-".$allsdSections[$sectionKey]->id."\">";
            $child_Div_Text = $child_Div_Text.$div_front.$result['field_Text']."<div>".$result['child_Field_Text']."</div>".$result['child_Div_Text']."</div>";  //add label and div in "div"

        }else{
            $child_Field_Text = $child_Field_Text."<div class=\"nested-section layer-".$sdSections->section_level."\">".$result['field_Text'].$result['child_Field_Text']."</div>";//add section label here TODO YULI
        }    
        $exsitSectionNo = $result['exsitSectionNo'];
    }
    $nav_Text = "<a class=\"nav-item";
    if($sdSections->display_order ==10) $nav_Text = $nav_Text ." active";
    $nav_Text = $nav_Text." nav-link\" id=\"nav-".$sdSections->id."-tab\" data-toggle=\"tab\" href=\"#secdiff-".$sdSections->id."\" role=\"tab\" aria-controls=\"secdiff-".$sdSections->id."\" aria-selected=\"true\">";
    $nav_Text = $nav_Text.$sdSections->section_name;
    $nav_Text = $nav_Text."</a>";
    // $nav_Text = "".$sdSections['id']."</div></nav>"; //add page style here
    if($child_Nav_Text!="") {
        $child_Nav_Text = "<nav class=\"my-3\"><div class=\"nav nav-tabs\" id=\"nav-tab\" role=\"tablist\">".$child_Nav_Text."</div></nav>"; //add navigation bar style here
        $child_Div_Text = "<div class=\"folder\"  id=\"folder-".$sdSections->id."\">".$child_Nav_Text."<div class=\"tab-content\" id=\"nav-tabContent\">".$child_Div_Text."</div></div>"; //pagination style
    }
    $exsitSectionNo[$sectionKey]= null;
    $result = [
        "child_Field_Text"=>$child_Field_Text,
        "field_Text"=>$field_Text,
        "nav_Text" => $nav_Text,
        "child_Div_Text" => $child_Div_Text,
        "exsitSectionNo"=>$exsitSectionNo
    ];
    return $result;
}
?>
