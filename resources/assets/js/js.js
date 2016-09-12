//<editor-fold defaultstate="collapsed" desc="EqualHeight">
/*!
 * Simple jQuery Equal Heights
 *
 * Copyright (c) 2013 Matt Banks
 * Dual licensed under the MIT and GPL licenses.
 * Uses the same license as jQuery, see:
 * http://docs.jquery.com/License
 *
 * @version 1.5.1
 */
!function (a) {
	a.fn.equalHeights = function () {
		var b = 0, c = a(this);
		return c.each(function () {
			var c = a(this).innerHeight();
			c > b && (b = c)
		}), c.css("height", b)
	}, a("[data-equal]").each(function () {
		var b = a(this), c = b.data("equal");
		b.find(c).equalHeights()
	})
}(jQuery);
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="PHPJS">
function str_replace(search, replace, subject, countObj) { // eslint-disable-line camelcase
	var i = 0
	var j = 0
	var temp = ''
	var repl = ''
	var sl = 0
	var fl = 0
	var f = [].concat(search)
	var r = [].concat(replace)
	var s = subject
	var ra = Object.prototype.toString.call(r) === '[object Array]'
	var sa = Object.prototype.toString.call(s) === '[object Array]'
	s = [].concat(s)

	var $global = (typeof window !== 'undefined' ? window : GLOBAL)
	$global.$locutus = $global.$locutus || {}
	var $locutus = $global.$locutus
	$locutus.php = $locutus.php || {}

	if (typeof (search) === 'object' && typeof (replace) === 'string') {
		temp = replace
		replace = []
		for (i = 0; i < search.length; i += 1) {
			replace[i] = temp
		}
		temp = ''
		r = [].concat(replace)
		ra = Object.prototype.toString.call(r) === '[object Array]'
	}

	if (typeof countObj !== 'undefined') {
		countObj.value = 0
	}

	for (i = 0, sl = s.length; i < sl; i++) {
		if (s[i] === '') {
			continue
		}
		for (j = 0, fl = f.length; j < fl; j++) {
			temp = s[i] + ''
			repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0]
			s[i] = (temp).split(f[j]).join(repl)
			if (typeof countObj !== 'undefined') {
				countObj.value += ((temp.split(f[j])).length - 1)
			}
		}
	}
	return sa ? s : s[0]
}

function explode(delimiter, string, limit) {
	if (arguments.length < 2 || typeof delimiter === 'undefined' || typeof string === 'undefined')
	{
		return null;
	}
	if (delimiter === '' || delimiter === false || delimiter === null) {
		return false;
	}
	if (typeof delimiter === 'function' || typeof delimiter === 'object' || typeof string === 'function' || typeof string ===
			'object') {
		return {
			0: ''
		};
	}
	if (delimiter === true) {
		delimiter = '1';
	}
	delimiter += '';
	string += '';
	var s = string.split(delimiter);
	if (typeof limit === 'undefined') {
		return s;
	}
	if (limit === 0) {
		limit = 1;
	}
	if (limit > 0) {
		if (limit >= s.length) {
			return s;
		}
		return s.slice(0, limit - 1)
				.concat([s.slice(limit - 1)
							.join(delimiter)
				]);
	}
	if (-limit >= s.length) {
		return [];
	}
	s.splice(s.length + limit);
	return s;
}
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
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="ZBASE COMMONS START">

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
 * Check if function exists
 * @param {type} func
 * @returns {Boolean}
 */
