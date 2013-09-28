<script type="text/javascript"> 
  $(document).ready(function(){
    var counter = 0;
    var mouseX = 0;
    var mouseY = 0;
    
    $("#imgtag img").click(function(e){ // make sure the image is click
      var imgtag = $(this).parent(); // get the div to append the tagging list
      mouseX = e.pageX - $(imgtag).offset().left; // x and y axis
      mouseY = e.pageY - $(imgtag).offset().top;
      $('#tagit').remove(); // remove any tagit div first
      $(imgtag).append('<div id="tagit"><div class="box"></div><div class="name"><div class="text">Add Tag</div><input type="text" name="txtname" id="tagname" /><div class="text">Add Type</div><?=getTagType()?><span id="uploadMask"><input type="file" multiple="multiple" name="avatar" id="avatar"><img src="images/upload_tag.png" alt="Upload Photo" /></span><input type="button" class="btn" name="btnsave" value="Save" id="btnsave" /><input type="button" class="btn" name="btncancel" value="Cancel" id="btncancel" /></div></div>');
      $('#tagit').css({top:mouseY,left:mouseX});      
      $('#tagname').focus();
    });
    
	$('#tagit #btnsave').live('click',function(){
	var file_data=$("#avatar").attr("files")[0];//prop ==OR==> attr
	name=$('#tagname').val();
	tagtype=$('#tag_type_id').val();
	var pic_id=document.getElementById('pic_id').value;
	var form_data=new FormData();
	form_data.append("file",file_data)
	form_data.append("pic_id",pic_id)
	form_data.append("name",name)
	form_data.append("tagtype",tagtype)
	form_data.append("pic_x",mouseX)
	form_data.append("pic_y",mouseY)	
	
	$.ajax({
		url:"savetag.php?type=insert",
		dataType:'script',
		cache:false,
		contentType:false,
		processData:false,
		data:form_data,
		type:'POST',
		success: function(data){
		  viewtag(pic_id);
		  $('#tagit').fadeOut();
		}
    })
    })    
     $('#tagit #btncancel').live('click',function(){
      $('#tagit').fadeOut();
      
    });
	
	$('#divHover').live('mouseover mouseout',function(event){
      id=$(this).attr("rel");
      if (event.type=="mouseover"){
        $('#view_'+id).show();
      }else{
        $('#view_'+id).hide();
      }
    });
  });
  function viewtag(pic_id){
  	$.ajax({
		type: "POST", 
		url: "savetag.php", 
		data: "pic_id="+pic_id+"&type=display",
		cache: true, 
		success: function(data){
		  $('#div_hover_'+pic_id).html(data);	  
		}
	});
  }
  function setPicId(pic_id){
  	document.getElementById('pic_id').value=pic_id;
  }
</script>