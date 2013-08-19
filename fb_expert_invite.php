<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>-->
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
      message: 'Companions will stand by you to help you stay motivated and inspired.',
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