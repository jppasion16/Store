<div class="row justify-content-center align-items-center h-100">
    <div class="col-sm-10 col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Connect your account to your store!</h3>
                
                <p class="font-weight-bold">Search for Existing Store</p>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" id="txtStoreSearch" name="txtStoreSearch" class="form-control" placeholder="Your store...">
                        <div class="input-group-append">
                            <div id="btnShowPass" class="input-group-text bg-primary text-white">
                                Sign Up
                            </div>
                        </div>
                    </div>
                </div>

                <p class="font-weight-bold mt-5">Sign up your own store</p>
                <div class="form-group">
                    <label for="txtStoreName">Store Name</label>
                    <input type="text" id="txtStoreName" name="txtStoreName" class="form-control" placeholder="Name of your store" required>
                </div>
                <div class="form-group">
                    <label for="sltRegion">Region</label>
                    <select id="sltRegion" name="sltRegion" class="form-control" title="select what region your store is in" required>
                        <option value="0">Please select Region</option>
                        <?=$opnRegion?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sltProvince">Province</label>
                    <select id="sltProvince" name="sltProvince" class="form-control" title="select what province your store is in" required>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sltCityMun">City/Municipality</label>
                    <select id="sltCityMun" name="sltCityMun" class="form-control" title="select what city/municipality your store is in" required>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sltBrgy">Barangay</label>
                    <select id="sltBrgy" name="sltBrgy" class="form-control" title="select what barangay your store is in" required>
                    </select>
                </div>
                <div class="form-group">
                    <label for="txtAddress">Blk, Lot, Street</label>
                    <input type="text" id="txtAddress" name="txtAddress" class="form-control" placeholder="Additional info for store's address">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // START OF CHANGE LISTENER
    // region
    <?php
        $arrIDs = array("#sltRegion", "#sltProvince", "#sltCityMun", "#sltBrgy");
        $arrFind = array("Region", "Province", "CityMun", "Brgy");
        $arrDesc = array("Region", "Province", "City/Municipality", "Barangay");
        for($idx = 0; $idx < (count($arrIDs)-1); $idx++):
    ?>
        $("<?=$arrIDs[$idx]?>").change(function(){
            $.ajax({
                url: "controller/signup-store.php",
                method: "POST",
                data: {
                    txtCode: $("<?=$arrIDs[$idx]?>").val(),
                    txtFind: "<?=$arrFind[$idx+1]?>",
                    IsAjax: 1
                },
                dataType: "text",
                success: function(result){
                    $("<?=$arrIDs[$idx+1]?>").html("<option value='0'>Please select <?=$arrDesc[$idx+1]?></option>"+result);
                }
            });
        });
    <?php endfor; ?>
});
</script>