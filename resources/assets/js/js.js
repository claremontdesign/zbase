// PHP JS START
/**
 *
 * @param {type} mixed_var
 * @returns {Boolean}
 */
function empty(mixed_var) {
	var undef, key, i, len;
	var emptyValues = [undef, null, false, 0, '', '0'];

	for (i = 0, len = emptyValues.length; i < len; i++) {
		if (mixed_var === emptyValues[i]) {
			return true;
		}
	}
	if (typeof mixed_var === 'object') {
		for (key in mixed_var) {
			return false;
		}
		return true;
	}
	return false;
}
// PHP JS END



$.ajaxSetup({
	headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')}
});
$(document).ajaxComplete(function (event, request, settings) {
	var responseText = jQuery(request.responseText);
	if (!empty(responseText.find('input[name="_token"]')[0].defaultValue))
	{
		var _token = responseText.find('input[name="_token"]')[0].defaultValue;
		if (!empty(_token))
		{
			$('meta[name=_token]').attr('content', _token);
		}
	}
});
$(document).ajaxError(function (event, request, settings) {
});
$(document).ajaxSend(function (event, request, settings) {
});
$(document).ajaxStart(function () {
});
$(document).ajaxStop(function () {
});
$(document).ajaxSuccess(function (event, request, settings) {
});