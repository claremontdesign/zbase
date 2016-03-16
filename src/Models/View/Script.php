<?php

namespace Zbase\Models\View;

/**
 * Script
 * output: <script type="text/javascript">function javascriptFunction(){ console.log(var) }</script>
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Script.php
 * @project Zbase
 * @package Zbase\Models\View
 */
use Zbase\Interfaces;
use Zbase\Traits;

class Script implements Interfaces\IdInterface, Interfaces\HtmlInterface, Interfaces\PositionInterface, Interfaces\PlaceholderInterface, Interfaces\AttributeInterface
{

	use Traits\Attribute,
	 Traits\Html,
	 Traits\Position,
	 Traits\Placeholder,
	 Traits\Id;

	/**
	 * The HTML Prefix
	 * @see Traits\Html::getHtmlId
	 * @var string
	 */
	protected $htmlPrefix = 'script-';

	/**
	 * The script content
	 * @var string
	 */
	protected $script = null;

	/**
	 * If to defer script
	 * will be wrapped with jQuery(document).ready();
	 * @var boolean
	 */
	protected $onLoad = false;

	/**
	 * Constructor
	 * @param array $attributes
	 */
	public function __construct($attributes)
	{
		$this->setPlaceholder('body_scripts');
		$this->setAttributes($attributes);
		$onLoad = $this->getOnLoad();
		if(!empty($onLoad))
		{
			$this->setPlaceholder('body_scripts_onload');
		}
	}

	/**
	 * Render
	 *
	 * @return string
	 */
	public function __toString()
	{
		$id = $this->id();
		$script = $this->getScript();
		if(!empty($id) && !empty($script))
		{
			return EOF . $script . EOF;
		}
		return '';
	}

	/**
	 * Return the script
	 * @return string
	 */
	function getScript()
	{
		return $this->script;
	}

	/**
	 * Return if the script is an onload script
	 * @return boolean
	 */
	function getOnLoad()
	{
		return $this->onLoad;
	}

	/**
	 * Set the script
	 * @param string $script
	 */
	function setScript($script)
	{
		$this->script = $script;
	}

	/**
	 * Set if to defer script loading
	 * @param boolean $onLoad
	 */
	function setOnLoad($onLoad)
	{
		$this->onLoad = $onLoad;
	}

}
