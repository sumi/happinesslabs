function getHTTPObject2() 
		{ 
		  var xmlhttp; 		

		  if(window.XMLHttpRequest)

		  { 

				xmlhttp = new XMLHttpRequest(); 

		  } 

		  else if (window.ActiveXObject)

		  { 

				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 

				if (!xmlhttp)

				{ 

					xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 

				}			

		  } 

  		  return xmlhttp;   

		} 
var http2 = getHTTPObject2(); // We create the HTTP Object 
//END====common function for use ajax functionality=========

//====function for product=====
//START checklist
function checked_checklist(type,div_name,stringVar,checkName)
{
	var New_stringVar='';
	if(document.getElementById(checkName).checked){
		New_stringVar=stringVar+"&checkVal=1";
	}else{
		New_stringVar=stringVar+"&checkVal=0";
	}
	ajax_action(type,div_name,New_stringVar);
}
//END checklist
function add_cherry_comment(evt,type,cherryboard_id,photo_id,user_id,cherry_comment)
{
	var checkVal=0;
	if(type=="add_cherry_comment"||type=="add_cherry_expert_comment"){
		evt = (evt) ? evt : window.event
		var charCode = (evt.which) ? evt.which : evt.keyCode 
		if (charCode == 13)
		{
			if(cherry_comment!="Leave your comment here" && photo_id!=""){
				var url = "ajax_data.php?type="+type+"&cherryboard_id="+cherryboard_id+"&photo_id="+photo_id+"&user_id="+user_id+"&cherry_comment="+cherry_comment;
				checkVal=1;
			}
		}
	}
 	if(type=="del_cherry_comment"||type=="del_cherry_expert_comment"){
		if(confirm('Are you sure to delete this comment?')){
			var url = "ajax_data.php?type="+type+"&cherryboard_id="+cherryboard_id+"&photo_id="+photo_id+"&user_id="+user_id+"&comment_id="+cherry_comment;
			checkVal=1;
		}
	}
	if(checkVal==1)
	{
		//alert(url);
		http2.open("GET", url , true); 
		http2.onreadystatechange = handleHttpResponse_cherryComment; 
		http2.send(null);
	}
}
function handleHttpResponse_cherryComment()
{    
	if (http2.readyState == 4) 
	{ 
		  if(http2.status==200) 
		  { 
			var results=http2.responseText;
			//alert(results);
			results=results.replace(/(\r\n|\n|\r)/gm,"");
			results_array=results.split('###');
			
			var cherryboard_id=results_array[2];
			if(cherryboard_id>0){
				ajax_action('refresh_inspir_feed','inspir_feed1','cherryboard_id='+cherryboard_id);
			}
			document.getElementById("cherry_comment_"+results_array[0]).value = 'Leave your comment here'; 
			document.getElementById("div_cherry_comment_"+results_array[0]).innerHTML = results_array[1]; 
		  } 
	} 
}
function submitFormOnEnter(evt,pkeyword,pcity)
{
	//example onkeypress="return submitFormOnEnter(event)"
	evt = (evt) ? evt : window.event
	var charCode = (evt.which) ? evt.which : evt.keyCode 
	if (charCode == 13)
	{
		setKEywordLocatiobUrl(pkeyword,pcity);
	}
}
function add_cherry_cheers(type,photo_id,cherryboard_id,user_id)
{
	var url = "ajax_data.php?type="+type+"&photo_id="+photo_id+"&cherryboard_id="+cherryboard_id+"&user_id="+user_id;
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_cherryComment; 
	http2.send(null);
}
///photo functions===
function photo_cancel(mtype,file_name)
{	
	if(mtype=="expert"){
		var url = "expert_uploadPhoto.php?type=cancel&file_name="+file_name;
	}else{
		var url = "uploadPhoto.php?type=cancel&file_name="+file_name;
	}
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_photo_cancel; 
	http2.send(null);	
}

function handleHttpResponse_photo_cancel()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		document.getElementById("div_up_photo").innerHTML = results; 
	  } 
	} 
}

