<!--<title>CIOMS</title>-->

<!-- For local Bootstrap/CSS link -->
<?= $this->Html->css('cimos.css') ?>
<?= $this->Html->css('bootstrap/bootstrap-grid.min.css') ?>
<?= $this->Html->css('bootstrap/bootstrap-reboot.min.css') ?>
<?= $this->Html->css('bootstrap/bootstrap.min.css') ?>

<body>
    <div class="page-header" style="text-align: right">
        <span></span><span >Mfr.Control Number:<?php echo $fileName?></span><br/>
    </div>
    <div class="page-footer">
        I'm The Footer
    </div>
    <!--place holder for the fixed-position header-->
    <div class="page-header-space"></div>
    <div class="pageOne">
        <!-- TITLE -->
        <table class="tg mx-auto" style="table-layout: fixed;  width: 95%; height:120px;">
            <colgroup>
            <col style="width: 467px">
            <col style="width: 129px">
            <col style="width: 21px">
            <col style="width: 21px">
            <col style="width: 21px">
            <col style="width: 21px">
            <col style="width: 21px">
            <col style="width: 21px">
            <col style="width: 21px">
            <col style="width: 20px">
            <col style="width: 21px">
            <col style="width: 20px">
            <col style="width: 20px">
            <col style="width: 20px">
            <col style="width: 20px">
            <col style="width: 20px">
            </colgroup>
            <tr>
                <th class="tg-0pky text-center align-middle title" rowspan="3"><b>SUSPECT ADVERSE REACTION REPORT</b> </th>
                <th class="tg-0pky" colspan="15"></th>
            </tr>
            <tr>
                <td class="tg-0pky" colspan="15"></td>
            </tr>
            <tr>
                <td class="tg-0pky"></td>
                <td class="tg-0pky"></td>
                <td class="tg-0pky"></td>
                <td class="tg-0pky"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
            </tr>
        </table>

        <!-- I. REACTION INFORMATION -->
        <h3 class="text-center mt-3">I. REACTION INFORMATION</h3>
        <table class="tg mx-auto" style="width: 95%;">
        <colgroup>
        <col style="width: 150px">
        <col style="width: 150px">
        <col style="width: 60px">
        <col style="width: 60px">
        <col style="width: 60px">
        <col style="width: 70px">
        <col style="width: 70px">
        <col style="width: 60px">
        <col style="width: 60px">
        <col style="width: 60px">
        <col style="width: 150px">
        </colgroup>
        <tr style="height:5px;">
            <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 1. PATIENT INITIALS </p> <p class="text-center textsize">(first, last)</p> <p class="text-center textsize"><?php echo $patientInitial ?></p> </th>
            <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 1a. COUNTRY </p> <p class="text-center align-bottom textsize"> <?php echo $country ?> </p></th>
            <th class="tg-0lax" colspan="3"><p class="text-center titlesize"> 2. DATE OF BIRTH </p></th>
            <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 2a. AGE Years </p><p class="text-center textsize"><?php echo $age."</br>$ageUnit"?></p></th>
            <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 3. SEX </p><p class="text-center textsize"><?php echo $sex ?></p></th>
            <th class="tg-0lax" colspan="3"><p class="text-center titlesize"> 4-6. REACTION ONSET </p></th>
            <th class="tg-0lax" rowspan="3">
                <p class="text-center titlesize"> 8-12. CHECK ALL APPROPRIATE TO ADVERSE REACTION </p>
                <input class="my-2" type="checkbox" name="vehicle1" value="Bike" <?php echo $patientDied?>>  PATIENT DIED<br>
                <input class="my-2" type="checkbox" name="vehicle2" value="Car" <?php echo $hospitalization?>> INVOLVED OR PROLONGED INPATIENT HOSPITALISATION<br>
                <input class="my-2" type="checkbox" name="vehicle3" value="Boat" <?php echo $disability?>> INVOLVED PERSISTENCE OR SIGNIFICANT DISABILITY OR INCAPACITY<br>
                <input class="my-2" type="checkbox" name="vehicle3" value="Boat" <?php echo $lifeThreatening?>> LIFE THREATENING<br>
                <input class="my-2" type="checkbox" name="vehicle3" value="Boat" <?php echo $congenital?>> CONGENITAL ANOMALY<br>
                <input class="my-2" type="checkbox" name="vehicle3" value="Boat" <?php echo $otherSerious?>> OTHER<br>
            </th>
        </tr>
        <tr style="height:15px;">
            <td class="tg-0lax"><p class="text-center textsize">Day</p> <p class="text-center textsize"><?php echo substr($birth,0,2) ?></p></td>
            <td class="tg-0lax"><p class="text-center textsize">Month</p> <p class="text-center textsize"><?php echo $birthMonth ?></p></td>
            <td class="tg-0lax"><p class="text-center textsize">Year</p> <p class="text-center textsize"><?php echo substr($birth,4,4) ?></p></td>
            <td class="tg-0lax"><p class="text-center textsize">Day</p> <p class="text-center textsize"><?php echo substr($reaction,0,2) ?></p></td>
            <td class="tg-0lax"><p class="text-center textsize">Month</p> <p class="text-center textsize"><?php echo $reactionMonth ?></p></td>
            <td class="tg-0lax"><p class="text-center textsize">Year</p> <p class="text-center textsize"><?php echo substr($reaction,4,4) ?></p></td>
        </tr>
        <tr style="height:130px;">
            <td class="tg-0lax" colspan="10">
                <p class="text-left titlesize"> 7 + 13. DESCRIBE REACTION(S) (including relevant tests/lab data) </p>
                <p class="text-left textsize"><?php echo substr($describe,0,1000)?></p>
            </td>
        </tr>
        </table>

        <!-- II. SUSPECT DRUG(S) INFORMATION -->
        <h3 class="text-center mt-3">II. SUSPECT DRUG(S) INFORMATION</h3>
        <table class="tg mx-auto" style="width: 95%; height:150px;">
            <colgroup>
                <col style="width: 50%">
                <col style="width: 25%">
                <col style="width: 25%">
            </colgroup>
            <tr class="SectionTwo">
                <th class="tg-0pky" colspan="2">
                    <p class="text-left titlesize"> 14. SUSPECT DRUG(S) (include generic name) </p>
                    <p class="text-left textsize" style="margin-bottom: 8px;margin-top: 10px;"><?php echo $suspecttitle?></p>
                    <p class="text-left textsize suspect" ><?php echo $suspectProducts?></p>
                </th>
                <th class="tg-0pky">
                    <p class="text-center titlesize" style="padding:5px;line-height: 20px;"> 20. DID REACTION ABATE AFTER STOPPING DRUG?</p>
                    <div class="d-flex justify-content-around" style="font-size:12px;">
                        <input type="checkbox" name="vehicle1" value="Bike" <?php echo $DeYes?> >Yes<br>
                        <input type="checkbox" name="vehicle2" value="Car" <?php echo $DeNo?>>No<br>
                        <input type="checkbox" name="vehicle3" value="Boat" <?php echo $DeUnkown?>>N/A<br>
                    </div>
                </th>
            </tr>
            <tr style="height:15px;" class="SectionTwo">
                <td class="tg-0pky">
                    <p class="text-left titlesize"> 15. DAILY DOSE(S) </p>
                    <p class="text-left textsize suspect"><?php echo $dailyDose?></p>
                    
                </td>
                <td class="tg-0pky">
                    <p class=" titlesize"> 16. ROUTE(S) OF ADMINISTRATION </p>
                    <p class="text-left textsize suspect"><?php echo $route?></p>
                </td>
                <td class="tg-0pky" rowspan="2">
                    <p class="text-center titlesize" style="padding:3px;line-height: 20px;"> 21. DID REACTION REAPPEAR AFTER REINTRODUCTION?</p>
                    <div class="d-flex justify-content-around" style="font-size:12px;">
                        <input type="checkbox" name="vehicle1" value="Bike" <?php echo $ReYes?>>Yes<br>
                        <input type="checkbox" name="vehicle2" value="Car" <?php echo $ReNo?>>No<br>
                        <input type="checkbox" name="vehicle3" value="Boat" <?php echo $ReUnkown?>>N/A<br>
                    </div>
                </td>
            </tr>
            <tr style="height:20px;" class="SectionTwo">
                <td class="tg-0lax" colspan="2">
                    <p class=" titlesize"> 17. INDICATION(S) FOR USE </p>
                    <p class="text-left textsize suspect"><?php echo $indication?></p>
                    
                </td>
            </tr>
            <tr class="SectionTwo">
                <td class="tg-0lax">
                    <p class=" titlesize"> 18. THERAPY DATES (from/to) </p>
                    <p class="text-left textsize suspect"><?php echo $therapy?></p>
                    
                </td>
                <td class="tg-0lax" colspan="2">
                    <p class=" titlesize"> 19. THERAPY  DURATION </p>
                    <p class="text-left textsize suspect"><?php echo $duration?></p>
                </td>
            </tr>
        </table>

        <!-- III. CONCOMITANT DRUG(S) AND HISTORY -->
        <h3 class="text-center mt-3">III. CONCOMITANT DRUG(S) AND HISTORY</h3>
        <table class="tg mx-auto" style="width: 95%; height:200px;">
            <colgroup>
                <col>
            </colgroup>
            <tr>
                <th class="tg-0pky">
                    <p class="text-left titlesize"> 22. CONCOMITANT DRUG(S) AND DATES OF ADMINISTRATION (exclude those used to treat reaction) </p>
                    <p class="text-left textsize"><?php echo $concomitanttitle?></p>
                    <p class="text-left textsize suspect"><?php echo $concomitantProducts?></p>
                </th>
            </tr>
            <tr>
                <td class="tg-0lax">
                    <p class="text-left titlesize"> 23. OTHER RELEVANT HISTORY (e.g. diagnostics, allergics, pregnancy with last month of period, etc.) </p>
                    <p class="text-left textsize "> <?PHP echo $relevanttitle?> </p>
                    <p class="text-left textsize suspect"><?php echo substr($relevant,0,200)?> </p>
                </td>
            </tr>
        </table>

        <!-- IV. MANUFACTURER INFORMATION -->
        <h3 class="text-center mt-3">IV. MANUFACTURER INFORMATION</h3>
        <table class="tg mx-auto" style="width: 95%;">
            <colgroup>
            <col style="width: 25%">
            <col style="width: 25%">
            <col style="width: 50%">
            </colgroup>
            <tr>
                <th class="tg-0pky" colspan="2">
                    <p class=" titlesize"> 24a. NAME AND ADDRESS OF MANUFACTURER </p>
                    <p class=" textsize"> <?php echo $caseSource?></p>
                </th>
                <th class="tg-0lax" rowspan="4">
                    <p class=" textsize">   </p>
                </th>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">
                    <p class=" titlesize"> 24b. MFR CONTROL NO. </p>
                    <p class=" textsize"> <?php echo $otherCaseIndentifier ?> </p>
                </td>
            </tr>
            <tr>
                <td class="tg-0lax">
                    <p class=" titlesize"> 24c. DATE RECEIVED BY MANUFACTURER </p>
                    <p class=" textsize"> <?php echo $receiptDate ?></p>
                </td>
                <td class="tg-0lax">
                    <p class=" titlesize"> 24d. REPORT SOURCE </p>
                    <div class="d-flex justify-content-left" style="font-size:12px;">
                        <input class="mx-1" type="checkbox" name="vehicle1" value="Bike" <?php echo $study?>>STUDY
                        <input class="mx-1" type="checkbox" name="vehicle2" value="Car" <?php echo $literature?>>LITERATURE <br>
                        <input class="mx-1" type="checkbox" name="vehicle3" value="Boat" <?php echo $healthProfessional?>>HEALTH PROFESSIONAL
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tg-0lax">
                    <p class=" titlesize"> DATE OF THIS REPORT </p>
                    <p class=" textsize"> <?php echo date('d-M-Y')?> </p>
                </td>
                <td class="tg-0lax">
                    <p class=" titlesize"> 25a. REPORT TYPE </p>
                    <div class="d-flex justify-content-around" style="font-size:12px;">
                        <input type="checkbox" name="vehicle1" value="Bike"  <?php echo $initial?>>INITIAL
                        <input type="checkbox" name="vehicle2" value="Car"  <?php echo $followup?>>FOLLOW UP
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <!-- Page Two -->
    <?php if(substr($describe,1000)!=null||substr($relevant,200)!=null){
        echo "<div class=\"tg m-5 pageBreak\" >";
        echo " <h3 class=\"tg-0pky text-center align-middle title pagetwo\" ><b>ADDITIONAL INFORMATION</b></h3>";
        echo "  <p class=\"text-left titlesize pagetwo\"> 7 + 13. DESCRIBE REACTION(S) continued </p>";
        echo "  <p class=\"textsize pagetwo\">";echo substr($describe,1000); echo "</p>";
        echo "  <p class=\"text-left titlesize pagetwo\"> 23.OTHER RELEVANT HISTORY continued </p>";
        echo "  <p class=\"textsize pagetwo\">";echo substr($relevant,200); echo "</p>";
        echo "</div>";
        echo "<!--place holder for the fixed-position footer-->";
        echo "<div class=\"page-footer-space\"></div> ";
}?>
</body>