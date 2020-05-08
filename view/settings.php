<?php
switch(strtolower($_GET["type"])):
    /**
     * store settings
     */
    case "store":
?>
    
    <h3>Form Outline</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="align-top" scope="col">#</th>
                    <th class="align-top" scope="col">Description</th>
                    <th class="align-top text-center" scope="col">Type</th>
                    <th class="align-top text-center" scope="col">With Graph</th>
                    <th class="align-top text-center" scope="col">Color</th>
                </tr>
            </thead>
            <tbody>
                <?=$txtFormOutline; ?>
            </tbody>
        </table>
    </div>
    <input type="hidden" id="txtSaveType" value="store">

<?php
    break;
    /**
     * account settings
     */
    case "account":
?>
    <h3>Account Settings</h3>
    <form action="<?=$_SESSION["HomeDir"]?>settings/save" id="AccountForm" class="needs-validation" method="post" novalidate autocomplete="off">
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="txtLastName">Last name</label>
                <input type="text" id="txtLastName" name="txtLastName" class="form-control" placeholder="Last name" value="<?=$txtLastName?>" required>
                <div class="invalid-feedback">
                    Your last name is required!
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="txtFirstName">First name</label>
                <input type="text" id="txtFirstName" name="txtFirstName" class="form-control" placeholder="First name" value="<?=$txtFirstName?>" required>
                <div class="invalid-feedback">
                    Your first name is required!
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="txtMidName">Middle name</label>
                <input type="text" id="txtMidName" name="txtMidName" class="form-control" placeholder="Middle name" value="<?=$txtMidName?>">
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="txtEmail">Email address</label>
                <input type="email" id="txtEmail" name="txtEmail" class="form-control" placeholder="Email address" value="<?=$txtEmail?>" required>
                <div class="invalid-feedback">
                    Email is required. Please enter a valid email
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="txtMobileNo">Mobile number</label>
                <input type="text" id="txtMobileNo" name="txtMobileNo" class="form-control" placeholder="Mobile number" value="<?=$txtMobileNo?>">
            </div>
        </div>
        <input type="hidden" id="txtSaveType" name="txtSaveType" value="account">
        <button class="btn btn-primary" type="submit" id="btnSave">Save</button>
        <?php if(isset($_SESSION["intSaveSuccess"])): 
            if($_SESSION["intSaveSuccess"] == 1): ?>
                <i id="boolSaveSuccessIndicator" class="fa fa-check text-success"></i>
            <?php else: ?>
                <i id="boolSaveSuccessIndicator" class="fa fa-times text-danger"></i>
        <?php 
            endif;
            unset($_SESSION["intSaveSuccess"]);
        endif; ?>
    </form>

    <!-- This script is from bootstrap documentation -->
    <script>
        myBootstrapFormValidator();
    </script>

<?php
    break;
    default:
        Error404();
    break;
endswitch;

?>