function function_exists(func) {
	if (eval("typeof(" + func + ") == typeof(Function)")) {
		return true;
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
 * Go To URL
 * @param {string} url
 * @returns {void}
 */
function zbase_gotoLocation(url)
{
	window.location = url;
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
 * return the Form Element value
 * @param {object} ele
 * @returns {mixed}
 */
function zbase_get_form_element_value(ele)
{
	var type = jQuery(ele).attr('type');
	if (type === 'radio' || type === 'checkbox')
	{
		if (jQuery(ele).parent().hasClass('checked'))
		{
			return jQuery(ele).val();
		} else {
			return jQuery(ele).val();
		}
	}
	return jQuery(ele).val();
}

/**
 * Form Reset all values
 * @returns {undefined}
 */
function zbase_form_reset(selector)
{
	jQuery(selector).trigger('reset');
}

/**
 * Add checkbox Event
 * @param {string} selector Selector
 * @param {string} event The Event Name
 * @param \Closure cb Callback
 * @returns {undefined}
 */
function zbase_event_checkbox(selector, event, cb)
{
	jQuery(selector).on(event, cb);
}

/**
 *
 * @param {type} type
 * @param {type} msg
 * @param {type} selector
 * @param {type} opt
 * @returns {undefined}
 */
function zbase_toast(type, msg, position, forceToast) {
	if (!forceToast)
	{
		if (jQuery('.alert-content-wrapper').length > 0)
		{
			zbase_alert(type, msg, jQuery('.alert-content-wrapper'), {manipulation: 'prepend'});
			return;
		}
		if (jQuery('.page-content-inner').length > 0)
		{
			zbase_alert(type, msg, jQuery('.page-content-inner'), {manipulation: 'prepend'});
			return;
		}
	}
	if (typeof toastr != 'undefined')
	{
		toastr.options = {
			closeButton: true,
			positionClass: position === undefined ? 'toast-bottom-right' : position,
		}

		if (type == 'error')
		{
			toastr.error(msg);
		}
		if (type == 'warning')
		{
			toastr.warning(msg);
		}
		if (type == 'info')
		{
			toastr.info(msg);
		}
		if (type == 'success')
		{
			toastr.success(msg);
		}
	} else
	{
		zbase_alert(type, msg, jQuery('.page-content-inner'), {manipulation: 'prepend'});
	}
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
	if (selector === undefined)
	{
		selector = jQuery('.page-content-inner');
	}
	if (opt === undefined)
	{
		opt = {manipulation: 'prepend'};
	}
	if (type === 'error')
	{
		type = 'danger';
	}
	var div = jQuery('<div>').addClass('alert alert-' + type).html(msg);
	var manipulation = opt !== undefined && opt.manipulation !== undefined ? opt.manipulation : undefined;
	if (selector !== undefined)
	{
		if (selector.length > 0)
		{
			selector.find('.alert').remove();
		}
		zbase_dom_insert_html(div, selector, manipulation);
	}
	return div;
}

/**
 * Remove all alerts
 * @returns {undefined}
 */
function zbase_alerts_remove()
{
	jQuery('.alert').not('.alert-block').remove();
}

/**
 * Alert a form element
 * @param {type} name
 * @param {type} msg
 * @returns {undefined}
 */
function zbase_alert_form_element(name, msg, formId)
{
	if (formId !== undefined)
	{
		var element = jQuery('#' + formId).find('[name="' + name + '"]');
	}
	if (element === undefined)
	{
		var element = jQuery('[name="' + name + '"]');
	}
	if (element.length > 0)
	{
		if (element.next().hasClass('help-block'))
		{
			if (element.next().text().indexOf(msg) === -1)
			{
				element.next('.help-block').append(' ' + msg);
			}
		} else {
			element.closest('.form-group').addClass('has-error');
			element.addClass('error');
			element.after('<span class="help-block help-block-error">' + msg + '</span>');
		}
	}
}

/**
 * Alert form errors
 * @returns {undefined}
 */
function zbase_alert_form_reset(formObj)
{
	jQuery('.form-group.has-error').removeClass('has-error');
	jQuery('.form-group input').removeClass('error');
	jQuery('.form-group .help-block-error').remove();
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
 * Toggle between 2 elements on action
 * @param e The event eg. click
 * @param ele The selector  to attach the even
 * @param showEle The current viewed selector
 * @param hiddenEle The second selector that is by default, hidden
 * @param selectorsToShow Selectors to show
 * @param selectorsToHide Selectors to hide
 * @param showCb Callback on showing
 * @param hiddenCb Callback on hiding
 * @returns
 */
function zbase_attach_toggle_event(e, ele, showEle, hiddenEle, selectorsToHide, showCb, hiddenCb)
{
	if (jQuery(ele).length > 0)
	{
		jQuery(ele).unbind(e).bind(e, function () {
			jQuery(selectorsToHide).not(showEle).hide();
			if (jQuery(showEle).is(':visible'))
			{
				if (showCb !== undefined && showCb !== null)
				{
					showCb();
				}
				jQuery(hiddenEle).show();
				jQuery(showEle).hide();
			} else {
				if (hiddenCb !== undefined && hiddenCb !== null)
				{
					hiddenCb();
				}
				jQuery(hiddenEle).hide();
				jQuery(showEle).show();
			}
		});
	}
}

/**
 * Show hide Element
 * @returns {undefined}
 */
function zbase_toggle_element(e)
{
	if (jQuery(e).is(':visible'))
	{
		jQuery(e).hide();
	} else {
		jQuery(e).show();
	}
}

//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="LOCALStorage">
/**
 * Save to LocalStorage
 * @param {type} k
 * @param {type} v
 * @returns {undefined}
 */
function saveToLocalStorage(k, v)
{
	localStorage.setItem(k, v);
}
/**
 * Retrieve Ite3m from LocalStorage
 * @param {type} k
 * @returns {DOMString}
 */
function getFromLocalStorage(k)
{
	return localStorage.getItem(k);
}
/**
 * Remove item from LocalStorage
 * @param {type} k
 * @returns {undefined}
 */
function removeFromLocalStorage(k)
{
	localStorage.removeItem(k);
}
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="AJAX">
/**
 * Send to another resource/logcation
 * @param string|object url
 * @returns void
 */
function zbase_to_url(url)
{
	if (jQuery(url).length > 0 && jQuery(url).attr('data-href') !== undefined)
	{
		window.location = jQuery(url).attr('data-href');
		return;
	}
	window.location = url;
}

/**
 * Post
 * @param {string} url The URL
 * @param {object} data Some POSTd Data
 * @param {callback} successCb Success Callback
 * @param {object} opt Options
 * @returns XHRRequest
 */
function zbase_ajax_post(url, data, successCb, opt)
{
	jQuery.ajax({
		type: 'POST',
		url: url,
		data: data,
		beforeSend: function ()
		{
			if (data.loader !== undefined && !data.loader)
			{
				return;
			}
			if (typeof App != "undefined")
			{
				if (opt.loaderTarget !== undefined && jQuery(opt.loaderTarget).length > 0)
				{
					App.blockUI({
						target: opt.loaderTarget,
						cenrerY: true,
						boxed: true
					});
				} else {
					App.blockUI({
						target: jQuery('.page-content-inner'),
						cenrerY: true,
						boxed: true
					});
				}
			}
		},
		complete: function ()
		{
			if (typeof App != "undefined")
			{
				if (opt.loaderTarget !== undefined && jQuery(opt.loaderTarget).length > 0)
				{
					App.unblockUI(opt.loaderTarget);
				} else {
					App.unblockUI(jQuery('.page-content-inner'));
				}
			}
		},
		success: successCb
	});
}
/**
 * GET
 * @param {string} url The URL
 * @param {object} data Some POSTd Data
 * @param {callback} successCb Success Callback
 * @param {object} opt Options
 * @returns XHRRequest
 */
function zbase_ajax_get(url, data, successCb, opt)
{
	jQuery.ajax({
		type: 'GET',
		dataType: 'json',
		url: url,
		data: data,
		beforeSend: function ()
		{
			if (typeof App != "undefined")
			{
				if (opt.loaderTarget !== undefined && jQuery(opt.loaderTarget).length > 0)
				{
					App.blockUI({
						target: opt.loaderTarget,
						cenrerY: true,
						boxed: true
					});
				} else {
					App.blockUI({
						target: jQuery('.page-content-inner'),
						cenrerY: true,
						boxed: true
					});
				}
			}
		},
		complete: function ()
		{
			if (typeof App != "undefined")
			{
				if (opt.loaderTarget !== undefined && jQuery(opt.loaderTarget).length > 0)
				{
					App.unblockUI(opt.loaderTarget);
				} else {
					App.unblockUI(jQuery('.page-content-inner'));
				}
			}
		},
		success: successCb
	});
}

/**
 * Dynamic Ajax Call
 * @param {type} ele
 * @returns {undefined}
 */
function zbase_ajax(dataConfig)
{
	var url = dataConfig.url !== undefined ? dataConfig.url : null;
	if (url === null || url === undefined)
	{
		return;
	}
	var method = dataConfig.method !== undefined ? dataConfig.method : 'get';
	var form = dataConfig.form !== undefined ? dataConfig.form : false;
	var elements = dataConfig.elements !== undefined ? dataConfig.elements : [];
	var callback = dataConfig.callback !== undefined ? dataConfig.callback : null;
	var loaderTarget = dataConfig.loaderTarget !== undefined ? dataConfig.loaderTarget : null;
	var beforeSendCheck = dataConfig.beforeSendCheck !== undefined ? dataConfig.beforeSendCheck : null;
	if (beforeSendCheck !== undefined)
	{
		beforeSendCheck;
	}
	if (!empty(form) && !empty(elements))
	{
		var data = {json: 1};
		jQuery.each(elements, function (i, ele) {
			var name = null;
			if (ele.indexOf('inputByName') >= 0)
			{
				ele = 'input[name="' + explode('=', ele)[1] + '"]:checked';
				name = jQuery(ele).attr('data-name');
			} else {
				name = jQuery(ele).attr('name');
			}
			var val = zbase_get_form_element_value(jQuery(ele));
			eval('data.' + name + ' = \'' + val + '\';');
		});
		zbase_ajax_post(url, data, callback, {loaderTarget: loaderTarget});
	} else {
		if (method === 'get')
		{
			zbase_ajax_get(url, {}, callback, {loaderTarget: loaderTarget});
		} else {
			zbase_ajax_post(url, {}, callback, {loaderTarget: loaderTarget});
		}
	}
}

/**
 * Preloader
 * @returns {undefined}
 */
function zbase_ajax_preloader()
{
	if (jQuery('.zbase-loader-wrapper').length > 0)
	{
		jQuery('.zbase-loader-wrapper').delay(100).fadeIn(100);
	}
}

/**
 * PreLoader
 * @returns {undefined}
 */
function zbase_ajax_preloader_hide()
{
	if (jQuery('.zbase-loader-wrapper').length > 0)
	{
		jQuery('.zbase-loader-wrapper').delay(100).fadeOut(100);
	}
}
//</editor-fold>

/**
 * CAll a dynamic function
 * @param {type} f
 * @returns {undefined}
 */
function zbase_call_function(f)
{
	var fcb = f.replace('-', '').replace('.', '_');
	var fcbc = eval("typeof " + fcb + " == 'function'");
	if (fcbc)
	{
		eval(fcb + '(arguments);');
	}
}
//<editor-fold defaultstate="collapsed" desc="REQUEST">

jQuery.ajaxSetup({
	headers: {'X-CSRF-TOKEN': jQuery('meta[name=_token]').length > 0 ? jQuery('meta[name=_token]').attr('content') : (jQuery('input[name="_token"]').length > 0 ? jQuery('input[name="_token"]').val() : null), 'angular': 1}
});
jQuery(document).ajaxComplete(function (event, request, settings) {
	if (request === undefined)
	{
		return;
	}
	if (request.responseJSON === undefined)
	{
		return;
	}
	var statusCode = request.status !== undefined ? request.status : 200;
	var responseJSON = request.responseJSON;
	if (responseJSON._token !== undefined)
	{
		if (jQuery('meta[name=_token]').length > 0)
		{
			jQuery('meta[name=_token]').attr('content', responseJSON._token);
		}
		if (jQuery('input[name="_token"]').length > 0)
		{
			jQuery('input[name="_token"]').val(responseJSON._token);
		}
	}
	if (responseJSON.redirect !== undefined)
	{
		window.location = responseJSON.redirect;
	}
	if (statusCode == 422)
	{
		jQuery.each(responseJSON, function (idx, content) {
			jQuery.each(content, function (cIdx, cContent) {
				zbase_alert('error', cContent, jQuery('.page-content-inner'), {manipulation: 'prepend'});
				zbase_alert_form_element(idx, cContent);
			});
		});
		return;
	}
	if (responseJSON.success !== undefined)
	{
		zbase_alert('success', responseJSON.success, jQuery('.page-content-inner'), {manipulation: 'prepend'});
	}
	if (responseJSON.error !== undefined)
	{
		zbase_alert('error', responseJSON.error, jQuery('.page-content-inner'), {manipulation: 'prepend'});
	}
	if (responseJSON.warning !== undefined)
	{
		zbase_alert('warning', responseJSON.warning, jQuery('.page-content-inner'), {manipulation: 'prepend'});
	}
	if (responseJSON._alerts !== undefined)
	{
		if (responseJSON._alerts)
		{
			var forceToast = responseJSON._toastit !== undefined ? true : false;
			var toastPosition = responseJSON._toastpos !== undefined ? responseJSON._toastpos : 'toast-bottom-right';
			jQuery.each(responseJSON._alerts.errors, function (i, m) {
				zbase_toast('error', m, toastPosition, forceToast);
			});
			jQuery.each(responseJSON._alerts.info, function (i, m) {
				zbase_toast('info', m, toastPosition, forceToast);
			});
			jQuery.each(responseJSON._alerts.messages, function (i, m) {
				zbase_toast('success', m, toastPosition, forceToast);
			});
			jQuery.each(responseJSON._alerts.warning, function (i, m) {
				zbase_toast('warning', m, toastPosition, forceToast);
			});
		}
	}
	if (responseJSON.errors !== undefined)
	{
		jQuery.each(responseJSON.errors, function (i, m) {
			zbase_alert_form_element(i, m, (responseJSON._formId !== undefined ? responseJSON._formId : undefined));
		});
		return;
	}
	if (responseJSON._html_selector_remove !== undefined)
	{
		jQuery.each(responseJSON._html_selector_remove, function (i, content) {
			jQuery.each(content, function (selector, html) {
				jQuery(selector).remove();
			});
		});
	}
	if (responseJSON._html_selector_replace !== undefined)
	{
		jQuery.each(responseJSON._html_selector_replace, function (i, content) {
			jQuery.each(content, function (selector, html) {
				jQuery(selector).replaceWith(html);
			});
		});
	}
	if (responseJSON._html_selector_html !== undefined)
	{
		jQuery.each(responseJSON._html_selector_html, function (i, content) {
			jQuery.each(content, function (selector, html) {
				jQuery(selector).html(html);
			});
		});
	}
	if (responseJSON._html_selector_append !== undefined)
	{
		jQuery.each(responseJSON._html_selector_append, function (i, content) {
			jQuery.each(content, function (selector, html) {
				jQuery(selector).append(html);
			});
		});
	}
	if (responseJSON._html_selector_prepend !== undefined)
	{
		jQuery.each(responseJSON._html_selector_prepend, function (i, content) {
			jQuery.each(content, function (selector, html) {
				jQuery(selector).prepend(html);
			});
		});
	}
	if (responseJSON._html_selector_show !== undefined)
	{
		jQuery.each(responseJSON._html_selector_show, function (i, content) {
			jQuery.each(content, function (selector) {
				jQuery(selector).show();
			});
		});
	}
	if (responseJSON._html_selector_hide !== undefined)
	{
		jQuery.each(responseJSON._html_selector_hide, function (i, content) {
			jQuery.each(content, function (selector) {
				jQuery(selector).hide();
			});
		});
	}
	if (responseJSON._package !== undefined && responseJSON._route !== undefined)
	{
		var packageRouteCallback = responseJSON._package + "_" + responseJSON._route.replace('-', '').replace('.', '_');
		var packageRouteCallbackCheck = eval("typeof " + packageRouteCallback + " == 'function'");
		if (packageRouteCallbackCheck)
		{
			eval(packageRouteCallback + '(responseJSON);');
		}
	}
	if (responseJSON._widget !== undefined && responseJSON._widget !== undefined)
	{
		var packageRouteCallback = responseJSON._widget.replace('-', '').replace('.', '_');
		var packageRouteCallbackCheck = eval("typeof " + packageRouteCallback + " == 'function'");
		if (packageRouteCallbackCheck)
		{
			eval(packageRouteCallback + '(responseJSON);');
		}
	}
	if (responseJSON._html_script !== undefined && responseJSON._html_script !== '')
	{
		eval(responseJSON._html_script);
	}
	Zbase.init();
	zbase_ajax_preloader_hide();
});
jQuery(document).ajaxError(function (event, request, settings) {
	zbase_ajax_preloader_hide();
	var statusCode = request.status !== undefined ? request.status : 200;
	if (statusCode == 422)
	{
		zbase_toast('warning', 'There was an error in the information you submitted. Kindly check.');
	} else {
		zbase_toast('error', 'There was an error processing your request. Kindly try again later.');
	}
});
jQuery(document).ajaxSend(function (event, request, settings) {
	zbase_alert_form_reset();
	if (settings.data !== undefined && (typeof settings.data == 'string') && settings.data.indexOf('loader=false') == 0)
	{
		zbase_ajax_preloader_hide();
	} else {
		zbase_ajax_preloader();
	}
	zbase_alerts_remove();
});
jQuery(document).ajaxStart(function () {

});
jQuery(document).ajaxStop(function () {
	zbase_ajax_preloader_hide();
	if (typeof App != "undefined")
	{
		App.unblockUI(jQuery('.page-content-inner'));
	}
});
jQuery(document).ajaxSuccess(function (event, request, settings) {
});
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Zbase">
var Zbase = function () {
	_this = this;
	_this.prefix = 'zbase';

	var initIntervalUpdates = function () {
		if (jQuery('.zbase-ajax-interval').length > 0)
		{
			jQuery('.zbase-ajax-interval').each(function (i, e) {
				var ele = jQuery(ele);
				var url = ele.attr('data-url');
				var time = ele.attr('data-mstime');
				var func = ele.attr('data-funcname');
				if (time === undefined)
				{
					time = 60000 * 5;
				}
				if (url !== undefined)
				{
					var method = ele.attr('data-method');
					if (method == 'post')
					{
						window.setInterval(zbase_ajax_post(url, {}, null, {}), time);
					} else {
						window.setInterval(zbase_ajax_get(url, {}, function () {}, {}), time);
					}
				}
				if (func !== undefined)
				{
					window.setInterval(func, time);
				}
			});
		}
	}
	var initFormControls = function () {}
	var initAjaxForm = function () {
		if (jQuery('.zbase-ajax-form').length > 0)
		{
			jQuery('.zbase-ajax-form').unbind('submit').submit(function (e) {
				e.preventDefault();
				var ele = jQuery(this);
				var url = ele.attr('action');
				if (!empty(url))
				{
					zbase_ajax_post(url, ele.serialize(), null, {});
				}
			});
		}
	}

	/**
	 * Confirmation buttons
	 * @returns void
	 */
	var initBtnActionConfirm = function () {
		if (jQuery('.zbase-btn-action-confirm').length > 0)
		{
			jQuery('.zbase-btn-action-confirm').unbind('click').click(function (e) {
				e.preventDefault();
				e.stopPropagation();
				var btn = jQuery(this);
				var dataConfig = zbase_get_element_config(btn);
				var dataMode = dataConfig.mode !== undefined ? dataConfig.mode : 'yesno';
				var alertType = dataConfig.alertType !== undefined ? dataConfig.alertType : 'error';
				var message = dataConfig.message !== undefined ? dataConfig.message : null;
				var url = dataConfig.url !== undefined ? dataConfig.url : null;
				var callback = dataConfig.callback !== undefined ? dataConfig.callback : null;
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
						jQuery(btn).siblings().hide();
						var btnNo = jQuery('<button type="button" class="btn btn-no btn-success ' + zbase_get_element_size(btn) + '">No</button>');
						var btnYes = jQuery('<button type="button" class="btn btn-yes btn-danger ' + zbase_get_element_size(btn) + '">Yes</button>');
						var btnDiv = jQuery('<div>').html(btnNo.outerHtml() + btnYes.outerHtml());
						jQuery(btnDiv).find('.btn-no').unbind('click').click(function () {
							btnDiv.remove();
							divAlert.remove();
							jQuery(btn).show();
							jQuery(btn).siblings().show();
						});
						jQuery(btnDiv).find('.btn-yes').unbind('click').click(function () {
							zbase_ajax_post(url, {}, callback, {});
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

	/**
	 * Check URLs if they are to be Ajaxed
	 * @returns {void}
	 */
	var initAjaxFromUrls = function ()
	{
		if (jQuery('.zbase-ajax-url').length > 0)
		{
			jQuery('.zbase-ajax-url').unbind('click').click(function (e) {
				e.preventDefault();
				zbase_ajax(zbase_get_element_config(jQuery(this)));
			});
		}
		if (jQuery('.zbase-ajax-anchor').length > 0)
		{
			jQuery('.zbase-ajax-anchor').unbind('click').click(function (e) {
				e.preventDefault();
				zbase_ajax_get(jQuery(this).attr('href'), {}, function () {}, {});
			});
		}
		if (jQuery('.zbase-ajax-update-main-content').length > 0)
		{
			jQuery('.zbase-ajax-update-main-content').unbind('click').click(function (e) {
				e.preventDefault();
				zbase_ajax_get(jQuery(this).attr('href'), {maincontent: 1}, function () {}, {});
			});
		}
	};

	/**
	 * Clickable Text/Btns
	 * @returns {void}
	 */
	var initClickableUrls = function ()
	{
		if (jQuery('.zbase-btn-clickable-url').length > 0)
		{
			jQuery('.zbase-btn-clickable-url').unbind('click').click(function (e) {
				var ele = jQuery(this);
				var dataConfig = zbase_get_element_config(ele);
				var url = dataConfig.url !== undefined ? dataConfig.url : ele.attr('href');
				window.location = url;
			});
		}
	};

	/**
	 * TABS
	 * @returns {void}
	 */
	var initTabs = function ()
	{
		if (jQuery().tab) {
			jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				/**
				 * Nested Tabs
				 */
				localStorage.removeItem(_this.prefix + 'firstTab');
				localStorage.removeItem(_this.prefix + 'secondTab');
				if (jQuery(this).closest('form').find('.nav-tabs li.active').length > 1)
				{
					jQuery('.nav-tabs li.active a').each(function (i, e) {
						if (i == 0)
						{
							localStorage.setItem(_this.prefix + 'firstTab', jQuery(e).attr('href'));
						} else {
							localStorage.setItem(_this.prefix + 'secondTab', jQuery(e).attr('href'));
						}
					});
				} else {
					localStorage.setItem(_this.prefix + 'firstTab', jQuery(this).attr('href'));
				}
			});
			var firstTab = localStorage.getItem(_this.prefix + 'firstTab');
			var secondTab = localStorage.getItem(_this.prefix + 'secondTab');
			if (firstTab) {
				jQuery('[href="' + firstTab + '"]').tab('show');
				if (secondTab) {
					jQuery('[href="' + secondTab + '"]').tab('show');
				}
			}
			if (window.location.hash !== undefined && window.location.hash !== '')
			{
				jQuery('a[href="' + window.location.hash + '"]').trigger('click');
			}
		}
	};

	/**
	 * Initialize Datatable
	 * @returns {undefined}
	 */
	var initDatatable = function () {
		if (jQuery('.zbase-datatable-row-toggle').length > 0)
		{
			jQuery('.zbase-datatable-row-toggle').unbind('click').click(function () {
				var r = jQuery(this);
				if (r.next().hasClass('zbase-datatable-row-toggle-copy'))
				{
					if (r.next().is(':visible'))
					{
						r.next().hide();
					} else {
						r.next().show();
					}
					return;
				}
				var url = r.attr('data-href');
				var rId = r.attr('id');
				var dataContent = r.attr('data-content');
				if (url !== null && url !== undefined)
				{
					if (dataContent !== undefined)
					{
						zbase_ajax_post(url, {_innercontent: 1, _innerContentId: rId, _datatableRow: 1}, function () {}, {});
					} else {
						var tdCount = r.find('td').length;
						var rId = r.attr('id');
						var newRTpl = '<tr class="zbase-datatable-row-toggle-copy"><td colspan="' + tdCount + '"><div class="zbase-datatable-row-toggle-copy-wrapper" id="rowCopy' + rId + '"></div></td></tr>';
						r.after(newRTpl);
						zbase_ajax_post(url, {}, function () {}, {loaderTarget: r.next().find('td')});
					}
				}
			});
		}
	};
	var handleFancybox = function () {
		if (!jQuery.fancybox) {
			return;
		}
		if (jQuery(".fancybox-button").size() > 0) {
			jQuery(".fancybox-button").fancybox({
				groupAttr: 'data-rel',
				prevEffect: 'none',
				nextEffect: 'none',
				closeBtn: true,
				helpers: {
					title: {
						type: 'inside'
					}
				}
			});
		}
	}

	// Handles custom checkboxes & radios using jQuery Uniform plugin
	var handleUniform = function () {
		if (!jQuery().uniform) {
			return;
		}
		var test = $("input[type=checkbox]:not(.toggle, .make-switch), input[type=radio]:not(.toggle, .star, .make-switch)");
		if (test.size() > 0) {
			test.each(function () {
				if ($(this).parents(".checker").size() == 0) {
					$(this).show();
					$(this).uniform();
				}
			});
		}
	}

	return {
		init: function () {
			var_dump('Zbase Initializing...');
			initIntervalUpdates();
			jQuery('.equalHeight').equalHeights();
			initTabs();
			initDatatable();
			initFormControls();
			initAjaxForm();
			initBtnActionConfirm();
			initContentFromUrl();
			initAjaxFromUrls();
			initClickableUrls();
			handleFancybox();
			handleUniform();
		}
	};
}();
//</editor-fold>