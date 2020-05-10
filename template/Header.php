<?php
/*
    includes general functions that will be used in all pages
*/
include_once($_SESSION["DocRoot"]."assets/Functions.php");
function ArrayConcat($array, $keys, $value = ""){
	$key = array_shift($keys);
	if(count($keys) === 0){
		$array[$key] = $value;
		return $array;
	}
	$array[$key] = array();
	$array[$key] = ArrayConcat($array[$key], $keys);
	return $array;
}

$path = trim(substr($_SERVER["REQUEST_URI"], strlen($_SESSION["HomeDir"])), "/");
$arrPath = explode("/", $path);
$active = ArrayConcat(array(), $arrPath, "active");

if(isset($_SESSION["currUserName"])) $boolHasSession = true;
else $boolHasSession = false;

if(isset($_SESSION["storeKey"])) $boolHasStore = true;
else $boolHasStore = false;

?>

<!doctype html>
<head>
    <!-- jQuery -->
    <script src="<?=$_SESSION["HomeDir"]?>assets/jquery-3.4.1.js"></script>

    <!-- jQuery UI -->
    <link rel="stylesheet" type="text/css" href="<?=$_SESSION["HomeDir"]?>assets/jquery-ui/jquery-ui.css">
    <script src="<?=$_SESSION["HomeDir"]?>assets/jquery-ui/jquery-ui.js"></script>

    <!-- Chart.js -->
    <link rel="stylesheet" type="text/css" href="<?=$_SESSION["HomeDir"]?>assets/chart/Chart.min.css">
    <script src="<?=$_SESSION["HomeDir"]?>assets/chart/Chart.min.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?=$_SESSION["HomeDir"]?>assets/bootstrap/css/bootstrap.css">
    <script src="<?=$_SESSION["HomeDir"]?>assets/bootstrap/js/bootstrap.js"></script>

    <link rel="stylesheet" href="<?=$_SESSION["HomeDir"]?>assets/font-awesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?=$_SESSION["HomeDir"]?>assets/font-awesome/css/solid.min.css">

    <!-- OWN -->
    <link rel="stylesheet" type="text/css" href="<?=$_SESSION["HomeDir"]?>assets/style.css.php">
    <script src="<?=$_SESSION["HomeDir"]?>assets/Functions.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Store System</title>
    <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
    <script>
        // hides top navigation when scrolled down, shows when scrolled up
        $(document).ready(function(){
            myScrollListener(window, function(){
                $("#TopNav").slideUp(150);
                $(".sticky-top").css("top", "10px");
            },
            function(){
                $("#TopNav").slideDown(150);
                $(".sticky-top").css("top", "70px");
            });

            <?php if($boolHasSession): ?>
                function SessionRefresh(){
                    var RefreshInterval = 600000; // every 10 mins
                    setTimeout(function(){
                        jQuery.ajax({
                            url: '<?=$_SESSION["HomeDir"]?>assets/SessionRefresh.php',
                            cache: false,
                            success: function(){
                                SessionRefresh();
                            }
                        });
                    }, RefreshInterval);
                }
                SessionRefresh();
            <?php endif; ?>
        });
        
    </script>
    <nav id="TopNav" class="navbar navbar-light bg-white navbar-expand-sm fixed-top shadow-sm">
        <a class="navbar-brand" href="<?=$_SESSION["HomeDir"]?>">
            <?php if($boolHasStore): ?>
                <img src="<?=$_SESSION["HomeDir"]."assets/images/".$_SESSION["storeNo"]?>.png" height="30" alt="My Store"> <!-- style="filter: brightness(0) invert(1)" -->
            <?php else: ?>
                <i class="fas fa-store"></i> Store System
            <?php endif; ?>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <?php if($boolHasSession): ?>
                    <li class="nav-item">
                        <a class="nav-link <?=$active[""].$active["home"]?>" href="<?=$_SESSION["HomeDir"]?>">Home</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?=$active["about"]?>" href="<?=$_SESSION["HomeDir"]?>about">About</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link <?=$active["support"]?>" href="<?=$_SESSION["HomeDir"]?>support">Support</a>
                </li>

                <?php if($boolHasSession): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?=((count($active["settings"])>0)?"active":"")?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Account</a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item <?=$active["settings"]["account"]?>" href="<?=$_SESSION["HomeDir"]?>settings/account">Account Settings</a>
                            <a class="dropdown-item <?=$active["settings"]["store"]?>" href="<?=$_SESSION["HomeDir"]?>settings/store">Store Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item <?=$active["logout"]?>" href="<?=$_SESSION["HomeDir"]?>logout">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?=$active["login"]?>" href="<?=$_SESSION["HomeDir"]?>login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container">
        