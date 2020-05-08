<div class="row justify-content-center align-items-center h-100">
    <div class="col-sm-8 col-md-6 col-lg-5">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Sign Up</h3>

                <?php if(isset($_POST["IsCompleted"])): ?>
                    <div id="SuccessReg" class="mb-5 d-flex flex-column justify-content-around align-items-center">
                        <i class="fa fa-check fa-5x text-success"></i>
                        <p class="text-center"><a href="<?=$_SESSION["HomeDir"]."login"?>">Success! Click here to login.</a></p>
                    </div>
                <?php else: ?>
                    <?php if(isset($txtErrorMsg)): ?>
                        <div id="formAlert" class="alert alert-danger" role="alert">ERROR: <?=((strlen(trim($txtErrorMsg)) > 0) ? $txtErrorMsg : "Unknown.")?></div>
                    <?php endif; ?>

                    <form id="RegForm" action="<?=$_SESSION["HomeDir"]."signup-submit"?>" class="needs-validation" method="post" novalidate <?=$txtStyle?> oninput='txtRepeatPassword.setCustomValidity(txtRepeatPassword.value != txtPassword.value ? "Passwords do not match." : "")'>
                        <div class="form-group row">
                            <label for="txtEmail" class="col-12 col-form-label">Email</label>
                            <div class="col-12">
                                <input type="email" id="txtEmail" name="txtEmail" class="form-control" placeholder="user@example.com" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="txtPassword" class="col-12 col-form-label">Password</label>
                            <div class="col-12">
                                <input type="password" id="txtPassword" name="txtPassword" class="form-control" placeholder="Enter 6 characters or more" minlength="6" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="txtRepeatPassword" class="col-12 col-form-label">Repeat Password</label>
                            <div class="col-12">
                                <input type="password" id="txtRepeatPassword" name="txtRepeatPassword" class="form-control" placeholder="Repeat your password" minlength="6" required>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col">
                                <button type="submit" class="col btn btn-primary btn-block font-weight-bold">SIGN UP</button>
                            </div>
                        </div>
                    </form>
                    
                    <p class="text-center mt-5 mb-0 text-secondary">Already has an account? <a href="<?=$_SESSION["HomeDir"]."login"?>" class="text-primary font-weight-bold">Log In</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    <?php if(isset($_POST["IsCompleted"])): ?>
        let w = $("#RegForm").width();
        let h = $("#RegForm").height();
        $("#SuccessReg").css({
            "width":w,
            "margin-top":h*-1,
            "height":h
        });
    <?php endif; ?>
});

myBootstrapFormValidator();
</script>