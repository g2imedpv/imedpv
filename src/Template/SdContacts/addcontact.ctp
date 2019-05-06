<?php
//debug($sdContacts);
?>
<title>Add Contact</title>
<head>
<!-- <?= $this->Html->script('') ?> -->
<head>
<script type="text/javascript">
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
</script>
<body>
    <div class="container">
        <div class="card mt-3">
            <div class="card-header text-center">
                <h3>Add Contact</h3>
            </div>
            <div class="card-body">
                <div class="form-row">      
                    <div class="form-group col-md-6">
                        <label>Contact Type</label>
                        <select class="form-control" id="contact_type" >
                            <option value=""></option>
                            <option value="1">Pharmaceutical Company</option>
                            <option value="2">Regulatory Authority</option>
                            <option value="3">Health professional</option>
                            <option value="4">Regional Pharmacovigilance Center</option>
                            <option value="5">WHO Collaborating Center for International Drug Monitoring</option>
                            <option value="6">Other</option>
                            <option value="7">CRO</option>
                            <option value="8">Call Center</option>
                        </select>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label>Authority</label>
                        <select class="form-control" id="Authority_select">
                            <option value=""></option>
                            <option value="1">Authority</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">      
                    <div class="form-group col-md-6" >
                        <div class="option_group">
                            <label>Data Privacy: &nbsp &nbsp &nbsp</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="Privacy_yes" value="1" name="contact[privacy_type]" class="custom-control-input">
                                <label for="Privacy_yes" class="custom-control-label">Yes<label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="Privacy_no" value="2" name="contact[privacy_type]" class="custom-control-input">
                                <label for="Privacy_no" class="custom-control-label">No<label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6" >
                        <div class="option_group">
                            <label>Blinded Report: &nbsp &nbsp &nbsp</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="blinded_report_blinded" value="1" name="contact[blinded_type]" class="custom-control-input">
                                <label for="blinded_report_blinded" class="custom-control-label">Blinded<label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="blinded_report_unblinded" value="2" name="contact[blinded_type]" class="custom-control-input">
                                <label for="blinded_report_unblinded" class="custom-control-label">Unblinded<label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="blinded_report_both" value="3" name="contact[blinded_type]" class="custom-control-input">
                                <label for="blinded_report_both" class="custom-control-label">Both<label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Contact ID</label>
                        <input type="text" class="form-control" id="contact_name">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Preferred Route</label>
                        <select class="form-control" id="preferred_route">
                            <option value=""></option>
                            <option value="1">Email</option>
                            <option value="2">ESTRI Gateway</option>
                            <option value="3">Manual</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Format Type</label>
                        <select class="form-control" id="format_type">
                            <option value=""></option>
                            <option value="1">E2B</option>
                            <option value="2">CIOMS</option>
                            <option value="3">MedWatch</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6" style="margin-top:14px; text-align:justify; line-height:28px;">
                        <input type="checkbox"  id="agXchange_ost"  placeholder="">Route through agXchange-OST<br>
                        <input type="checkbox"  id="sir_contact"  placeholder="">SIR Contact<br>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Title</label>
                        <input type="text" class="form-control" id="contact_person_title">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Given Name</label>
                        <input type="text" class="form-control" id="contact_person_givenname">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Family Name</label>
                        <input type="text" class="form-control" id="contact_person_familyname">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Middle Name</label>
                        <input type="text" class="form-control" id="contact_person_middlename">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Address</label>
                        <input type="text" class="form-control" id="contact_address">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>City</label>
                        <input type="text" class="form-control" id="contact_address_city">
                    </div>
                    <div class="form-group col-md-3">
                        <label>State</label>
                        <input type="text" class="form-control" id="contact_address_state">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Zip Code</label>
                        <input type="text" class="form-control" id="contact_address_zipcode">
                    </div>
                    <div class="form-group col-md-3">
                        <label>country</label>
                        <input type="text" class="form-control" id="contact_address_country">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Phone</label>
                        <input type="text" class="form-control" id="contact_phone">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Fax</label>
                        <input type="text" class="form-control" id="contact_fax">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Email</label>
                        <input type="text" class="form-control" id="contact_email">
                    </div>
                    <div class="form-group col-md-3">
                        <label>website</label>
                        <input type="text" class="form-control" id="contact_website">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12 text-center">
                        <button type="button" class="btn btn-outline-success">Complete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
