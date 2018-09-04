<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
    
    // get state variables from database
    $query = "SELECT phase, upload_cnt_obj1, upload_cnt_obj2, upload_cnt_obj3, subset_cnt_obj, subset_cnt_num FROM "
        ."variables where participant_id=".$_SESSION['pid']." and trial = ".$_SESSION['trial']." order by time desc";
    $latestVar = getSelect($query);
    
    $progress = 13;
    if ($latestVar['phase'] == 'subset_train2') {
        $progress = 28;
    }
    if ($latestVar['subset_cnt_num'] == 0){
        $progress += 1;
    } else if ($latestVar['subset_cnt_num'] == 5){
        $progress += 3;
    } else if ($latestVar['subset_cnt_num'] == 20){
        $progress += 2;
    }
    $progress += $latestVar['subset_cnt_obj']*3;
?>    

<script type="text/javascript">
    var cnt=0;
    var img_path = 'images/p<?=$_SESSION['pid']?>/t<?=$_SESSION['trial']?>/'+subset_for+'/'+replaceSpecial(obj_name)+'/';
    var img_size = 100;
    var img_num = 30;
    
	$("#subset_header").empty();
    if (subset_cnt_num <= 2) {
    	img_num = 30;
		limit = 20;
		$("#subset_header").append("<h4>Subset Selection 20</h4>"+"<p>If you were to choose only 20 out of the 30 training images to make the training faster which ones would they be?</p>");
	} else if (subset_cnt_num == 5) {
		img_num = 5;
		limit = 1;
		$("#subset_header").append("<h4>If you were to choose only 1?</h4>");
	} else if (subset_cnt_num == 20) {
		img_num = 20;
		limit = 5;
		$("#subset_header").append("<h4>If you were to choose only 5?</h4>");
	}
    
    console.log(img_path);
    
    $imgView = $("#imgView");
    var i;
	for (i = 1; i <= 30; i++) { 
		if (img_num == 30 || selected[i-1]) {
			var img_tag = "<img src='"+img_path+i+".png' style='width: "+img_size+"x; height: "+parseInt(img_size/ratio)+"px;PADDING-LEFT:0px; PADDING-RIGHT:0px;PADDING-TOP:0px; PADDING-BOTTOM:0px;' "
					+"id='"+i+"' onclick='click_img("+i+");' vspace='3' hspace='3'/>";
			//console.log(img_tag);
			$imgView.append(img_tag);
			document.getElementById(''+i).style.border='5px solid #000000';
		}
		selected[i-1] = false;
	}

	cnt = 0;
	selected = [];
	$("#showCnt").text("Selected "+cnt+" out of "+img_num);
    
    
    $('html, body').animate({
        scrollTop: $("#subset_header").offset().top
    }, 500);
	
	function click_img(id) {
		if (selected[id-1]) {
			document.getElementById(''+id).style.border='5px solid #000000';
			selected[id-1] = false;
			cnt -= 1;
		} else {
			if (cnt < limit) {
				document.getElementById(''+id).style.border='5px solid #33cc33';
				selected[id-1] = true;
				cnt += 1;
			}
		}
		
		$("#showCnt").text("Selected "+cnt+" out of "+img_num);
		if (cnt == limit) {
			$("#subset_next").show();
	        $('html, body').animate({
				scrollTop: $("#subset_next").offset().top
			}, 500);
		} else {
			$("#subset_next").hide();
		}
	}
</script>

<?php
    printProgressBar($progress);
?>

<div id='subset_header'></div>

<p>You can select an image by clicking on it. A green border will appear upon selection. </p>

<div id='imgView'></div>
<div id='showCnt'></div>
<div align='right'>
	<button type="button" id='subset_next' class="btn btn-primary" style="display:none;" onclick='submit_selection()'>Next ></button>
</div>


