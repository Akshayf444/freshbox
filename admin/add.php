<?php
include_once'./db.php';

$page = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
$perpage = 20;
$totalcount = countVideo();
$totalcount = $totalcount->videocount;
$offset = offset($page, $perpage);
$videos = getVideo($perpage, $offset);
$totalpages = paging($perpage, $totalcount);
if (isset($_POST['type']) && $_POST['type'] == 'add') {
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        $name = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];
        $upload = '../video/';
        $explode = explode(".", $name);
        $name = time() . "." . end($explode);
        if (move_uploaded_file($tmp, $upload . $name)) {
            $field = array(
                'client_name' => $_POST['client_name'],
                'client_email' => $_POST['client_email'],
                'mobile' => $_POST['mobile'],
                'description' => $_POST['description'],
                'title' => $_POST['title'],
                'created_at' => date('Y-m-d H:i:s'),
                'path' => $name
            );

            sendsms($_POST['mobile'], 'Click on following Link To View Video ' . BASE_URL . 'video.php?path=' . $name);
            create($field, 'video');
        }
    }
    header('Location:dashboard.php?view=add');
    echo "<script>window.location='dashboard.php?view=add';</script>";
} elseif (isset($_POST['type']) && $_POST['type'] == 'edit') {
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        $name = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];
        $upload = '../video/';
        $explode = explode(".", $name);
        $name = time() . "." . end($explode);
        if (move_uploaded_file($tmp, $upload . $name)) {
            $field = array(
                'id' => $_POST['video_id'],
                'client_name' => $_POST['client_name'],
                'client_email' => $_POST['client_email'],
                'mobile' => $_POST['mobile'],
                'description' => $_POST['description'],
                'title' => $_POST['title'],
                'created_at' => date('Y-m-d H:i:s'),
                'path' => $name
            );

            update($field, 'video');
        }
    }
    echo "<script>window.location='dashboard.php?view=add';</script>";
} elseif (isset($_GET['type']) && $_GET['type'] == 'delete') {
    $video_id = $_GET['video_id'];
    deleteVideo($video_id);
    echo "<script>window.location='dashboard.php?view=add';</script>";
}
?>

<div class="box">
    <!-- Box Head -->
    <div class="box-head">
        <h2 class="left">Current Articles</h2>
    </div>
    <!-- End Box Head -->	

    <!-- Table -->
    <div class="table">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
<!--                <th width="13"><input type="checkbox" class="checkbox" /></th>-->
                <th>Title</th>
                <th>Email</th>
                <th>Client Name</th>
                <th>Date</th>
                <th width="110" class="ac">Content Control</th>
            </tr>
            <?php
            if (!empty($videos)) {
                while ($row = mysqli_fetch_object($videos)) {
                    ?>
                    <tr>
<!--                        <td><input type="checkbox" class="checkbox" /></td>-->
                        <td class="title"><h3><a href="video.php?path=<?php echo $row->path; ?>" class="view"><?php echo $row->title; ?></a></h3></td>
                        <td class="client_email"><?php echo $row->client_email; ?></td>
                        <td style="display: none" class="description"><?php echo $row->description; ?></td>
                        <td style="display: none" class="video_id"><?php echo $row->id; ?></td>
                        <td style="display: none" class="title"><?php echo $row->title; ?></td>
                        <td style="display: none" class="mobile"><?php echo $row->mobile; ?></td>
                        <td style="display: none" class="path"><?php echo $row->path; ?></td>
                        <td class="client_name"><?php echo $row->client_name; ?></td>
                        <td><?php echo date('d.m.Y', strtotime($row->created_at)); ?></td>
                        <td><a onclick="deleteVideo(<?php echo $row->id; ?>)" class="ico del">Delete</a><a href="#" class="ico edit">Edit</a></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
        <!-- Pagging -->
        <div class="pagging">
            <?php echo renderPaging($totalpages,'dashboard.php?view=add&page=');?>
        </div>
        <!-- End Pagging -->

    </div>
    <!-- Table -->

</div>
<!-- End Box -->
<link rel="stylesheet" type="text/css" href="css/mapl.css">

<!-- Box -->
<div class="box">
    <!-- Box Head -->
    <div class="box-head">
        <h2>Add New Article</h2>
    </div>
    <!-- End Box Head -->

    <form action="add.php" method="POST" enctype="multipart/form-data" >

        <!-- Form -->
        <div class="form">
            <p>
                <span class="req">max 100 symbols</span>
                <label>Client Name</label>
                <input type="text" class="field size1" name="client_name" />
                <input type="hidden" name="video_id">
                <input type="hidden" name="type" value="add">
            </p>
            <p>
                <span class="req">max 100 symbols</span>
                <label>Client Email</label>
                <input type="email" class="field size1" name="client_email" />
            </p>
            <p>
                <span class="req">10 digit</span>
                <label>Phone Number</label>
                <input type="number" class="field size1" name="mobile" />
            </p>
            <p>
                <span class="req">10 digit</span>
                <label>Video Title</label>
                <input type="text" class="field size1" name="title" />
            </p>
            <p>
                <span class="req">10 digit</span>
                <label>Video Discription</label>
                <input type="text" class="field size1" name="description" />
            </p>

            <p>
                <span class="req">Upload Video file below</span>
                <label>Upload Video</label>
                <input type="file" name="file" max-size="3215455" />
            </p>	

        </div>
        <!-- End Form -->

        <!-- Form Buttons -->
        <div class="buttons">
            <input type="submit" class="button" name="submit" value="submit" />
        </div>
        <!-- End Form Buttons -->
    </form>
</div>
<div>
    <section class="lightbox2" id="vid1" style="display:none;">
        <a href="#" onclick="document.getElementById('vid1').style.display = 'none';" style=" width:70px; margin:10px; z-index:600; text-align:center;  color:#FFF; background-color:#F00; padding:5px 0px; border-radius:15px; position:absolute; right:0; right:0;">Close</a>
        <video id="myVideoId" preload="metadata" autoplay width="80%" height="50%" controls style="margin:0 auto; top:25%; position:absolute; left:0; right:0; background:#ffffff; padding:10px 0; border-radius:10px; box-shadow:0 0 10px #CCC;" >
            <source src="" type="video/mp4">
        </video>
    </section>
</div>
<!-- End Box -->
<script>
    $(".edit").click(function () {
        var names = ['client_name', 'client_email', 'description', 'title', 'video_id', 'mobile'];
        for (var i = 0; i < names.length; i++) {
            $("input[name=" + names[i] + "]").val($(this).closest('tr').children('td.' + names[i]).text());
        }
        $("input[name=type]").val('edit');
    });

    $(".view").click(function () {

        var names = ['path'];
        var videosrc = '../video/' + $(this).closest('tr').children('td.' + names[0]).text();
        $(".lightbox2").css('display', 'block');
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
        var myVideo = document.getElementById("myVideoId");
        $("#myVideoId").attr('src', videosrc);
        myVideo.load();
        myVideo.play()


    });

    function deleteVideo(url) {
        if (confirm('Are you sure you want to delete')) {
            window.location = 'add.php?type=delete&video_id=' + url;
        } else {
            // Do nothing!
        }
    }
</script>    