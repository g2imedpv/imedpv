<!--     Left Navigation Bar    -->
<div class="btn-group-vertical" id="sidenav" role="group" aria-label="Button group with nested dropdown">
    <ul class="nav nav-pills nav-fill">
    <?php
    foreach($sdTabs as $sdTabK =>$sdTabV){
        $multiSec = 0;
        // if(sizeof($sdTabV->sd_sections)>=0) $multiSec=1;
        echo "<li class=\"nav-item ";
        echo ($sdTabV->id==$tabid)?"selval ":null;
        // echo ($multiSec)?"dropright ":null;
        echo "mb-2 bg-outline-primary text-white border border-primary rounded\">";
        echo "<a class=\"nav-link loadingAni";
        // echo ($multiSec)?"dropdown-toggle\" aria-haspopup=\"true\" aria-expanded=\"false\" data-toggle=\"dropdown ":null;
        echo "\" href=\"/sd-tabs/showdetails/".$caseNo."/".$version."/".$sdTabV->id;
        if($readonly) echo "?readonly=1";
        echo "\">".__($sdTabV->tab_name)."</a>";
        // if($multiSec){
        //     echo "<div class=\"dropdown-menu\">";
        //     foreach($sdTabV->sd_sections as $sectionK => $sectionV){
        //         if($sectionV->section_level ==1){
        //             echo"<a class=\"dropdown-item\" href=\"/sd-tabs/showdetails/".$caseNo."/".$version."/".$sdTabV->id;
        //             if($readonly) echo "?readonly=1";
        //             echo "#secdiff-".$sectionV->id."\">";
        //             echo $sectionV->section_name;
        //             echo"</a>";
        //         }
        //     };
        //     echo "</div>";
        // }
        echo"</li>";
    } ?>
    </ul>
</div>