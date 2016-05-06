/**
 * Amazon Reflink JavaScript functions.
 * 
 * @author Matthias Kittsteiner
 * @copyright 2016 Matthias Kittsteiner
 * @license <internal>
 */
$(function() {
	var javaScriptContent = $('#javaScriptContent');
	var page = $(document);
	
	// submit form without reload
	page.on('submit', '#form', function(event) {
		event.preventDefault();
		
		var currentTarget = $(this);
		var postData = currentTarget.serializeArray();
		
		javaScriptContent.show();
		javaScriptContent.html('<div class="loading"><img src="images/loading.gif" alt="Lädt …" /></div>');
		
		$.ajax({
			url: currentTarget.attr('action'),
			type: 'POST',
			data: postData,
			dataType: 'json',
			cache: false,
			success: function(data) {
				var content = data;
				var htmlContent = '';
				
				if (content.length > 1) {
					htmlContent = '<h3>Umgewandelte Links:</h3>';
				}
				else if (typeof content.length !== 'undefined') {
					htmlContent = '<h3>Umgewandelter Link:</h3>';
				}
				
				$.each(content, function(index, value) {
					if (value['link'].length <= 1) {
						javaScriptContent.text('Keine URL angegeben.');
					}
					else {
						htmlContent = htmlContent + '<a href="' + value['link'] + '">' + value['title'] + '</a> <span class="light">&ndash; Referrer: ' + value['refcode'] + '</span><br />';
						javaScriptContent.html(htmlContent);
					}
				});
			}
		});
	});
	
	// click on statistics month
	$('.date').click(function(event) {
		$(this).children('ul').animate({
			'height': 'toggle'
		});
	});
});

/**
 * Automatic content-dependend calculation of textarea size.
 * 
 * @param	object		element
 */
function autoGrow(element) {
	if (element.scrollHeight > 70) {
		element.style.height = '5px';
		element.style.height = (element.scrollHeight) + 'px';
	}
}

/**
 * Strip URL content.
 * 
 * @param	string		$url
 * @return	string
 */
function stripUrl(url) {
	var urlWithoutProtocol = url.replace('/([\w]+:\/\/)/i', '');
	
	if (urlWithoutProtocol.length > 60) {
		var title = urlWithoutProtocol.substr(0, 45) + '&hellip;' + urlWithoutProtocol.substr(-15);
		
		return title;
	}
	else {
		return urlWithoutProtocol;
	}
}