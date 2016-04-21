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