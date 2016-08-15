<?php

namespace Zbase\Models\View;

/**
 * Nav
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Nav.php
 * @project Zbase
 * @package Zbase\Models\View
 */
use Zbase\Traits;

class Notification extends Navigation
{

	/**
	 * The HTML Prefix
	 * @see Traits\Html::getHtmlId
	 * @var string
	 */
	protected $htmlPrefix = 'notification-';

	/**
	 * Default Header message
	 * @var string
	 */
	protected $defaultMessage = null;

	/**
	 * Notification Format
	 * @var string|HTML
	 */
	protected $format = '<li class="dropdown" id="{ID}">
	<a href="{URL}" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
		<i class="{ICON}"></i>
		<span class="badge" id="{ID}-badge">{BADGE_COUNT}</span>
	</a>
	<ul class="dropdown-menu extended" id="{ID}-dropdown-menu">
		{DEFAULT_MESSAGE}
		<li>
			<ul class="dropdown-menu-list scroller"  id="{ID}-dropdown-menu-list" style="height:{DROPDOWN_HEIGHT}">{DEFAULT_CONTENT}</ul>
		</li>
		{VIEW_ALL_TEXT}
	</ul>';

	/**
	 * Height of Dropdown list
	 * @var integer
	 */
	protected $height = 0;

	/**
	 * Render
	 *
	 * @return string
	 */
	public function __toString()
	{
		$id = $this->htmlPrefix . $this->id;
		$url = $this->getRouteUrl();
		$icon = $this->icon;
		$title = $this->title;
		$defaultMessage = $this->_v('defaultMessage', null);
		$badgeCount = $this->_v('badgeCount', null);
		$defaultContent = $this->_v('defaultContent', null);
		if(!empty($defaultMessage))
		{
			$defaultMessage = '<li id="'.$id.'-default-message"><p>' . $defaultMessage . '</p></li>';
		}
		$viewAllText = $this->_v('viewAllText', null);
		if(!empty($viewAllText))
		{
			$viewAllText = '<li class="external">
				<a href="'.$url.'">
					'.$viewAllText.' <i class="m-icon-swapright"></i>
				</a>
			</li>';
		}
		$label = !empty($this->label) ? $this->label : $this->title;
		$str = str_replace(
				array('{ID}', '{URL}', '{ICON}', '{TITLE}', '{LABEL}', '{DEFAULT_MESSAGE}', '{BADGE_COUNT}', '{DEFAULT_CONTENT}', '{VIEW_ALL_TEXT}', '{DROPDOWN_HEIGHT}'),
				array($id, $url, $icon, $title, $label, $defaultMessage, $badgeCount, $defaultContent, $viewAllText, $this->height), $this->format);
		return $str;
	}

}
