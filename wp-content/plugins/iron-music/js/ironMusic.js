jQuery(document).ready(function($) {
	
	if ( $('.events-bar').length ) {

		$('.events-bar').each(function() {
			var $bar = $(this);
			var $list = $bar.next('.concerts-list');

			$bar.find('#artists_filter').on('change', function() {

				var artist_id = $(this).val();

				if($bar.hasClass('archive-event'))
				{	
					redirect_event_archive(artist_id);
				}
				else {
					
					if(artist_id == 'all') {
						$list.find('li').fadeIn();
					}else{
						$list.find('li').hide();
		                $list.find('li.artist-'+artist_id).fadeIn();
					}

				}
			});
		});
	}

});

function redirect_event_archive(artist_id) {
	$ = jQuery;
	var $params = getParams();
	var redirect_url = (event_url.indexOf('?') != -1 ) ? window.location.origin+window.location.pathname : event_url;
	$params['artist-id'] = artist_id;
	first_param = true;
	
	$.each($params, function(index, val) {
		if (val != 'all' && index != 'paged') {
			if(first_param) {
				first_param = false;
				redirect_url += '?'+index+'='+val;
			}
			else {
				redirect_url += '&'+index+'='+val;
			}
		}

	});	

	window.location.href=redirect_url;

}

function getParams(){
    var oGetVars = {};

    if (location.search.length > 1) {
      for (var aItKey, nKeyId = 0, aCouples = location.search.substr(1).split("&"); nKeyId < aCouples.length; nKeyId++) {
        aItKey = aCouples[nKeyId].split("=");
        oGetVars[decodeURIComponent(aItKey[0])] = aItKey.length > 1 ? decodeURIComponent(aItKey[1]) : "";
      }
    }

    return oGetVars;
}