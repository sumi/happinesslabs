function getHTTPObject2() 
{ 
  var xmlhttp; 		

  if(window.XMLHttpRequest)
  { 
	xmlhttp = new XMLHttpRequest();
  }else if (window.ActiveXObject)
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
		if(cherry_comment!="Add a comment..." && photo_id!=""){
			var url = "ajax_data.php?type="+type+"&cherryboard_id="+cherryboard_id+"&photo_id="+photo_id+"&user_id="+user_id+"&cherry_comment="+cherry_comment;
			checkVal=1;
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
		var data=results_array[1];
		
		var cherryboard_id=results_array[2];
		if(cherryboard_id>0){
			ajax_action('refresh_inspir_feed','inspir_feed1','cherryboard_id='+cherryboard_id);
		}
		document.getElementById("cherry_comment_"+results_array[0]).value = 'Add a comment...'; 
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
	showLoadingImg('rotate_img');
	var user_id=document.getElementById('user_id').value;
	var cherryboard_id=document.getElementById('cherryboard_id').value;
	var comment = document.getElementById('txtcomment').value;
	
	if(mtype=="expert"){
		var subtype=document.getElementById('subtype').value;
		//CHANGE PROFILE PICTURE
		if(subtype=="change_profile_pic"){
			var url = "expert_uploadPhoto.php?type=add_exp_profile_pic&file_name="+file_name+"&user_id="+user_id+"&cherryboard_id="+cherryboard_id+"&comment="+comment;
		//CHANGE REWARD PICTURE	
		}else if(subtype=="change_reward_pic"){
			var exp_reward_id=document.getElementById('exp_reward_id').value;
			var url = "expert_uploadPhoto.php?type=edit_exp_reward_pic&file_name="+file_name+"&user_id="+user_id+"&cherryboard_id="+cherryboard_id+"&comment="+comment+"&exp_reward_id="+exp_reward_id;
		//ADD REWARD PICTURE	
		}else if(subtype=="add_expert_reward_pic"){
			var url = "expert_uploadPhoto.php?type=add_exp_reward_pic&file_name="+file_name+"&user_id="+user_id+"&cherryboard_id="+cherryboard_id+"&comment="+comment;
		//CHANGE STORY BOARD PICTURE	
		}else if(subtype=="change_story_pic"){
			var story_photo_id=document.getElementById('story_photo_id').value;
			var url = "expert_uploadPhoto.php?type=edit_exp_story_pic&file_name="+file_name+"&user_id="+user_id+"&cherryboard_id="+cherryboard_id+"&comment="+comment+"&story_photo_id="+story_photo_id;
		}else{
			var photo_day=document.getElementById('photo_day').value;
			var url="expert_uploadPhoto.php?type=expert_add&file_name="+file_name+"&user_id="+user_id+"&cherryboard_id="+cherryboard_id+"&comment="+comment+"&photo_day="+photo_day;
		}
	}else{
		var url="uploadPhoto.php?type=add&file_name="+file_name+"&user_id="+user_id+"&cherryboard_id="+cherryboard_id+"&comment="+comment;
	}
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_add_photo; 
	http2.send(null);	
}
function handleHttpResponse_add_photo()
{    
	if(http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		results_array=results.split('##===##');
		var action_type=results_array[0];
		var content=results_array[1];
		if(action_type=="expert_add"||action_type=="add_exp_profile_pic"||action_type=="add_exp_reward_pic"||action_type=="edit_exp_reward_pic"||action_type=="edit_exp_story_pic"){
			document.location='expert_cherryboard.php?cbid='+results_array[1];
		}else{
			if(parseInt(results_array[2])>0){
				document.location='cherryboard.php?cbid='+results_array[2];
			}else{
				document.getElementById("div_up_photo").innerHTML = results_array[0];
				document.getElementById("right_container").innerHTML = results_array[1];
			}
		}
		
	  } 
	} 
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

function rotate_photo_expert(rotate_degree,photo_id,cherryboard_id)
{	
	showLoadingImg('rotate_img'+photo_id);
	var url = "expert_uploadPhoto.php?type=rotate&rotate_degree="+rotate_degree+"&photo_id="+photo_id+"&cherryboard_id="+cherryboard_id;
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_rotate_photo_expert; 
	http2.send(null);	
}
function handleHttpResponse_rotate_photo_expert()
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
		document.getElementById(div_name).innerHTML = div_content;
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
			//alert(results_array[1]);
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
	
	//LIKE AND UNLIKE STORY
	if(type=="like_story"||type=="unlike_story"){
		isAction=1;
	}
	//PUBLISH AND UNPUBLISH HAPPY STORY
	if(type=="publish_story"||type=="unpublish_story"){
		isAction=1;
	}
	//CREATE STORY NUMBER IMAGES
	if(type=="focus_story_title"||type=="focus_story_category"||type=="focus_story_about"||type=="focus_story_daytype"||type=="focus_story_price"||type=="focus_story_type"){
		isAction=1;
	}
	//NOT NOW TELL A HAPPY STORY
	if(type=="tell_story_notnow"){
		if(confirm('Are you sure to tell a happy story not now?')){
			isAction=1;
		}
	}
	//CONFIRM TELL A HAPPY STORY
	if(type=="tell_story_confirm"){
		isAction=1;
	}
	//MORE CLASSMATES SECTION
	if(type=="more_classmates"){
		isAction=1;
	}
	//REFRESH TODO LIST
	if(type=="refresh_todo_list"){
		isAction=1;
	}
	//ADD EXPERT CHEER
	if(type=="add_expert_cheers"){
		isAction=1;
	}
	//SWAP TO-DO LIST
	if(type=="swap_todolist"){
		isAction=1;
	}
	//INCREASE EXPERT DAYS OR ITEMS
	if(type=="increase_expdays_items"){
		isAction=1;
	}
	//SEND REQUEST TO TELL A STORY
	if(type=="sendStoryRequest"){
		isAction=1;
	}
	//DELETE EXPERT NOTES
	if(type=="del_expert_note"){
		if(confirm('Are you sure to delete this notes?')){
			isAction=1;
		}
	}
	//ADD EXPERT NOTES 
	if(type=="expert_notes"||type=="add_expert_notes"){
		isAction=1;
	}
	//UPLOAD EXPERTBOARD DOIT USER PICTURE
	if(type=="expertUsr_Doit_Pic"){
		isAction=1;
	}
	//DO-IT EXPERT HAPPINESS
	if(type=="expert_doit"){
		sendRequestStory();
		isAction=1;
	}
	//EDIT EXPERT CUSTOMERS LABLE
	if(type=="edit_exp_customer"){
		isAction=1;
	}
	//EDIT EXPERT REWARD PHOTO
	if(type=="edit_exp_reward_pic"){
		isAction=1;
	}
	//EDIT EXPERT REWARD TITLE
	if(type=="edit_exp_reward_title"){
		isAction=1;
	}
	//DELETE EXPERT REWARD
	if(type=="del_exp_reward"){
		if(confirm('Are you sure to delete this reward?')){
			isAction=1;
		}
	}
	//EDIT EXPERT VIEW MORE LINK
	if(type=="get_more_expert"){
		isAction=1;
	}
	//EDIT EXPERT DETAIL
	if(type=="edt_exp_detail"){
		isAction=1;
	}
	//EDIT EXPERT TITLE
	if(type=="edt_exp_title"){
		isAction=1;
	}
	//EDIT EXPERT PRICE
	if(type=="edt_exp_price"){
		isAction=1;
	}
	//EDIT EXPERT GOAL DAY
	if(type=="edt_exp_goal_day"){
		isAction=1;
	}
	//EDIT EXPERT TO-DO LIST ITEM
	if(type=="edit_todo_list"){
		isAction=1;
	}
	//EDIT EXPERT PHOTO DAY 
	if(type=="edt_exp_photo_day"){
		isAction=1;
	}
	//ASK QUESTION TAB
	if(type=="ask_question"||type=="cherry_answer"||type=="ask_expert_question"||type=="del_expert_question"||type=="del_expert_answer"){
		if(type=="del_expert_question"||type=="del_expert_answer"){
			if(confirm('Are you sure to delete question/answer?')){
				isAction=1;
			}
		}else{
			isAction=1;
		}
	}
	
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
		if(confirm('Are you sure to delete todo list item?')){
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
	if(type=="fb_link_post"||type=="fb_link_post_exp"){
		isAction=1;
	}
	//Edit goal and strikes days
	if(type=="edit_goal_day"){
		isAction=1;
	}
	//SEND THANK YOU EMAIL
	if(type=="sendThankYou"||type=="sendThankYou_Expert"){
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
		
		if(action_type=="focus_story_title"){
			document.getElementById('div_story_category').innerHTML ='<div class="project_left_2">2</div>';
			document.getElementById('div_story_about').innerHTML ='<div class="project_left_2">3</div>';
			document.getElementById('div_story_day_type').innerHTML ='<div class="project_left_2">4</div>';
			document.getElementById('div_story_board_price').innerHTML ='<div class="project_left_2">5</div>';
			document.getElementById('div_story_board_type').innerHTML ='<div class="project_left_2">6</div>';
		}
		if(action_type=="focus_story_category"){
			document.getElementById('div_story_title').innerHTML ='<div class="project_left_2">1</div>';
			document.getElementById('div_story_about').innerHTML ='<div class="project_left_2">3</div>';
			document.getElementById('div_story_day_type').innerHTML ='<div class="project_left_2">4</div>';
			document.getElementById('div_story_board_price').innerHTML ='<div class="project_left_2">5</div>';
			document.getElementById('div_story_board_type').innerHTML ='<div class="project_left_2">6</div>';
		}
		if(action_type=="focus_story_about"){
			document.getElementById('div_story_title').innerHTML ='<div class="project_left_2">1</div>';
			document.getElementById('div_story_category').innerHTML ='<div class="project_left_2">2</div>';
			document.getElementById('div_story_day_type').innerHTML ='<div class="project_left_2">4</div>';
			document.getElementById('div_story_board_price').innerHTML ='<div class="project_left_2">5</div>';
			document.getElementById('div_story_board_type').innerHTML ='<div class="project_left_2">6</div>';
		}
		if(action_type=="focus_story_daytype"){
			document.getElementById('div_story_title').innerHTML ='<div class="project_left_2">1</div>';
			document.getElementById('div_story_category').innerHTML ='<div class="project_left_2">2</div>';
			document.getElementById('div_story_about').innerHTML ='<div class="project_left_2">3</div>';
			document.getElementById('div_story_board_price').innerHTML ='<div class="project_left_2">5</div>';
			document.getElementById('div_story_board_type').innerHTML ='<div class="project_left_2">6</div>';
		}
		if(action_type=="focus_story_price"){
			document.getElementById('div_story_title').innerHTML ='<div class="project_left_2">1</div>';
			document.getElementById('div_story_category').innerHTML ='<div class="project_left_2">2</div>';
			document.getElementById('div_story_about').innerHTML ='<div class="project_left_2">3</div>';
			document.getElementById('div_story_day_type').innerHTML ='<div class="project_left_2">4</div>';
			document.getElementById('div_story_board_type').innerHTML ='<div class="project_left_2">6</div>';
		}
		if(action_type=="focus_story_type"){
			document.getElementById('div_story_title').innerHTML ='<div class="project_left_2">1</div>';
			document.getElementById('div_story_category').innerHTML ='<div class="project_left_2">2</div>';
			document.getElementById('div_story_about').innerHTML ='<div class="project_left_2">3</div>';
			document.getElementById('div_story_day_type').innerHTML ='<div class="project_left_2">4</div>';
			document.getElementById('div_story_board_price').innerHTML ='<div class="project_left_2">5</div>';
		}
		
		<!-- START CUSTOMER HAPPY STORY DIV SECTION -->		
		if(action_type=="focus_story_title"&&div_name=="DivStoryTitle"){
			document.getElementById('DivStoryCategory').innerHTML ='<div class="project_left_2">2</div>';
			document.getElementById('DivStoryAbout').innerHTML ='<div class="project_left_2">3</div>';
			document.getElementById('DivStoryDayType').innerHTML ='<div class="project_left_2">4</div>';
			document.getElementById('DivStoryBoardPrice').innerHTML ='<div class="project_left_2">5</div>';
			document.getElementById('DivStoryBoardType').innerHTML ='<div class="project_left_2">6</div>';
		}
		if(action_type=="focus_story_category"&&div_name=="DivStoryCategory"){
			document.getElementById('DivStoryTitle').innerHTML ='<div class="project_left_2">1</div>';
			document.getElementById('DivStoryAbout').innerHTML ='<div class="project_left_2">3</div>';
			document.getElementById('DivStoryDayType').innerHTML ='<div class="project_left_2">4</div>';
			document.getElementById('DivStoryBoardPrice').innerHTML ='<div class="project_left_2">5</div>';
			document.getElementById('DivStoryBoardType').innerHTML ='<div class="project_left_2">6</div>';
		}
		if(action_type=="focus_story_about"&&div_name=="DivStoryAbout"){
			document.getElementById('DivStoryTitle').innerHTML ='<div class="project_left_2">1</div>';
			document.getElementById('DivStoryCategory').innerHTML ='<div class="project_left_2">2</div>';
			document.getElementById('DivStoryDayType').innerHTML ='<div class="project_left_2">4</div>';
			document.getElementById('DivStoryBoardPrice').innerHTML ='<div class="project_left_2">5</div>';
			document.getElementById('DivStoryBoardType').innerHTML ='<div class="project_left_2">6</div>';
		}
		if(action_type=="focus_story_daytype"&&div_name=="DivStoryDayType"){
			document.getElementById('DivStoryTitle').innerHTML ='<div class="project_left_2">1</div>';
			document.getElementById('DivStoryCategory').innerHTML ='<div class="project_left_2">2</div>';
			document.getElementById('DivStoryAbout').innerHTML ='<div class="project_left_2">3</div>';
			document.getElementById('DivStoryBoardPrice').innerHTML ='<div class="project_left_2">5</div>';
			document.getElementById('DivStoryBoardType').innerHTML ='<div class="project_left_2">6</div>';
		}
		if(action_type=="focus_story_price"&&div_name=="DivStoryBoardPrice"){
			document.getElementById('DivStoryTitle').innerHTML ='<div class="project_left_2">1</div>';
			document.getElementById('DivStoryCategory').innerHTML ='<div class="project_left_2">2</div>';
			document.getElementById('DivStoryAbout').innerHTML ='<div class="project_left_2">3</div>';
			document.getElementById('DivStoryDayType').innerHTML ='<div class="project_left_2">4</div>';
			document.getElementById('DivStoryBoardType').innerHTML ='<div class="project_left_2">6</div>';
		}
		if(action_type=="focus_story_type"&&div_name=="DivStoryBoardType"){
			document.getElementById('DivStoryTitle').innerHTML ='<div class="project_left_2">1</div>';
			document.getElementById('DivStoryCategory').innerHTML ='<div class="project_left_2">2</div>';
			document.getElementById('DivStoryAbout').innerHTML ='<div class="project_left_2">3</div>';
			document.getElementById('DivStoryDayType').innerHTML ='<div class="project_left_2">4</div>';
			document.getElementById('DivStoryBoardPrice').innerHTML ='<div class="project_left_2">5</div>';
		}
		//Refresh Expert Days Or Item Page
		if(action_type=="increase_expdays_items"){
			if(results_array[3]>0){
			ajax_action('exp_photo_refresh','right_container','cherryboard_id='+results_array[3]+'&sort=asc');			
			}
		}
		if(action_type=="expertUsr_Doit_Pic"){
			document.location.href='expert_cherryboard.php?cbid='+results_array[3];
		}
		//expert great message
		if(action_type=="expert_doit"){
			document.getElementById('div_great').innerHTML = results_array[3];
			document.getElementById('div_expowner_picture').innerHTML = results_array[4];
			sendRequest();
		}
		//Refresh Page
		if(action_type=="edt_exp_goal_day"){
			if(results_array[3]=="esave"&&results_array[4]>0){
			ajax_action('exp_photo_refresh','right_container','cherryboard_id='+results_array[4]+'&sort=asc');			
			}
		}
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
			document.getElementById('txt_todolist').value='add something to To-Do List';
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
		
		document.getElementById(div_name).innerHTML=div_content;
		//photo refresh of the board
		if(action_type=="photo_refresh"||action_type=="exp_photo_refresh"){
		    ajax_action('refresh_todo_list','div_todo_list','cherryboard_id='+results_array[3]+'&sort='+results_array[4]);
			document.getElementById('asc_desc_arrow').innerHTML=results_array[5];
			//setupBlocks();			
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
		if(FieldVar[0]=="sponsorship_url"){
			if(ValidURL(FieldValue)==false){
				document.getElementById(divName).innerHTML=FieldMsg;
				return false;
			}
		}else if(FieldVar[0]=="number_days"){
			if(document.getElementById('living_narrative').value==0){
				if(FieldValue==""||FieldValue==0){
					document.getElementById(divName).innerHTML=FieldMsg;
					return false;
				}
			}	
		}else if(FieldVar[0]=="price"){
			if(document.getElementById('is_board_price').value==1){
				if(FieldValue==""||FieldValue==0){
					document.getElementById(divName).innerHTML=FieldMsg;
					return false;
				}
			}
		}else if(FieldVar[0]=="day_type"){
			if(ValidRadio(FieldVar[0])==false){
				document.getElementById(divName).innerHTML=FieldMsg;
				return false;
			}
		}else if(FieldVar[0]=="chk_is_board_price"){
			if(ValidRadio(FieldVar[0])==false){
				document.getElementById(divName).innerHTML=FieldMsg;
				return false;
			}
		}else if(FieldVar[0]=="board_type"){
			if(ValidRadio(FieldVar[0])==false){
				document.getElementById(divName).innerHTML=FieldMsg;
				return false;
			}
		}else if(FieldVar[0]=="story_price"){
			if(document.getElementById('IsBoardPrice').value==1){
				if(FieldValue==""||FieldValue==0){
					document.getElementById(divName).innerHTML=FieldMsg;
					return false;
				}
			}
		}else{
			if(FieldValue==""){
				document.getElementById(divName).innerHTML=FieldMsg;
				return false;
			}else if(FieldValue==FieldVar[2]&&FieldVar[2]!=""){
				document.getElementById(divName).innerHTML=FieldMsg;
				return false;
			}
		}
	}
	return true;
}
//START FUNCTION VALIDATE RADIO BUTTON
function ValidRadio(FieldName){
	var radios = document.getElementsByName(FieldName)
    for (var i = 0; i < radios.length; i++) {
		if (radios[i].checked) {
			return true; // checked
		}
    };
    return false;
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
		alert('Cannot delete the expertboard as there are story boards associated with this expertboard');
		return false;	
	}else{
		return confirm('Are you sure to delete this story boards');	
	}
}
//START AJAX COMMON DATA FUNCTION
function ajax_common_action(type,div_name,stringVar)
{	
	var isAction=0;
	var script_name='ajax_common_data.php';	
	
	//photo refresh of the expert board
	if(type=="set_reward_session"){
		isAction=1;
		//alert(type);
	}	
	if(isAction==1){
		var url = script_name+"?type="+type+"&div_name="+div_name+"&"+stringVar;
		//alert(url);
		http2.open("GET", url , true); 
		http2.onreadystatechange = handleHttpResponse_ajax_common_action; 
		http2.send(null);	
	}
}
function handleHttpResponse_ajax_common_action()
{    
	if (http2.readyState==4) 
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
		if(action_type=='set_reward_session'){
			fb_login();			
		}
		document.getElementById(div_name).innerHTML = div_content;
	  }
	} 
}
//END AJAX COMMON DATA FUNCTION
//START PHOTO INSTAGRAM FILTER
function photo_filter(mtype,file_name,filter_type)
{	
	showLoadingImg('rotate_img');
	var txtcomment=document.getElementById('txtcomment').value;
	if(mtype=="goal"){
		var url = "uploadPhoto.php?type=filter&file_name="+file_name+"&txtcomment="+txtcomment+"&filter_type="+filter_type;
	}else{
		var url = "expert_uploadPhoto.php?type=filter&file_name="+file_name+"&txtcomment="+txtcomment+"&filter_type="+filter_type;	
	}
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_photo_filter; 
	http2.send(null);	
}
function handleHttpResponse_photo_filter()
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
//END PHOTO INSTAGRAM FILTER