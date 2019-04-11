<?php
debug($sdSections);
foreach($sdSections as $sections){
    displaySection($sections, $sdSections);
}
function displaySection($sdSections, $allsdSections){
    $field_Text= "";
    debug($sdSections);
    $child_Field_Text = "";
    $child_Div_Text = "";
    $child_Nav_Text = "";
    $childchild_Field_Text ="";
    if(!empty($sdSections['field'])){
        $field_Text = $sdSections['field'];//display single section's fields, iterater sectioin's strucutures
    }
    if(empty($sdSections['child_section'])){
        $result = array('field_Text'=>"(field)".$field_Text."(/field)");//add section label in "field";
        print_r($sdSections['id']);
        print_r($result);
        return $result;
    }
    foreach($sdSections['child_section'] as $key => $child_section){
        $result = displaySection($allsdSections[$child_section], $allsdSections);
        if(!empty($result['nav_Text'])){
            $child_Nav_Text = $child_Nav_Text.$result['nav_Text'];
            $child_Div_Text = $child_Div_Text."(div)".$result['field_Text'].$result['child_Field_Text'].$result['child_Div_Text']."(/div)";  //add label and div in "div"
        }else{
            $child_Field_Text = $child_Field_Text.$result['field_Text'];
        }
    }
    $nav_Text = "(a)".$sdSections['id']."(a)"; //add page style here 
    if($child_Nav_Text!="") {
        $child_Nav_Text = "(nav)".$child_Nav_Text."(/nav)"; //add navigation bar style here 
        $child_Div_Text = $child_Nav_Text.$child_Div_Text; 
    }
    $result = [
        "child_Field_Text"=>$child_Field_Text,
        "field_Text"=>$field_Text,
        "nav_Text" => $nav_Text,
        "child_Div_Text" => $child_Div_Text
    ];
    print_r($sdSections['id']);
    print_r($result);
    return $result;
}
    //output the structure
?>