/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function nv_topic_del(a) {
	if( confirm(nv_is_del_confirm[0]) )
	{
		$.get(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topic&del=1&nocache=' + new Date().getTime(), 'tid=' + a + '&num=' + nv_randomPassword(8), function(res) {
			nv_cat_del_result(res);
		});
	}
	return !1
}

function nv_cat_del_result(a) {
	"OK" == a ? window.location.href = window.location.href : alert(nv_is_del_confirm[2]);
	return !1
}

function nv_chang_weight(a) {
	nv_settimeout_disable("weight" + a, 5E3);
	var b = document.getElementById("weight"+a).options[document.getElementById("weight" + a).selectedIndex].value;
	$.get(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topic&changeweight=1&nocache=' + new Date().getTime(), 'tid=' + a + '&new=' + b + '&num=' + nv_randomPassword(8), function(res) {
		nv_chang_weight_result(res);
	});
}

function nv_chang_weight_result(a) {
	"OK" != a && alert(nv_is_change_act_confirm[2]);
	clearTimeout(nv_timer);
	window.location.href = window.location.href
}

function nv_chang_status(a) {
	nv_settimeout_disable("change_status" + a, 5E3);
	$.get(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topic&changestatus=1&nocache=' + new Date().getTime(), 'tid=' + a + '&num=' + nv_randomPassword(8), function(res) {
		nv_chang_status_res(res);
	});
}

function nv_chang_status_res(a) {
	"OK" != a && (alert(nv_is_change_act_confirm[2]), window.location.href = window.location.href)
};

sum = 0;
sumN = 0;
var nextPageToken;

function getVids(key_ss, key_list, id, PageToken){
    // pid = 'FLAd5v-uqjtuIVMQmfX_WF_g';
    pid = id;
    $.get(
        "https://www.googleapis.com/youtube/v3/playlistItems",{
        part : 'snippet', 
        maxResults : 50,
        playlistId : pid,
        pageToken : PageToken,
        key: key_ss
        },
        function(data){
              myPlan(key_ss, key_list, id, data);
        }        
    );  
 }

  function myPlan(key_ss, key_list, id, data){
      total = data.pageInfo.totalResults;
      nextPageToken=data.nextPageToken;
//      data.pageInfo.totalResults
      for(i=0;i<data.items.length;i++){
//          console.log(data.items[i].snippet);
          
//          document.getElementById('area1_'+id).innerHTML += '<tr><td>'+data.items[i].snippet.title+'</td><td>'+data.items[i].snippet.resourceId.videoId+'</td></tr>';
          document.getElementById('area1_'+id).innerHTML += '<tr><td>'+data.items[i].snippet.title+'</td><td>'+data.items[i].snippet.resourceId.videoId+'</td></tr><tr class="hide"><td><input type="text" name="q'+key_list+'['+sum+'][id_playlist]" value="'+data.items[i].snippet.playlistId+'"> - <input type="text" name="q'+key_list+'['+sum+'][thumbnail]" value="'+data.items[i].snippet.thumbnails.medium.url+'"> - <input type="text" name="q'+key_list+'['+sum+'][title]" value="'+data.items[i].snippet.title+'"> - <input type="text" name="q'+key_list+'['+sum+'][description]" value="'+data.items[i].snippet.description+'"> - <input type="text" name="q'+key_list+'['+sum+'][videoid]" value="'+data.items[i].snippet.resourceId.videoId+'"></td></tr>';
          sum++ ; sumN++;
          if(sum == (total) ){              
              sum = 0;  
              return;      
          }
      }  
      if(sum <(total)){
          getVids(key_ss, key_list, id, nextPageToken);
      }    
 }