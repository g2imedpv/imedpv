<title>CIOMS</title>

<!-- For local Bootstrap/CSS link -->
<?= $this->Html->css('cimos.css') ?>
<?= $this->Html->css('bootstrap/bootstrap-grid.min.css') ?>
<?= $this->Html->css('bootstrap/bootstrap-reboot.min.css') ?>
<?= $this->Html->css('bootstrap/bootstrap.min.css') ?>

<body>
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
        <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 1. PATIENT INITIALS </p> <p class="text-center textsize">(first, last)</p> <p class="text-center textsize">01-9026-019</p> </th>
        <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 1a. COUNTRY </p> <p class="text-center align-bottom textsize"> USA </p></th>
        <th class="tg-0lax" colspan="3"><p class="text-center titlesize"> 2. DATE OF BIRTH </p></th>
        <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 2a. AGE Years </p><p class="text-center textsize">57</p></th>
        <th class="tg-0lax" rowspan="2"><p class="text-center titlesize"> 3. SEX </p><p class="text-center textsize">M</p></th>
        <th class="tg-0lax" colspan="3"><p class="text-center titlesize"> 4-6. REACTION ONSET </p></th>
        <th class="tg-0lax" rowspan="3">
            <p class="text-center titlesize"> 8-12. CHECK ALL APPROPRIATE TO ADVERSE REACTION </p>
            <input class="my-2" type="checkbox" name="vehicle1" value="Bike">  PATIENT DIED<br>
            <input class="my-2" type="checkbox" name="vehicle2" value="Car"> INVOLVED OR PROLONGED INPATIENT HOSPITALISATION<br>
            <input class="my-2" type="checkbox" name="vehicle3" value="Boat"> INVOLVED PERSISTENCE OR SIGNIFICANT DISABILITY OR INCAPACITY<br>
            <input class="my-2" type="checkbox" name="vehicle3" value="Boat"> LIFE THREATENING<br>
        </th>
    </tr>
    <tr style="height:20px;">
        <td class="tg-0lax"><p class="text-center textsize">Day</p> <p class="text-center textsize">1</p></td>
        <td class="tg-0lax"><p class="text-center textsize">Month</p> <p class="text-center textsize">3</p></td>
        <td class="tg-0lax"><p class="text-center textsize">Year</p> <p class="text-center textsize">2012</p></td>
        <td class="tg-0lax"><p class="text-center textsize">Day</p> <p class="text-center textsize">1</p></td>
        <td class="tg-0lax"><p class="text-center textsize">Month</p> <p class="text-center textsize">3</p></td>
        <td class="tg-0lax"><p class="text-center textsize">Year</p> <p class="text-center textsize">2012</p></td>
    </tr>
    <tr style="height:160px;">
        <td class="tg-0lax" colspan="10">
            <p class="text-left titlesize"> 7 + 13. DESCRIBE REACTION(S) (including relevant tests/lab data) </p>
            <p class="text-left textsize">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsa sapiente eligendi consequuntur quibusdam quis dolorem harum, nihil rerum aut deserunt id? Deserunt veritatis assumenda aliquid perspiciatis cum expedita neque laboriosam aut culpa soluta, consequuntur temporibus quaerat atque sed accusantium fuga nam ab quis! Quasi dolore similique odio vitae cumque illum amet? Officia reprehenderit alias obcaecati sed quidem! Placeat dignissimos at illo. Nam, cupiditate molestiae! Excepturi aliquam saepe quis accusantium, sit voluptate quo repudiandae harum nesciunt nam error earum nisi magnam sunt. Minus vel dolorum autem iusto officiis quia, ducimus, excepturi saepe quisquam blanditiis a perferendis nobis eos cum assumenda? Minima.</p>
        </td>
    </tr>
    </table>

    <!-- II. SUSPECT DRUG(S) INFORMATION -->
    <h3 class="text-center mt-3">II. SUSPECT DRUG(S) INFORMATION</h3>
    <table class="tg mx-auto" style="width: 95%; height:200px;">
        <colgroup>
            <col style="width: 50%">
            <col style="width: 25%">
            <col style="width: 25%">
        </colgroup>
        <tr>
            <th class="tg-0pky" colspan="2">
                <p class="text-left titlesize"> 14. SUSPECT DRUG(S) (include generic name) </p>
                <p class="text-left textsize">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nam facilis, deleniti unde ducimus quaerat doloremque, laborum dolorum ipsa reprehenderit hic alias quibusdam cumque voluptas nobis debitis. Labore dolore tenetur voluptatum.</p>
            </th>
            <th class="tg-0pky">
                <p class="text-center titlesize"> 20. DID REACTION ABATE AFTER STOPPING DRUG?</p>
                <div class="d-flex justify-content-around">
                    <input type="checkbox" name="vehicle1" value="Bike"> Yes<br>
                    <input type="checkbox" name="vehicle2" value="Car"> No<br>
                    <input type="checkbox" name="vehicle3" value="Boat"> N/A<br>
                </div>
            </th>
        </tr>
        <tr style="height:20px;">
            <td class="tg-0pky">
                <p class="text-left titlesize"> 15. DAILY DOSE(S) </p>
                <p class="text-left textsize"> 200 MG QD </p>
            </td>
            <td class="tg-0pky">
                <p class=" titlesize"> 16. ROUTE(S) OF ADMINISTRATION </p>
                <p class=" textsize"> ORAL </p>
            </td>
            <td class="tg-0pky" rowspan="2">
                <p class="text-center titlesize"> 21. DID REACTION REAPPEAR AFTER REINTRODUCTION?</p>
                <div class="d-flex justify-content-around">
                    <input type="checkbox" name="vehicle1" value="Bike"> Yes<br>
                    <input type="checkbox" name="vehicle2" value="Car"> No<br>
                    <input type="checkbox" name="vehicle3" value="Boat"> N/A<br>
                </div>
            </td>
        </tr>
        <tr style="height:20px;">
            <td class="tg-0lax" colspan="2">
                <p class=" titlesize"> 17. INDICATION(S) FOR USE </p>
                <p class=" textsize"> XDR-TB </p>
            </td>
        </tr>
        <tr>
            <td class="tg-0lax">
                <p class=" titlesize"> 18. THERAPY DATES (from/to) </p>
                <p class=" textsize"> XDR-TB </p>
            </td>
            <td class="tg-0lax" colspan="2">
                <p class=" titlesize"> 19. THERAPY  DURATION </p>
                <p class=" textsize"> 29 JULY 2016 </p>
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
                <p class="text-left textsize"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime incidunt, consectetur esse similique in dignissimos porro quod minus sunt, ipsam, culpa sint? Doloribus facilis inventore eius praesentium nostrum explicabo quis, alias itaque quod corporis maiores. Recusandae, labore sequi. Cupiditate et beatae enim corrupti harum, dolores itaque accusantium illum dolorum inventore?</p>
            </th>
        </tr>
        <tr>
            <td class="tg-0lax">
                <p class="text-left titlesize"> 23. OTHER RELEVANT HISTORY (e.g. diagnostics, allergics, pregnancy with last month of period, etc.) </p>
                <p class="text-left textsize"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, fugit? Vero ducimus optio suscipit, culpa maxime non laboriosam sequi corrupti enim voluptas, soluta officia aspernatur, error quae sint earum porro sunt quam. Cupiditate exercitationem placeat quos autem dolore ullam excepturi aspernatur quae eos perferendis eum ipsa, eligendi reprehenderit, vel voluptate. </p>
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
                <p class=" textsize"> NEW YORK CITY NEW YORK CITY NEW YORK CITY NEW YORK CITY NEW YORK CITY NEW YORK CITY </p>
            </th>
            <th class="tg-0lax" rowspan="4">
                <p class=" textsize">  NEW YORK CITY NEW YORK CITY <br>NEW YORK CITY NEW YORK CITY <br>NEW YORK CITY NEW YORK CITY <br>NEW YORK CITY NEW YORK CITY <br>NEW YORK CITY NEW YORK CITY <br> </p>
            </th>
        </tr>
        <tr>
            <td class="tg-0lax"></td>
            <td class="tg-0lax">
                <p class=" titlesize"> 24b. MFR CONTROL NO. </p>
                <p class=" textsize"> TBA-2018-0021 </p>
            </td>
        </tr>
        <tr>
            <td class="tg-0lax">
                <p class=" titlesize"> 24c. DATE RECEIVED BY MANUFACTURER </p>
                <p class=" textsize"> 01 OCT 2019 </p>
            </td>
            <td class="tg-0lax">
                <p class=" titlesize"> 24d. REPORT SOURCE </p>
                <div class="d-flex justify-content-left">
                    <input class="mx-1" type="checkbox" name="vehicle1" value="Bike"> STUDY
                    <input class="mx-1" type="checkbox" name="vehicle2" value="Car"> LITERATURE <br>
                    <input class="mx-1" type="checkbox" name="vehicle3" value="Boat"> HEALTH PROFESSIONAL
                </div>
            </td>
        </tr>
        <tr>
            <td class="tg-0lax">
                <p class=" titlesize"> DATE OF THIS REPORT </p>
                <p class=" textsize"> 01 OCT 2019 </p>
            </td>
            <td class="tg-0lax">
                <p class=" titlesize"> 25a. REPORT TYPE </p>
                <div class="d-flex justify-content-around">
                    <input type="checkbox" name="vehicle1" value="Bike"> INITIAL
                    <input type="checkbox" name="vehicle2" value="Car"> FOLLOW UP
                </div>
            </td>
        </tr>
    </table>
</body>