function add_photo(mtype,file_name)
{	
	var user_id=document.getElementById('user_id').value;
	var cherryboard_id=document.getElementById('cherryboard_id').value;
	var comment = document.getElementById('txtcomment').value;
	if(mtype=="expert"){
		var url = "expert_uploadPhoto.php?type=add&file_name="+file_name+"&user_id="+user_id+"&cherryboard_id="+cherryboard_id+"&comment="+comment;
	}else{
		document.getElementById("rotate_img").src="images/loading.gif";
		var url = "uploadPhoto.php?type=add&file_name="+file_name+"&user_id="+user_id+"&cherryboard_id="+cherryboard_id+"&comment="+comment;
	}
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_add_photo; 
	http2.send(null);	
}
function showLoadingImg(divName){
	document.getElementById(divName).src="images/loading.gif";
}
function rotate_photo(mtype,file_name,rotate_degree)
{	
	showLoadingImg('rotate_img');
	var txtcomment=document.getElementById('txtcomment').value;
	if(mtype=="expert"){
		var url = "expert_uploadPhoto.php?type=rotate&file_name="+file_name+"&rotate_degree="+rotate_degree+"&txtcomment="+txtcomment;
	}else{
		var url = "uploadPhoto.php?type=rotate&file_name="+file_name+"&rotate_degree="+rotate_degree+"&txtcomment="+txtcomment;
	}
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_rotate_photo; 
	http2.send(null);	
}
function handleHttpResponse_rotate_photo()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		document.getElementById("div_up_photo").innerHTML = results;
		
	  } 
	} 
}

function handleHttpResponse_add_photo()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		results_array=results.split('##===##');
		if(parseInt(results_array[2])>0){
			document.location='cherryboard.php?cbid='+results_array[2];
		}else{
			document.getElementById("div_up_photo").innerHTML = results_array[0];
			document.getElementById("right_container").innerHTML = results_array[1];
		}
		
	  } 
	} 
}
//del photo
function photo_action(type,cherryboard_id,photo_id)
{	
	var confrim=0;
	if(type=="del_photo"){
		if(confirm('Are you sure to delete this photo?')){		
			var url = "uploadPhoto.php?type="+type+"&cherryboard_id="+cherryboard_id+"&del_photo_id="+photo_id;
			confrim=1;
		}
	}	
	if(type=="del_expert_photo"){
		if(confirm('Are you sure to delete this photo?')){		
			var url = "expert_uploadPhoto.php?type="+type+"&cherryboard_id="+cherryboard_id+"&del_photo_id="+photo_id;
			confrim=1;
		}
	}	
	if(confrim==1){
		//alert(url);	
		http2.open("GET", url , true); 
		http2.onreadystatechange = handleHttpResponse_photo_action; 
		http2.send(null);
	}
}

function handleHttpResponse_photo_action()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		results_array=results.split('##===##');
		document.getElementById("right_container").innerHTML = results_array[2];
		setupBlocks();
	  } 
	} 
}
/*
function get_gifts(type,category_id)
{	
	var url = "ajax_data.php?type="+type+"&category_id="+category_id;
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_get_gifts; 
	http2.send(null);	
}

function handleHttpResponse_get_gifts()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		document.getElementById("div_get_gifts").innerHTML = results;
	  } 
	} 
}*/
function select_gift(type,gift_id,field_name)
{	
	  if(document.getElementById(field_name).checked){
		document.getElementById('gosetup3').value=1;  
		var url = "ajax_data.php?type="+type+"&gift_id="+gift_id;
		//alert(url);
		http2.open("GET", url , true); 
		http2.onreadystatechange = handleHttpResponse_sel_gifts; 
		http2.send(null);	
	  }else{
		document.getElementById('gosetup3').value=0;  
	  	var url = "ajax_data.php?type="+type+"&uncheck=true&gift_id="+gift_id;
		//alert(url);
		http2.open("GET", url , true); 
		http2.onreadystatechange = handleHttpResponse_sel_gifts; 
		http2.send(null);	
	  }
}
function handleHttpResponse_sel_gifts()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		results=results.replace(/(\r\n|\n|\r)/gm,"");
		results_array=results.split('##===##');
		if(results_array[0]>0){
			//alert(results_array[1]);
			var varnam='chk_gift_'+results_array[0];
			//alert(varnam);
			document.getElementById(varnam).checked=false;
			document.getElementById('div_msg').innerHTML = results_array[1];
		}else{
			document.getElementById('div_msg').innerHTML = '';
		}
	  } 
	} 
}
function goto_step3(){
	if(document.getElementById('gosetup3').value==1){
		document.location='setup3.php';
	}else{
		alert('Please select gift first');	
	}
}

