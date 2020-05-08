<form id="RegProcessForm" action="<?=$_SESSION["HomeDir"]."signup"?>" method="post" style="visibility:hidden">
    <?php foreach ($arrPost as $key => $value) { ?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>">
    <?php } ?>
</form>

<script>
$(document).ready(function(){
    $("#RegProcessForm").submit();
});
</script>