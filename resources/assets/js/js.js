// PHP JS START
function in_array(needle, haystack, argStrict) {
	var key = '',
			strict = !!argStrict;
	if (strict) {
		for (key in haystack) {
			if (haystack[key] === needle) {
				return true;
			}
		}
	} else {
		for (key in haystack) {
			if (haystack[key] == needle) {
				return true;
			}
		}
	}

	return false;
}
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
//
jQuery.fn.outerHtml = function (s) {
	return s ? this.before(s).remove() : jQuery("<p>").append(this.eq(0).clone()).html();
};
//
// ZBASE COMMONS START
/**
 * Laravel dd type. I'm used of using dd
 * @param {string} v
 * @returns void
 */
function dd(v) {
	var_dump(v);
}
function var_dump(v) {
	if (window.console) {
		console.log(v);
	}
}
/**
 * Return a value from a JSON Object by Key
 * @param {object} obj
 * @param {string} key
 * @returns {string|integer|undefined}
 */
function zbase_json_by_key(obj, key)
{
	if (obj[key] !== undefined)
	{
		return obj[key];
	}
	return undefined;

}
/**
 * Return a Random String
 * @param {int} len The Length of string to generate
 * @returns {String}
 */
function zbase_random_string(len)
{
	if (len === undefined)
	{
		len = 5;
	}
	var t = '';
	var alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	for (var i = 0; i < len; i++)
	{
		t += alpha.charAt(Math.floor(Math.random() * alpha.length));
	}
	return t;
}
/**
 * REturn a Data Attribute (data-suffix) of an element
 *
 * @param {object} obj The Object in Question
 * @param {string} suffix The suffix
 * @returns string|undefined
 */
function zbase_get_element_data(obj, suffix)
{
	return jQuery(obj).attr('data-' + suffix) !== undefined ? jQuery(obj).attr('data-' + suffix) : undefined;
}
/**
 * Return a Configuration object from a data-config attribute.
 *
 * @param {object} obj The Object in Question
 * @returns object|null
 */
function zbase_get_element_config(obj)
{
	var configString = zbase_get_element_data(obj, 'config');
	return configString !== undefined ? eval('(' + configString + ')') : {};
}

/**
 * Return the element size of the given object
 *	Bootstrap size; http://www.w3schools.com/bootstrap/bootstrap_buttons.asp
 * @param {object} obj
 * @returns {string}
 */
function zbase_get_element_size(obj)
{
	if (jQuery(jQuery(obj).outerHtml())[0].nodeName === 'BUTTON')
	{
		if (jQuery(obj).hasClass('btn-lg'))
		{
			return 'btn-lg';
		}
		if (jQuery(obj).hasClass('btn-md'))
		{
			return 'btn-md';
		}
		if (jQuery(obj).hasClass('btn-sm'))
		{
			return 'btn-sm';
		}
		if (jQuery(obj).hasClass('btn-xs'))
		{
			return 'btn-xs';
		}
	}
	return '';
}
/**
 * Return Checkbox Value
 * @param {string} selector The checkbox selector
 * @returns {undefined|jQuery}
 */
function zbase_get_checkbox_value(selector)
{
	if (jQuery(selector + ':checked').length > 0)
	{
		return jQuery(selector + ':checked').val();
	}
	return undefined;
}
/**
 * Add checkbox Event
 * @param {string} selector Selector
 * @param {string} event The Event Name
 * @param {object} cb Callback
 * @returns {undefined}
 */
function zbase_event_checkbox(selector, event, cb)
{
	jQuery(selector).on(event, cb);
}
/**
 *
 * @param {string} type Type of alert
 * @param {string} msg The message to display
 * @param {object} selector Selector to insertBefore the alert
 * @param {object} opt options
 *	opt.selector = Will be htmled to the selector
 * @returns object The Alert DOM Object
 */
function zbase_alert(type, msg, selector, opt)
{
	if (type === 'error')
	{
		type = 'danger';
	}
	var div = jQuery('<div>').addClass('alert alert-' + type).html(msg);
	var manipulation = opt.manipulation !== undefined ? opt.manipulation : undefined;
	zbase_dom_insert_html(div, selector, manipulation);
	return div;
}

/**
 * Insert an HTMl to a given selector by Mode
 * @param {string} html The HTML to insert
 * @param {object|string} selector The selector to insert the HTML
 * @param {string} mode Mode of insert
 * @returns void
 */
function zbase_dom_insert_html(html, selector, mode)
{
	if (mode !== undefined)
	{
		if (mode.toLowerCase() === 'insertafter')
		{
			jQuery(html).insertAfter(selector);
		}
		if (mode.toLowerCase() === 'insertbefore')
		{
			jQuery(html).insertBefore(selector);
		}
		if (mode.toLowerCase() === 'after')
		{
			jQuery(selector).after(html);
		}
		if (mode.toLowerCase() === 'before')
		{
			jQuery(selector).before(html);
		}
		if (mode.toLowerCase() === 'append')
		{
			jQuery(selector).append(html);
		}
		if (mode.toLowerCase() === 'appendto')
		{
			jQuery(html).appendTo(selector);
		}
		if (mode.toLowerCase() === 'prepend')
		{
			jQuery(selector).prepend(html);
		}
		if (mode.toLowerCase() === 'prependto')
		{
			jQuery(html).prependTo(selector);
		}
		if (mode.toLowerCase() === 'replaceall')
		{
			jQuery(html).replaceAll(selector);
		}
		if (mode.toLowerCase() === 'replacewith')
		{
			jQuery(selector).replaceWith(html);
		}
	}
}

