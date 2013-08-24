<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>-->
<script type="text/javascript">
	var cherryboard_id=document.getElementById('cherryboard_id').value;
</script>
<?php
$stringVar='';
//$cherryboard_id=(int)$_GET['cherryboard_id'];
$expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
$story_title=trim(getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertboard_id));

if(SCRIPT_NAME=="expert_cherryboard.php"){
	$stringVar='Companions will stand by you to help you stay motivated and inspired.';
}else{
	$stringVar='Share your happy story of "'.$story_title.'" and spread happiness with your friends. select atleast 1 friend.';
}
?>
<script>
	function save_add_cherryboard(){
		var resolution_title=document.getElementById('resolution_title').value;
		if(resolution_title!=""){
			document.frmAddCherry.submit();		
		}else{
			alert('Please, Fillup cherryboard title');
		}
	}
  window.fbAsyncInit = function() {
    FB.init({
      appId: '<?php echo APPID;?>',
      status: true,
      cookie: true,
      oauth: true
    });
  };

  $('#invite_frnd').click(sendRequest);
  function sendRequest() {
    FB.ui({
      method: 'apprequests',
      message: '<?php echo $stringVar; ?>',
      title: 'Select your Companions!',
    },
    function (response) {
      if (response.request && response.to) {
        var request_ids = [];
        for(i=0; i<response.to.length; i++) {
          var temp = response.request + '_' + response.to[i];
          request_ids.push(temp);
        }
        var requests = request_ids.join(',');
       $.post('invite_friends.php',{uid: <?=FB_ID?>,gtype: 'expert', request_ids: requests, cherryboard_key:document.getElementById('cherryboard_key').value,cherryboard_id:document.getElementById('cherryboard_id').value},function(resp) {
	   
         //refresh sent request friends
		  document.getElementById("div_goal_recent_followers").innerHTML = resp;
		  ajax_action('sel_goal_recent_followers','div_goal_recent_followers','cherryboard_id='+document.getElementById('cherryboard_id').value);
        });
      } else {
        alert('canceled');
      }
    });
    return false;
  }
</script>