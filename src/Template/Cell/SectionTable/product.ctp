<div class="card mt-1 mb-5">
        <div class="card-header">
            <h3 class="d-inline"><?php echo __("Product List");?></h3>
            <a class="btn btn-outline-primary float-right" href="#" role="button" title="add"><i class="fas fa-plus"></i> Add</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead>
                    <tr class="table-secondary">
                    <?php
                    echo "<th scope=\"col\">".$sdFields[2]."</th>" ;
                    echo "<th scope=\"col\">".$sdFields[0]."</th>" ;
                    echo "<th scope=\"col\">".$sdFields[1]."</th>" ;
                    echo "<th scope=\"col\">".$sdFields[3]."</th>"
                    ?>
                        <th scope="col">action</th>
                    </tr>
                </thead>
                    <?php
                    foreach($sourceFields as $setNo => $setValue){
                        echo "<tr>";
                        echo "<td scope=\"row\">";
                        echo (!empty($setValue['282']))?$sdFieldLookUps[1][$setValue['282']]:null;
                        echo "</td>";
                        echo "<td scope=\"col\">";
                        echo (!empty($setValue['175']))?$sdFieldLookUps[0][$setValue['175']]:null;
                        echo "</td>";
                        echo "<td scope=\"col\">";
                        echo (!empty($setValue['176']))?$setValue['176']:null;
                        echo "</td>";
                        echo "<td scope=\"col\">";
                        echo (!empty($setValue['285']))?$setValue['285']:null;
                        echo "</td>";
                        echo "<td scope=\"col\">";
                        echo "<button class=\"btn btn-outline-danger\" onclick=\"setPageChange(55,".$setNo.")\" role=\"button\" title=\"show\"><i class=\"fas fa-trash-alt\"></i></button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
</div>