/**
 * Post
 * @param {string} url The URL
 * @param {object} data Some POSTd Data
 * @param {callback} success Success Callback
 * @param {object} opt Options
 * @returns void
 */
function zbase_ajax_post(url, data, success, opt)
{
	jQuery.ajax({
		type: 'POST',
		url: url,
		data: data,
		success: function (data)
		{
			return data;
		}
	});
}
/**
 * GET
 * @param {string} url The URL
 * @param {object} data Some POSTd Data
 * @param {callback} success Success Callback
 * @param {object} opt Options
 * @returns void
 */
function zbase_ajax_get(url, data, success, opt)
{
	jQuery.ajax({
		type: 'GET',
		dataType: 'json',
		url: url,
		data: data,
		success: success
	});
}


// ZBASE COMMONS END
jQuery.ajaxSetup({
	headers: {'X-CSRF-TOKEN': jQuery('meta[name=_token]').attr('content')}
});
jQuery(document).ajaxComplete(function (event, request, settings) {
	var responseJSON = request.responseJSON;
	if (responseJSON._token !== undefined)
	{
		jQuery('meta[name=_token]').attr('content', responseJSON._token);
	}
	if (responseJSON.redirect !== undefined)
	{
		window.location = responseJSON.redirect;
	}
});
jQuery(document).ajaxError(function (event, request, settings) {
});
jQuery(document).ajaxSend(function (event, request, settings) {
});
jQuery(document).ajaxStart(function () {
});
jQuery(document).ajaxStop(function () {
});
jQuery(document).ajaxSuccess(function (event, request, settings) {
});

//<editor-fold defaultstate="collapsed" desc="Zbase">
var Zbase = function () {
	_this = this;

	/**
	 * Confirmation buttons
	 * @returns void
	 */
	var initBtnActionConfirm = function () {
		if (jQuery('.zbase-btn-action-confirm').length > 0)
		{
			jQuery('.zbase-btn-action-confirm').click(function (e) {
				e.preventDefault();
				var btn = jQuery(this);
				var dataConfig = zbase_get_element_config(btn);
				var dataMode = dataConfig.mode !== undefined ? dataConfig.mode : 'yesno';
				var alertType = dataConfig.alertType !== undefined ? dataConfig.alertType : 'error';
				var message = dataConfig.message !== undefined ? dataConfig.message : null;
				var url = dataConfig.url !== undefined ? dataConfig.url : null;
				var opt = {manipulation: 'insertBefore'};
				if (message !== null && url !== null)
				{
					if (dataMode === 'yesno')
					{
						/**
						 * Display an alert
						 * Hide this element
						 * Replace with Yes-No Buttons
						 */
						var divAlert = zbase_alert(alertType, message, jQuery(btn).parent(), opt);
						jQuery(btn).hide();
						var btnNo = jQuery('<button type="button" class="btn btn-no btn-success ' + zbase_get_element_size(btn) + '">No</button>');
						var btnYes = jQuery('<button type="button" class="btn btn-yes btn-danger ' + zbase_get_element_size(btn) + '">Yes</button>');
						var btnDiv = jQuery('<div>').html(btnNo.outerHtml() + btnYes.outerHtml());
						jQuery(btnDiv).find('.btn-no').click(function () {
							btnDiv.remove();
							divAlert.remove();
							jQuery(btn).show();
						});
						jQuery(btnDiv).find('.btn-yes').click(function () {
							zbase_ajax_post(url, {}, null, {});
						});
						zbase_dom_insert_html(btnDiv, btn, 'insertbefore');
					}
				}
			});
		}
	};

	/**
	 * Load contents from URL
	 * @returns {void}
	 */
	var initContentFromUrl = function ()
	{
		if (jQuery('.zbase-content-url').length > 0)
		{
			jQuery('.zbase-content-url').each(function () {
				var ele = jQuery(this);
				var dataConfig = zbase_get_element_config(ele);
				var url = dataConfig.url !== undefined ? dataConfig.url : null;
				var method = dataConfig.method !== undefined ? dataConfig.method : 'get';
				var htmlIndex = dataConfig.htmlIndex !== undefined ? dataConfig.htmlIndex : null;
				if (!empty(url))
				{
					if (method === 'post')
					{
						zbase_ajax_post(url, {}, null, {});
					} else {
						zbase_ajax_get(url, {}, function (data) {
							if (!empty(data.html))
							{
								ele.html(zbase_json_by_key(data.html, htmlIndex));
							}
						}, {});
					}
				}
			});
		}
	};
	return {
		init: function () {
			initBtnActionConfirm();
			initContentFromUrl();
		}
	};
}();
//</editor-fold>