<!--<title>CIOMS</title>-->

<!-- For local Bootstrap/CSS link -->
<?= $this->Html->css('cimos.css') ?>
<?= $this->Html->css('bootstrap/bootstrap-grid.min.css') ?>
<?= $this->Html->css('bootstrap/bootstrap-reboot.min.css') ?>
<?= $this->Html->css('bootstrap/bootstrap.min.css') ?>

<body>
    <div class="text-center my-3 printBTN">
        <input class="btn btn-primary w-25" type="button" value="Print" onClick="window.print()">
    </div>
    <div class="page-header" style="text-align: right">
        <span></span><span >Mfr.Control Number:<?php echo $fileName?></span><br/>
    </div>
    <div class="page-footer">
        Copyright &copy; <?php echo date("Y");?> G2-MDS. All rights reserved.
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
                <th class="tg-0pky text-center align-middle title" rowspan="3"><b>不良事件报告</b> </th>
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
        <h3 class="text-center mt-3">I. 不良事件信息</h3>
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
            <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 1. 患者姓名缩写 </p> <p class="text-center textsize"><?php echo $patientInitial ?></p> </th>
            <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 1a. 国家 </p> <p class="text-center align-bottom textsize"> <?php echo $country ?> </p></th>
            <th class="tg-0lax" colspan="3"><p class="text-center titlesize"> 2. 出生日期 </p></th>
            <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 2a. 年龄 </p><p class="text-center textsize"><?php echo $age."</br>$ageUnit"?></p></th>
            <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 3. 性别 </p><p class="text-center textsize"><?php echo $sex ?></p></th>
            <th class="tg-0lax" colspan="3"><p class="text-center titlesize"> 4-6. 发生时间 </p></th>
            <th class="tg-0lax" rowspan="3">
                <p class="text-center titlesize"> 8-12. 严重性 </p>
                <input class="my-2" type="checkbox" name="vehicle1" value="1"<?php echo $patientDied?>>  导致死亡<br>
                <input class="my-2" type="checkbox" name="vehicle2" value="2" <?php echo $hospitalization?>> 导致住院/住院时间延长<br>
                <input class="my-2" type="checkbox" name="vehicle3" value="3" <?php echo $disability?>> 残疾/功能丧失<br>
                <input class="my-2" type="checkbox" name="vehicle3" value="4" <?php echo $lifeThreatening?>> 危及生命<br>
                <input class="my-2" type="checkbox" name="vehicle3" value="5" <?php echo $congenital?>> 先天性异常或出生缺陷<br>
                <input class="my-2" type="checkbox" name="vehicle3" value="6" <?php echo $otherSerious?>> 其他重要医学事件<br>
            </th>
        </tr>
        <tr style="height:15px;">
            <td class="tg-0lax"><p class="text-center textsize">日</p> <p class="text-center textsize"><?php echo substr($birth,0,2) ?></p></td>
            <td class="tg-0lax"><p class="text-center textsize">月</p> <p class="text-center textsize"><?php echo $birthMonth ?></p></td>
            <td class="tg-0lax"><p class="text-center textsize">年</p> <p class="text-center textsize"><?php echo substr($birth,4,4) ?></p></td>
            <td class="tg-0lax"><p class="text-center textsize">日</p> <p class="text-center textsize"><?php echo substr($reaction,0,2) ?></p></td>
            <td class="tg-0lax"><p class="text-center textsize">月</p> <p class="text-center textsize"><?php echo $reactionMonth ?></p></td>
            <td class="tg-0lax"><p class="text-center textsize">年</p> <p class="text-center textsize"><?php echo substr($reaction,4,4) ?></p></td>
        </tr>
        <tr style="height:130px;">
            <td class="tg-0lax" colspan="10">
                <p class="text-left titlesize"> 7 + 13. 事件描述 (包括相关检查) </p>
                <p class="text-left textsize"><?php echo substr($describe,0,1000)?></p>
            </td>
        </tr>
        </table>

        <!-- II. SUSPECT DRUG(S) INFORMATION -->
        <h3 class="text-center mt-3">II. 怀疑药物信息</h3>
        <table class="tg mx-auto" style="width: 95%; height:150px;">
            <colgroup>
                <col style="width: 50%">
                <col style="width: 25%">
                <col style="width: 25%">
            </colgroup>
            <tr class="SectionTwo">
                <th class="tg-0pky" colspan="2">
                    <p class="text-left titlesize"> 14. 怀疑药物 (包括通用名) </p>
                    <p class="text-left textsize" style="margin-bottom: 8px;margin-top: 10px;"><?php echo $suspecttitle?></p>
                    <p class="text-left textsize suspect" ><?php echo $suspectProducts?></p>
                </th>
                <th class="tg-0pky">
                    <p class="text-center titlesize" style="padding:5px;line-height: 20px;"> 20. 停药或减量后，反应是否消失或减轻?</p>
                    <div class="d-flex justify-content-around" style="font-size:12px;">
                        <input type="checkbox" name="vehicle1" value="Bike" <?php echo $DeYes?> >是<br>
                        <input type="checkbox" name="vehicle2" value="Car" <?php echo $DeNo?>>否<br>
                        <input type="checkbox" name="vehicle3" value="Boat" <?php echo $DeUnkown?>>不适用<br>
                    </div>
                </th>
            </tr>
            <tr style="height:15px;" class="SectionTwo">
                <td class="tg-0pky">
                    <p class="text-left titlesize"> 15. 剂量 </p>
                    <p class="text-left textsize suspect"><?php echo $dailyDose?></p>

                </td>
                <td class="tg-0pky">
                    <p class=" titlesize"> 16. 给药途径 </p>
                    <p class="text-left textsize suspect"><?php echo $route?></p>
                </td>
                <td class="tg-0pky" rowspan="2">
                    <p class="text-center titlesize" style="padding:3px;line-height: 20px;"> 21. 再次给药后是否再次发生反应？</p>
                    <div class="d-flex justify-content-around" style="font-size:12px;">
                        <input type="checkbox" name="vehicle1" value="Bike" <?php echo $ReYes?>>是<br>
                        <input type="checkbox" name="vehicle2" value="Car" <?php echo $ReNo?>>否<br>
                        <input type="checkbox" name="vehicle3" value="Boat" <?php echo $ReUnkown?>>不适用<br>
                    </div>
                </td>
            </tr>
            <tr style="height:20px;" class="SectionTwo">
                <td class="tg-0lax" colspan="2">
                    <p class=" titlesize"> 17. 适应症 </p>
                    <p class="text-left textsize suspect"><?php echo $indication?></p>

                </td>
            </tr>
            <tr class="SectionTwo">
                <td class="tg-0lax">
                    <p class=" titlesize"> 18. 给药日期 </p>
                    <p class="text-left textsize suspect"><?php echo $therapy?></p>

                </td>
                <td class="tg-0lax" colspan="2">
                    <p class=" titlesize"> 19. 治疗持续时间</p>
                    <p class="text-left textsize suspect"><?php echo $duration?></p>
                </td>
            </tr>
        </table>

        <!-- III. CONCOMITANT DRUG(S) AND HISTORY -->
        <h3 class="text-center mt-3">III. 合并用药和病史</h3>
        <table class="tg mx-auto" style="width: 95%; height:200px;">
            <colgroup>
                <col>
            </colgroup>
            <tr>
                <th class="tg-0pky">
                    <p class="text-left titlesize"> 22. 合并用药信息 </p>
                    <p class="text-left textsize"><?php echo $concomitanttitle?></p>
                    <p class="text-left textsize suspect"><?php echo $concomitantProducts?></p>
                </th>
            </tr>
            <tr>
                <td class="tg-0lax">
                    <p class="text-left titlesize"> 23. 其他相关病史 </p>
                    <p class="text-left textsize "> <?PHP echo $relevanttitle?> </p>
                    <p class="text-left textsize suspect"><?php echo substr($relevant,0,200)?> </p>
                </td>
            </tr>
        </table>

        <!-- IV. MANUFACTURER INFORMATION -->
        <h3 class="text-center mt-3">IV. 企业信息</h3>
        <table class="tg mx-auto" style="width: 95%;">
            <colgroup>
            <col style="width: 25%">
            <col style="width: 25%">
            <col style="width: 50%">
            </colgroup>
            <tr>
                <th class="tg-0pky" colspan="2">
                    <p class=" titlesize"> 24a. 企业信息 </p>
                    <p class=" textsize"> <?php echo $caseSource?></p>
                </th>
                <th class="tg-0lax" rowspan="4">
                    <p class=" textsize">   </p>
                </th>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax">
                    <p class=" titlesize"> 24b. 病例编号 </p>
                    <p class=" textsize"> <?php echo $otherCaseIndentifier ?> </p>
                </td>
            </tr>
            <tr>
                <td class="tg-0lax">
                    <p class=" titlesize"> 24c.收到本报告最新信息的日期 </p>
                    <p class=" textsize"> <?php echo $receiptDate ?></p>
                </td>
                <td class="tg-0lax">
                    <p class=" titlesize"> 24d. 报告来源 </p>
                    <div class="d-flex justify-content-left" style="font-size:12px;">
                        <input class="mx-1" type="checkbox" name="option1" value="1" <?php echo $study?>>研究
                        <input class="mx-1" type="checkbox" name="option2" value="2" <?php echo $literature?>>文献 <br>
                        <input class="mx-1" type="checkbox" name="option3" value="3" <?php echo $healthProfessional?>>医疗保健专业人士
                        <input class="mx-1" type="checkbox" name="option4" value="4" <?php echo $healthProfessional?>>其他
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tg-0lax">
                    <p class=" titlesize"> 报告日期 </p>
                    <p class=" textsize"> <?php echo date('d-M-Y')?> </p>
                </td>
                <td class="tg-0lax">
                    <p class=" titlesize"> 25a. 报告类型 </p>
                    <div class="d-flex justify-content-around" style="font-size:12px;">
                        <input type="checkbox" name="vehicle1" value="Bike"  <?php echo $initial?>>首次
                        <input type="checkbox" name="vehicle2" value="Car"  <?php echo $followup?>>随访
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <!-- Page Two -->
    <?php if(substr($describe,1000)!=null||substr($relevant,200)!=null){
        echo "<div class=\"tg m-5 pageBreak\" >";
        echo " <h3 class=\"tg-0pky text-center align-middle title pagetwo\" ><b>附加信息</b></h3>";
        echo "  <p class=\"text-left titlesize pagetwo\"> 7 + 13.  事件描述（续） </p>";
        echo "  <p class=\"textsize pagetwo\">";echo substr($describe,1000); echo "</p>";
        echo "  <p class=\"text-left titlesize pagetwo\"> 23.其他相关病史（续） </p>";
        echo "  <p class=\"textsize pagetwo\">";echo substr($relevant,200); echo "</p>";
        echo "</div>";
        echo "<!--place holder for the fixed-position footer-->";
        echo "<div class=\"page-footer-space\"></div> ";
}?>
</body>