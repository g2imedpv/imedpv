<?php
if($newQueryCount)

echo    "<a role=\"button\" class=\"btn\" href=\"/sd-queries/queries/unread\" title=\"You have ".$newQueryCount." New Queries\">
            <i class=\"far fa-bell fa-2x position-relative\">
            <span class=\"rounded-circle bg-danger mailIconText\">$newQueryCount</span>
            </i>
        </a>";

else echo   "<a role=\"button\" class=\"btn\" href=\"/sd-queries/queries/\" title=\"You have 0 New Queries\">
            <i class=\"far fa-bell fa-2x position-relative\"></i>
            </a>";


// echo    "<a role=\"button\" class=\"btn btn-light text-muted querybox d-flex align-items-center\"
//             href=\"/sd-queries/queries/unread\" data-toggle=\"tooltip\" data-html=\"true\"
//             title=\"You have ".$newQueryCount." New Queries\">
//         <i class=\"fas fa-envelope\"></i>
//         <span class=\"badge badge-light ml-2\">$newQueryCount</span>
//         </a>";

// else echo
//         "<a role=\"button\" class=\"btn btn-light text-muted querybox d-flex align-items-center\"
//             href=\"/sd-queries/queries/\" data-toggle=\"tooltip\" data-html=\"true\"
//             title=\"You have 0 New Queries\">
//         <i class=\"fas fa-envelope\"></i>
//         </a>";

?>
