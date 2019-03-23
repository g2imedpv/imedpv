<?php
if($newQueryCount)
// echo  "<a class=\"\" href=\"/sd-queries/queries/unread\"><i class=\"far fa-envelope\"></i><b>You have ".$newQueryCount." New Queries</b></a>";
// else echo "<a class=\"\" href=\"/sd-queries/queries/\"><i class=\"far fa-envelope\"></i>My Queries</a>";
echo "<a role=\"button\" class=\"btn btn-light text-muted querybox d-flex align-items-center\" href=\"/sd-queries/queries/unread\" data-toggle=\"tooltip\" data-html=\"true\" title=\"You have ".$newQueryCount." New Queries\"><i class=\"fas fa-envelope\"></i><span class=\"badge badge-light ml-2\">$newQueryCount</span> </a>";
else echo "<a role=\"button\" class=\"btn btn-light text-muted querybox d-flex align-items-center\" href=\"/sd-queries/queries/\" data-toggle=\"tooltip\" data-html=\"true\" title=\"You have 0 New Queries\"><i class=\"fas fa-envelope\"></i> </a>";
?>