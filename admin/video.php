<?php include_once './db.php';?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="cache-control" content="no-cache">
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="expires" content="0">
        <link rel="shortcut icon" href="tel-images/favicon.ico" type="image/x-icon"> 
        <title>Taleview Communication | Creative | Content | Concept | Multimedia | Web</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/mapl.css">
        <link rel="stylesheet" type="text/css" href="css/telview.css" media="all">


        <script language=JavaScript>
<!--

            //Disable right click script III- By Renigade (renigade@mediaone.net)
            //For full source code, visit http://www.dynamicdrive.com

            var message = "";
            ///////////////////////////////////
            function clickIE() {
                if (document.all) {
                    (message);
                    return false;
                }
            }
            function clickNS(e) {
                if
                        (document.layers || (document.getElementById && !document.all)) {
                    if (e.which == 2 || e.which == 3) {
                        (message);
                        return false;
                    }
                }
            }
            if (document.layers)
            {
                document.captureEvents(Event.MOUSEDOWN);
                document.onmousedown = clickNS;
            }
            else {
                document.onmouseup = clickNS;
                document.oncontextmenu = clickIE;
            }

            document.oncontextmenu = new Function("return false")
            // --> 
            myVideo = document.getElementById("myVideoId");
            myVideo.load();
            myVideo.play()
        </script>


    </head>

    <body>
        <section class="logo" style="margin:46px 0 0 20px;"> <a href="http://freshboxmedia.co.in/default.html"><img src="tel-images/Untitled-2.png" alt="FreshBox Media" style="width:190px; height:auto;"></a></section>
        <section class="lightbox2" id="vid1" style="display:block;">
            <a href="http://freshboxmedia.co.in/default.html" style=" width:70px; margin:10px; z-index:600; text-align:center;  color:#FFF; background-color:#F00; padding:5px 0px; border-radius:15px; position:absolute; right:0; right:0;">Close</a>
            <video id="myVideoId" preload="metadata" autoplay width="80%" height="50%" controls style="margin:0 auto; top:25%; position:absolute; left:0; right:0; background:#ffffff; padding:10px 0; border-radius:10px; box-shadow:0 0 10px #CCC;" >
                <source src="<?php echo BASE_URL . '../video/' . $_GET['path'] ?>" type="video/mp4">
            </video>
        </section>
    </body>
</html>