function goto_step3_next(goal_arr_key)
{	
	var url = "ajax_data.php?type=get_goals&goal_arr_key="+goal_arr_key;
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_step3_next; 
	http2.send(null);	
}

function handleHttpResponse_step3_next()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		document.getElementById("div_sys_goals").innerHTML = results;
		
	  } 
	} 
}
function select_goal(type,cherryboard_id,field_name)
{	
	  if(document.getElementById(field_name).checked){
		document.getElementById('gosetup4').value=1;   
		var url = "ajax_data.php?type="+type+"&cherryboard_id="+cherryboard_id;
		//alert(url);
		http2.open("GET", url , true); 
		http2.onreadystatechange = handleHttpResponse_sel_goal; 
		http2.send(null);	
	  }else{
		document.getElementById('gosetup4').value=0;   
	  	var url = "ajax_data.php?type="+type+"&uncheck=true&cherryboard_id="+cherryboard_id;
		//alert(url);
		http2.open("GET", url , true); 
		http2.onreadystatechange = handleHttpResponse_sel_goal; 
		http2.send(null);	
	  }
}
function handleHttpResponse_sel_goal()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		results=results.replace(/(\r\n|\n|\r)/gm,"");
		//alert(results);
		results_array=results.split('##===##');
		if(results_array[0]>0){
			alert(results_array[1]);
			var varnam='chk_goals_'+results_array[0];
			//alert(varnam);
			document.getElementById(varnam).checked=false;
		}
	  } 
	} 
}
function goto_step4(){
	if(document.getElementById('gosetup4').value==1){
		document.location='setup4.php';
	}else{
		alert('Please select goal first');	
	}
}
function select_checklist(type,checklist_id,field_name)
{	
	  if(document.getElementById(field_name).checked){
		  
		var url = "ajax_data.php?type="+type+"&checklist_id="+checklist_id;
		//alert(url);
		http2.open("GET", url , true); 
		http2.onreadystatechange = handleHttpResponse_sel_goal; 
		http2.send(null);	
	  }else{
	  	var url = "ajax_data.php?type="+type+"&uncheck=true&checklist_id="+checklist_id;
		//alert(url);
		http2.open("GET", url , true); 
		http2.onreadystatechange = handleHttpResponse_sel_checklist; 
		http2.send(null);	
	  }
}
function handleHttpResponse_sel_checklist()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		results=results.replace(/(\r\n|\n|\r)/gm,"");
		//alert(results);
		results_array=results.split('##===##');
	  } 
	} 
}
function edit_goal(type,cherryboard_id)
{	
	  if(type=="edit_goal_title"){
		var url = "ajax_data.php?type="+type+"&cherryboard_id="+cherryboard_id;
	  }else if(type=="save_goal_title"){
		var edit_title=document.getElementById('goal_edit_title').value;  	
	  	var url = "ajax_data.php?type="+type+"&cherryboard_id="+cherryboard_id+"&edit_title="+edit_title;
	  }else if(type=="edit_expert_title"){
		var url = "ajax_data.php?type="+type+"&cherryboard_id="+cherryboard_id;
	  }else if(type=="save_expert_title"){
		var edit_title=document.getElementById('goal_edit_title').value;  	
	  	var url = "ajax_data.php?type="+type+"&cherryboard_id="+cherryboard_id+"&edit_title="+edit_title;
	  }
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_edit_goal; 
	http2.send(null);	
}
function handleHttpResponse_edit_goal()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		document.getElementById("div_goal_title").innerHTML = results;
		
	  } 
	} 
}
function showDynamicDiv(DivName,totalDyndivName){
	var divnum=parseInt(document.getElementById(totalDyndivName).value)+1;
	var id=DivName+divnum;
	//alert(id);
	if (document.getElementById && document.createTextNode)
	{
		var tr=document.getElementById(id);
		if (tr) {
			if (tr.style.display == 'none') {
			// we set style to block for IE
			// but for firefox we use table-row
				try {
						tr.style.display='table-row';
						} catch(e) {
						tr.style.display = 'block';
					}
			}
			else {
				tr.style.display = 'none';
				}
			}
	}
	document.getElementById(totalDyndivName).value=divnum;
}
//==ajax
function ajax_action(type,div_name,stringVar)
{	
	var isAction=0;
	var script_name='ajax_data.php';
	
	
	//photo refresh of the expert board
	if(type=="photo_refresh"){
		script_name='uploadPhoto.php';
		showLoadingImg('rotate_asc');
		isAction=1;
	}
	if(type=="exp_photo_refresh"){
		script_name='expert_uploadPhoto.php';
		showLoadingImg('rotate_asc');
		isAction=1;
	}
	
	//swap image
	if(type=="swap_image"){
		isAction=1;
	}
	
	//update photo title
	if(type=="upd_photo_title"){
		isAction=1;
	}
	
	//START expert checkin mail
	if(type=="exp_checkin_mail"){
		isAction=1;
	}
	
	//START buy expert board
	if(type=="buy_board"||type=="buy_board_exp"){
		isAction=1;
	}
	
	//START add category
	if(type=="add_category"){
		isAction=1;
	}
	
	//START checklist
	if(type=="checked_checklist"){
		isAction=1;
	}
	if(type=="add_checklist"){
		isAction=1;
	}
	if(type=="remove_checklist"){
		if(confirm('Are you sure to delete checklist item?')){
			isAction=1;
		}
	}
	//EXpert board
	if(type=="checked_expert_checklist"){
		isAction=1;
	}
	if(type=="add_expert_checklist"){
		isAction=1;
	}
	if(type=="remove_expert_checklist"){
		if(confirm('Are you sure to delete checklist item?')){
			isAction=1;
		}
	}
	//END checklist
	//START refresh feed
	if(type=="refresh_inspir_feed"){
		isAction=1;
	}
	//Delete Campaign Checklist
	if(type=="delete_chk"){
		if(confirm('Are you sure to delete this checklist?')){
			isAction=1;
		}
	}
	if(type=="del_sel_expert_followers"){
		if(confirm('Are you sure to delete this friend?')){
			isAction=1;
			script_name='invite_friends.php';
		}
	}
	if(type=="delete_goal_friends"){
		if(confirm('Are you sure to delete this friend?')){
			isAction=1;
		}
	}
	if(type=="delete_goal_recent_friends"){
		if(confirm('Are you sure to delete request friend?')){
			isAction=1;
		}
	}
	if(type=="sel_goal_recent_friends"){
		isAction=1;
	}
	//followers
	if(type=="delete_goal_followers"){
		if(confirm('Are you sure to delete this follower?')){
			isAction=1;
		}
	}
	if(type=="sel_goal_recent_followers"){
		isAction=1;
		
	}
	if(type=="delete_goal_recent_followers"){
		if(confirm('Are you sure to delete request follower?')){
			isAction=1;
		}
	}
	//goal experts
	if(type=="delete_goal_experts"){
		if(confirm('Are you sure to delete this expert?')){
			isAction=1;
		}
	}
	if(type=="delete_goal_monthly_specials"){
		if(confirm('Are you sure to delete this gift?')){
			isAction=1;
		}
	}
	if(type=="add_goal_expert"){
		isAction=1;
	}
	//goal experts
	if(type=="get_gifts"){
		isAction=1;
	}
	//Get More Link
	if(type=="get_more"){
		isAction=1;
	}
	//Fb link post
	if(type=="fb_link_post"){
		isAction=1;
	}
	//Edit goal and strikes days
	if(type=="edit_goal_day"){
		isAction=1;
	}
	if(type=="save_goal_day"){
		var edit_goal_days=document.getElementById('edit_goal_days').value;  
		var edit_miss_days=document.getElementById('edit_miss_days').value;
	  	stringVar=stringVar+"&edit_goal_days="+edit_goal_days+"&edit_miss_days="+edit_miss_days;
		isAction=1;
	}
	//Edit Reward Title
	if(type=="edit_reward_title"){
		isAction=1;
	}
	if(type=="save_reward_title"){
		var edit_reward_title=document.getElementById('edit_reward_title').value;
	  	stringVar=stringVar+"&edit_reward_title="+edit_reward_title;
		isAction=1;
	}
	//Edit Reward Picture
	if(type=="edit_reward_picture"){
		isAction=1;
	}
	//Delete Campaign Expert
	if(type=="delete_cmp_expert"){
		if(confirm('Are you sure to delete this experts?')){
			isAction=1;
		}
	}
	if(isAction==1){
		var url = script_name+"?type="+type+"&div_name="+div_name+"&"+stringVar;
		//alert(url);
		http2.open("GET", url , true); 
		http2.onreadystatechange = handleHttpResponse_ajax_action; 
		http2.send(null);	
	}
}
function handleHttpResponse_ajax_action()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		results=results.replace(/(\r\n|\n|\r)/gm,"");
		results_array=results.split('##===##');
		var action_type=results_array[0];
		var div_name=results_array[1];
		var div_content=results_array[2];
		//alert(action_type);alert(div_name);alert(div_content);
		
		//setup Add category
		if(action_type=="swap_image"){
			var swap_type=results_array[3];
			if(swap_type=="new_swaped"){
				document.getElementById(results_array[4]).innerHTML = results_array[5];
				document.getElementById(results_array[6]).innerHTML = results_array[7];
				//document.location='expert_cherryboard.php?cbid='+results_array[8];
				//alert(results_array[8]);
				ajax_action('exp_photo_refresh','right_container','cherryboard_id='+results_array[8]+'&sort='+results_array[9]);
			}
		}
		//setup Add category
		if(action_type=="add_category"){
			if(div_content=="Success"){
				document.location='setup2.php';	
			}
			
		}
		//extra action for checklist
		if(action_type=="add_checklist"||action_type=="add_expert_checklist"){
			var cherryboard_id=results_array[3];
			document.getElementById('txt_checklist').value = 'add something to your checklist';
			if(action_type=="add_checklist"){
				ajax_action('refresh_inspir_feed','inspir_feed1','cherryboard_id='+cherryboard_id);
			}
			if(action_type=="add_expert_checklist"){
				ajax_action('refresh_expert_inspir_feed','inspir_feed1','cherryboard_id='+cherryboard_id);
			}
		}
		//extra action for select expert
		if(action_type=="add_goal_expert"){
			var cherryboard_id=results_array[3];
			document.location='cherryboard.php?cbid='+cherryboard_id;
		}
		//buy expertboard
		if(action_type=="buy_board_exp"){
			var cherryboard_id=results_array[3];
			document.location='expert_cherryboard.php?cbid='+cherryboard_id;
		}
		document.getElementById(div_name).innerHTML = div_content;
		//photo refresh of the board
		if(action_type="photo_refresh"||action_type=="exp_photo_refresh"){
			setupBlocks();
		}
		
		
	  } 
	} 
}
function CheckFormValidation(divName,FormVars){
	var VarArray=FormVars.split(',');
	var checkMsg='';
	for(i=0;i<=(VarArray.length-1);i++){
		var FieldName=VarArray[i];
		var FieldVar=FieldName.split('#');
		var FieldValue=document.getElementById(FieldVar[0]).value;
		var FieldMsg=FieldVar[1];
		if(FieldValue==""){
			document.getElementById(divName).innerHTML=FieldMsg;
			return false;
		}else if(FieldValue==FieldVar[2]&&FieldVar[2]!=""){
			document.getElementById(divName).innerHTML=FieldMsg;
			return false;
		}
		if(FieldVar[0]=="sponsorship_url"){
			if(ValidURL(FieldValue)==false){
				document.getElementById(divName).innerHTML=FieldMsg;
				return false;
			}
		}
	}
	return true;
}
function ValidURL(url) {
     var theurl=url;
     var tomatch= /[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/
     if (tomatch.test(theurl))
     {
         return true;
     }
     else
     {
         return false; 
     }
}
function delCompain(totalGoal){
	if(totalGoal>0){
		alert('Cannot delete the campaign as there are goal boards associated with this campaign');
		return false;	
	}else{
		return confirm('Are you sure to delete campaign');	
	}
	
}
function delReward(totalGoal){
	if(totalGoal>0){
		alert('Cannot delete the reward as there are goal boards associated with this reward');
		return false;	
	}else{
		return confirm('Are you sure to delete reward');	
	}
}
function delExpert(totalExpert){
	if(totalExpert>0){
		alert('Cannot delete the expert as there are expert boards associated with this expert');
		return false;	
	}else{
		return confirm('Are you sure to delete this expert');	
	}
}
