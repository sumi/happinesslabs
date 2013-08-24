<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>-->
<?php
/*if(isset($_GET['cherryboard_id'])){
	$cherryboard_id=(int)$_GET['cherryboard_id'];
}
echo "CBID :".$cherryboard_id;*/
$stringVar='';
if(SCRIPT_NAME=="ask_experts.php"){
	$stringVar='Your friends will stand by you to help motivate and inspire you';
}else{
	$stringVar='Ask your friends to share their happy stories. Select atleast 1 friend.';
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

  $('#invite_frnd').click(sendRequestStory);
  function sendRequestStory() {
    FB.ui({
      method: 'apprequests',
      message: '<?php echo $stringVar; ?>',
      title: 'Select your friends!',
    },
    function (response) {
      if (response.request && response.to) {
        var request_ids = [];
        for(i=0; i<response.to.length; i++) {
          var temp = response.request + '_' + response.to[i];
          request_ids.push(temp);
        }
       var requests = request_ids.join(',');
       $.post('invite_friends.php',{uid: <?=FB_ID?>,gtype:'request', request_ids: requests, cherryboard_key:document.getElementById('cherryboard_key').value,cherryboard_id:document.getElementById('cherryboard_id').value},function(resp) {
          //refresh sent request friends
		  //alert(resp); //print response		  //ajax_action('sel_goal_recent_friends','div_goal_recent_friends','cherryboard_id='+document.getElementById('cherryboard_id').value);
        });
      } else {
        alert('canceled');
      }
    });
    return false;
  }
</script>