/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Function to run after File Delete AJAX POST
 * @param {object} data
 * @returns {void}
 */
function nodeFileAfterDelete(data)
{
	if (data.node !== undefined && data.id !== undefined)
	{
		var id = data.id;
		var name = data.node.name;
		var prefix = data.node.prefix;
		if (jQuery('#' + prefix + '-' + name + '-' + id).length > 0)
		{
			jQuery('#' + prefix + '-' + name + '-' + id).remove();
		}
		if (jQuery('#node-' + name + '-' + id).length > 0)
		{
			jQuery('#node-' + name + '-' + id).remove();
		}
	}
}

/**
 * Function to run after File Update AJAX POST
 * @param {object} data
 * @returns {void}
 */
function nodeFileAfterUpdate(data)
{
}

/**Node Category**/
/**
 * JSTree Node Category when clicked
 * @param {Event} evt
 * @param {Object} node
 * @returns {void}
 */
function nodeCategoryJstreeOnClicked(evt, node)
{
	// console.log(evt, node);
	var tree = jQuery(evt.target);
	if (tree.length > 0)
	{
		var dataConfig = zbase_get_element_config(tree);
		var url = dataConfig.url !== undefined ? dataConfig.url : null;
		var infopane = dataConfig.infopane !== undefined ? dataConfig.infopane : null;
		var singleNode = dataConfig.node !== undefined ? dataConfig.node : null;
		url = url.replace('ACTION', 'view').replace('ID', node.node.id);
		zbase_ajax_get(url, {}, function(data){
			if(singleNode !== undefined)
			{
				jQuery(infopane).html(eval('data.html.' + singleNode));
			}
		}, {loaderTarget: infopane});
		return;
	}
}