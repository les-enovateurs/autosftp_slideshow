<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class='no-js' lang='en'>
<!--<![endif]-->
<head>
    <meta charset='utf-8' />
    <meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible' />
    <title>Raspberry Pi Slideshow</title>

    <meta content='Jérémy PASTOURET' name='author' />

    <meta name="distribution" content="global" />
    <meta name="language" content="en" />
    <meta content='width=device-width, initial-scale=1.0' name='viewport' />

    <link rel="stylesheet" href="./lib/css/jquery.maximage.css?v=1.2" type="text/css" media="screen" charset="utf-8" />
    <link rel="stylesheet" href="./lib/css/screen.css?v=1.2" type="text/css" media="screen" charset="utf-8" />

    <style type="text/css" media="screen">
        #maximage {
            /*				position:fixed !important;*/
        }

        /*Set my logo in bottom left*/
        #logo {
            bottom:30px;
            height:auto;
            left:30px;
            position:absolute;
            width:34%;
            z-index:1000;
        }
        #logo img {
            width:100%;
        }

    </style>

    <!--[if IE 6]>
    <style type="text/css" media="screen">
        /*I don't feel like messing with pngs for this browser... sorry*/
        #gradient {display:none;}
    </style>
    <![endif]-->
</head>
<body>

<div id="maximage">
    <?php

    $aFile = [];


    if ($handle = opendir('./photo/')) {

        while (false !== ($entry = readdir($handle))) {

            if ($entry != "." && $entry != ".." && false === strpos('readme.txt',$entry) && false == strpos('2019-05-15', $entry)) {

                array_push($aFile, $entry);
            }
        }

        closedir($handle);
    }

    sort($aFile);

    $aFile = array_reverse($aFile);

    foreach($aFile as $entry){
        echo "<img src='./photo/$entry' />\n";
    }

    ?>
</div>

<script src='./lib/js/jquery.js'></script>
<script src="./lib/js/jquery.cycle.all.js" type="text/javascript" charset="utf-8"></script>
<script src="./lib/js/jquery.maximage.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">
    $(function(){
        // Trigger maximage
        jQuery('#maximage').maximage();

        window.setInterval(function(){
            $.ajax({ // ajax call starts
                url: 'recharge_file.php', // JQuery loads serverside.php
            })
                .done(function(data) { // Variable data contains the data we get from serverside

                    if(data.length > 0)
                    {
                        location.reload();
                    }
                });


        }, 5000);
    });
</script>
</body>
</html>