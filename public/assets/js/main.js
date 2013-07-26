var errors = [];
errors.container = $('#errors');
errors.invalid_url = "Sorry provided link is invalid";
errors.not_reddit = "Sorry this is not a valid Reddit.com link."

var validUrlExpression = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/i;

var $url

var search = function(){
	$url = $("#url");
	// add our events
	$url.bind('process', process);

	link = $url.val();

	if(!validUrl(link))
	{
		showError(errors.invalid_url);
		return;
	}

	var url_encoded = encodeURIComponent(link);

	makeRequest(url_encoded, $url)
}
/**
 * make ajax request to gater data and generate a winne
 * @param  {sting} url url to contest
 * @param  {obj} elm elm we are targeting
 * @return void
 */
var makeRequest = function(url, elm){
	$.ajax({
		dataType:'JSON',
		url :'/search',
		data : {url : url},
		success : function(data){
			elm.trigger('process', data);
		}
	});
}

/**
 * processes post and adds data to
 * @param  object e    event
 * @param  JSON	 data  orginal post &  winner post data
 * @return void
 */
var process = function(e,data){
 	if(!data.success)
 	{
 		showError(data.error);
 	}

 	var $op = $("#orginalPost");
 	var $win = $("#winner");


 	$op.find("#op_title").html(data.post.title);
 	$op.find('#op_content').html(data.post.content);

	$win.find('#win_author').html(data.winner.author);
	$win.find('#win_content').html(data.winner.content);

	$win.fadeIn('fast');
	$win.data('post-id',data.winner.postId);
}

/**
 * makes sure the URL is valid
 * @param  {string} $url Url to make request too
 * @return {bool}      is valid?
 */
var validUrl= function(url){
	return validUrlExpression.test(url);
}

/**
 * shows an error messange and then fades it out
 * @param  {[type]} message [description]
 * @return {[type]}         [description]
 */
var showError = function(message){
	errors.container.html(message);
	errors.container.fadeIn('fast').delay(3000).fadeOut('slow');
	return;
}

var queryObj  = function() {
    var result = {}, keyValuePairs = location.search.slice(1).split('&');

    keyValuePairs.forEach(function(keyValuePair) {
        keyValuePair = keyValuePair.split('=');
        result[keyValuePair[0]] = keyValuePair[1] || '';
    });
    return result;
}

var selectWinner = function(){
  alert('still working on this feature');
  alert('Thank you for trying this out and I am welcome to any feed back you have!');
}



$(document).ready(function(){
	$('#search').submit(function(e){
			e.preventDefault();
			search();
	});

	var queryString = queryObj();
	//todo -- clean this nasty up
	if(queryString.url != undefined ){
		url = decodeURIComponent(queryString.url);
		if(validUrl(url))
		{
			$("#url").val(url);
		}
	}

	$('#searchAgain').click(function(){ search(); });
	$('#selectWinner').click(function(){selectWinner(); });

});