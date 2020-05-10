<div class="row justify-content-center align-items-center h-100">
    <div class="col-sm-8 col-md-6 col-lg-5">
        <div class="card sticky-top">
            <div class="card-body">
                <h3 class="card-title text-center mb-5">Login</h3>
                <div class="error-container"></div>
                <form autocomplete="off">
                    <div class="form-group row">
                        <div class="col">
                            <input type="text" id="txtUserName" name="txtUserName" class="form-control" placeholder="Email Address" value="<?=$txtUserName?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="password" id="txtPassword" name="txtPassword" class="form-control" placeholder="Password" value="<?=$txtPassword?>" data-toggle="password">
                            <div class="input-group-append">
                                <div id="btnShowPass" class="input-group-text" title="Show/Hide Password"><i class="fa fa-eye"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-check form-group">
                        <input type="checkbox" class="form-check-input" id="chkKeepLogin" <?=$chkKeepLogin?>>
                        <label class="form-check-label" for="chkKeepLogin">Keep me logged in</label>
                    </div>
                    
                    <div class="form-group">
                        <button type="button" id="btnLogin" class="btn btn-primary btn-block font-weight-bold">LOG IN</button>
                    </div>
                </form>
                <p class="text-center mt-5 mb-0 text-secondary">Not a member yet? <a href="<?=$_SESSION["HomeDir"]."signup"?>" class="text-primary font-weight-bold">Sign Up</a></p>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#btnShowPass").css("cursor","pointer").click(function(){
        $("#btnShowPass i").toggleClass("fa-eye").toggleClass("fa-eye-slash");
        if($("#txtPassword").attr("type") == "password"){
            $("#txtPassword").attr("type", "text");
        }
        else{
            $("#txtPassword").attr("type", "password");
        }
    });

    $("#txtUserName, #txtPassword").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#btnLogin").click();
        }
    });
    $("#btnLogin").click(function (){
        var boolKeepLogin = ($("#chkKeepLogin:checked").length) ? 1 : 0;
        $.ajax({
            url: "controller/login.php",
            method: "POST",
            data: {
                txtUserName: $("#txtUserName").val(),
                txtPassword: $("#txtPassword").val(),
                chkKeepLogin: boolKeepLogin,
                IsAjax: 1
            },
            dataType: "text",
            success: function(result){
                result = parseInt(result);
                if(result){
                    window.location.replace("<?=$_SESSION["HomeDir"];?>");
                }
                else{
                    $(".error-container").html('<div class="alert alert-warning" role="alert">Failed authentication! Check your login credentials and try again.</div>');
                }
            }
        });
    });
});
</script>