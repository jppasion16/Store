<?php
switch(strtolower($_GET["type"])):
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
<?php
    break;
    case "account":
?>
    

<?php
    break;
    default:
        Error404();
    break;
endswitch;

?>