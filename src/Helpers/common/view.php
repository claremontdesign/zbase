<?php

/**
 * Zbase-common Helpers-Views
 *
 * Functions and Helpers for View, themes and templates
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file view.php
 * @project Zbase
 * @package Zbase/Common/Helpers
 */
// <editor-fold defaultstate="collapsed" desc="HeadMeta">
/**
 * Add a headMeta into the HTML Head
 *
 * zbase_view_head_meta_add('viewport', 'width=1020', null, null, ['http-equiv' => 'Content-Language']);
 * zbase()->view()->render('headMeta') = <meta name="viewport" content="width=1020" http-equiv="Content-Language">
 *
 * @param string $name
 * @param string $content
 * @param array $cond
 * @param string $id
 * @param array $attributes
 * @return Zbase\Models\View\HeadMeta
 */
function zbase_view_head_meta_add($name, $content, $cond = null, $id = null, $attributes = [])
{
	return zbase()->view()->add(\Zbase\Models\View::HEADMETA, [
				'id' => !empty($id) ? $id : $name,
				'content' => $content,
				'name' => $name,
				'html' => [
					'condition' => $cond,
					'attributes' => $attributes
				]
	]);
}

/**
 * Set multiple headMetas
 *
 * @param array $metas
 * @return Zbase\Models\View\HeadMeta[]
 */
function zbase_view_head_metas_set($metas)
{
	if(is_array($metas) && !empty($metas))
	{
		foreach ($metas as $id => $config)
		{
			$config['id'] = $id;
			zbase()->view()->add(\Zbase\Models\View::HEADMETA, $config);
		}
	}
	return zbase_view_head_metas();
}

/**
 * Return the HeadMeta by $name
 *
 * @param string $name
 * @return Zbase\Models\View\HeadMeta
 */
function zbase_view_head_meta($name)
{
	return zbase()->view()->get(\Zbase\Models\View::HEADMETA, $name);
}

/**
 * Check if a headMeta by $name exists
 *
 * @param string $name
 * @return boolean
 */
function zbase_view_head_meta_has($name)
{
	return zbase()->view()->has(\Zbase\Models\View::HEADMETA, $name);
}

/**
 * Return all headMetas
 *
 * @return Zbase\Models\View\HeadMeta[]
 */
function zbase_view_head_metas()
{
	return zbase()->view()->all(\Zbase\Models\View::HEADMETA);
}

/**
 * Render headMetas
 *
 * @return string
 */
function zbase_view_head_metas_render()
{
	return zbase()->view()->render(\Zbase\Models\View::HEADMETA);
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="HeadLink">
/**
 * Add a headlink into the HTML Head
 *
 * zbase_view_head_link_add('id','theme.css', 'stylesheet', []);
 * <link rel="stylesheet" type="text/css" href="theme.css">
 *
 * @param string $id HeadLink ID - some unique id
 * @param string $href Href attribute of Link
 * @param array $rel The rel attribute
 * @param array $cond
 * @param array $attributes
 * @return Zbase\Models\View\HeadMeta
 */
function zbase_view_head_link_add($id, $href, $rel, $cond = null, $attributes = [])
{
	return zbase()->view()->add(\Zbase\Models\View::HEADLINK, [
				'id' => $id,
				'href' => $href,
				'rel' => $rel,
				'html' => [
					'condition' => $cond,
					'attributes' => $attributes
				]
	]);
}

/**
 * Set multiple HeadLink
 *
 * @param array $links
 * @return Zbase\Models\View\HeadLink[]
 */
function zbase_view_head_links_set($links)
{
	if(is_array($links) && !empty($links))
	{
		foreach ($links as $id => $config)
		{
			$config['id'] = $id;
			zbase()->view()->add(\Zbase\Models\View::HEADLINK, $config);
		}
	}
	return zbase_view_head_links();
}

/**
 * Return the HeadLink by $id
 *
 * @param string $id
 * @return Zbase\Models\View\HeadLink
 */
function zbase_view_head_link($id)
{
	return zbase()->view()->get(\Zbase\Models\View::HEADLINK, $id);
}

/**
 * Check if a HeadLink by $id exists
 *
 * @param string $id
 * @return boolean
 */
function zbase_view_head_link_has($id)
{
	return zbase()->view()->has(\Zbase\Models\View::HEADLINK, $id);
}

/**
 * Return all HeadLink
 *
 * @return Zbase\Models\View\HeadLink[]
 */
function zbase_view_head_links()
{
	return zbase()->view()->all(\Zbase\Models\View::HEADLINK);
}

/**
 * Render HeadLink
 *
 * @return string
 */
function zbase_view_head_links_render()
{
	return zbase()->view()->render(\Zbase\Models\View::HEADLINK);
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Stylesheet">
/**
 * Add a Stylesheet
 *
 * zbase_view_stylesheet_add('id','theme.css', 'stylesheet', []);
 * <link rel="stylesheet" type="text/css" href="theme.css">
 *
 * @param string $id
 * @param string $href
 * @param string $cond The HTML Condition: ['lte IE 8']
 * @param array $attributes
 * @return Zbase\Models\View\Stylesheet
 */
function zbase_view_stylesheet_add($id, $href, $cond = null, $attributes = [])
{
	return zbase()->view()->add(\Zbase\Models\View::STYLESHEET, [
				'id' => $id,
				'href' => $href,
				'html' => [
					'conditions' => $cond,
					'attributes' => $attributes
				]
	]);
}

/**
 * Set multiple Stylesheet
 *
 * @param array $stylesheets
 * @return Zbase\Models\View\Stylesheet[]
 */
function zbase_view_stylesheets_set($stylesheets)
{
	if(is_array($stylesheets) && !empty($stylesheets))
	{
		foreach ($stylesheets as $id => $config)
		{
			$config['id'] = $id;
			zbase()->view()->add(\Zbase\Models\View::STYLESHEET, $config);
		}
	}
	return zbase_view_stylesheets();
}

/**
 * Return the Stylesheet by $id
 *
 * @param string $id
 * @return Zbase\Models\View\Stylesheet
 */
function zbase_view_stylesheet($id)
{
	return zbase()->view()->get(\Zbase\Models\View::STYLESHEET, $id);
}

/**
 * Check if a Stylesheet by $id exists
 *
 * @param string $id
 * @return boolean
 */
function zbase_view_stylesheet_has($id)
{
	return zbase()->view()->has(\Zbase\Models\View::STYLESHEET, $id);
}

/**
 * Return all Stylesheet
 *
 * @return Zbase\Models\View\Stylesheet[]
 */
function zbase_view_stylesheets()
{
	return zbase()->view()->all(\Zbase\Models\View::STYLESHEET);
}

/**
 * Render Stylesheet
 *
 * @return string
 */
function zbase_view_stylesheets_render()
{
	return zbase()->view()->render(\Zbase\Models\View::STYLESHEET);
}

/**
 * Load a stylesheet plugin from config by id
 *
 * @param string $pluginId
 * @return Zbase\Models\View\Stylesheet
 */
function zbase_view_stylesheet_load_plugin($pluginId)
{

}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Style">
/**
 * Add a Style
 *
 * zbase_view_style_add('id','function javascriptFunction(){ console.log(var) }', null, []);
 * <style type="text/css">#selector{display:block;}</style>
 *
 * @param string $id
 * @param string $style
 * @param array $cond
 * @param array $attributes
 * @return Zbase\Models\View\Style
 */
function zbase_view_style_add($id, $style, $cond = null, $attributes = [])
{
	return zbase()->view()->add(\Zbase\Models\View::STYLE, [
				'id' => $id,
				'style' => $style,
				'html' => [
					'condition' => $cond,
					'attributes' => $attributes
				]
	]);
}

/**
 * Set multiple Style
 *
 * @param array $styles
 * @return Zbase\Models\View\Style[]
 */
function zbase_view_styles_set($styles)
{
	if(is_array($styles) && !empty($styles))
	{
		foreach ($styles as $id => $config)
		{
			$config['id'] = $id;
			zbase()->view()->add(\Zbase\Models\View::STYLE, $config);
		}
	}
	return zbase_view_styles();
}

/**
 * Return the Style by $id
 *
 * @param string $id
 * @return Zbase\Models\View\Style
 */
function zbase_view_style($id)
{
	return zbase()->view()->get(\Zbase\Models\View::STYLE, $id);
}

/**
 * Check if a Style by $id exists
 *
 * @param string $id
 * @return boolean
 */
function zbase_view_style_has($id)
{
	return zbase()->view()->has(\Zbase\Models\View::STYLE, $id);
}

/**
 * Return all Style
 *
 * @return Zbase\Models\View\Style[]
 */
function zbase_view_styles()
{
	return zbase()->view()->all(\Zbase\Models\View::STYLE);
}

/**
 * Render Style
 *
 * @return string
 */
function zbase_view_styles_render()
{
	return zbase()->view()->render(\Zbase\Models\View::STYLE);
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Javascript">
/**
 * Add a Javascript
 *
 * zbase_view_javascript_add('id','script.js', null, []);
 * <script type='text/javascript' src='script.js'></script>
 *
 * @param string $id
 * @param string $href
 * @param array $cond
 * @param array $attributes
 * @return Zbase\Models\View\Javascript
 */
function zbase_view_javascript_add($id, $href, $cond = null, $attributes = [])
{
	return zbase()->view()->add(\Zbase\Models\View::JAVASCRIPT, [
				'id' => $id,
				'href' => $href,
				'html' => [
					'condition' => $cond,
					'attributes' => $attributes
				]
	]);
}

/**
 * Set multiple Javascript
 *
 * @param array $javascripts
 * @return Zbase\Models\View\Javascript[]
 */
function zbase_view_javascripts_set($javascripts)
{
	if(is_array($javascripts) && !empty($javascripts))
	{
		foreach ($javascripts as $id => $config)
		{
			$config['id'] = $id;
			zbase()->view()->add(\Zbase\Models\View::JAVASCRIPT, $config);
		}
	}
	return zbase_view_javascripts();
}

/**
 * Return the Javascript by $id
 *
 * @param string $id
 * @return Zbase\Models\View\Javascript
 */
function zbase_view_javascript($id)
{
	return zbase()->view()->get(\Zbase\Models\View::JAVASCRIPT, $id);
}

/**
 * Check if a Javascript by $id exists
 *
 * @param string $id
 * @return boolean
 */
function zbase_view_javascript_has($id)
{
	return zbase()->view()->has(\Zbase\Models\View::JAVASCRIPT, $id);
}

/**
 * Return all Javascript
 *
 * @return Zbase\Models\View\Javascript[]
 */
function zbase_view_javascripts()
{
	return zbase()->view()->all(\Zbase\Models\View::JAVASCRIPT);
}

/**
 * Render Javascript
 *
 * @return string
 */
function zbase_view_javascripts_render()
{
	return zbase()->view()->render(\Zbase\Models\View::JAVASCRIPT);
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Script">
/**
 * Add a Script
 *
 * zbase_view_script_add('id','function javascriptFunction(){ console.log(var) }', false, null, []);
 * <script type="text/javascript">function javascriptFunction(){ console.log(var) }</script>
 *
 * @param string $id
 * @param string $script
 * @param boolean $onLoad If to defer loading
 * @param array $cond
 * @param array $attributes
 * @return Zbase\Models\View\Script
 */
function zbase_view_script_add($id, $script, $onLoad = true, $cond = null, $attributes = [])
{
	return zbase()->view()->add(\Zbase\Models\View::SCRIPT, [
				'id' => $id,
				'script' => $script,
				'onLoad' => $onLoad,
				'html' => [
					'condition' => $cond,
					'attributes' => $attributes
				]
	]);
}

/**
 * Set multiple Script
 *
 * @param array $scripts
 * @return Zbase\Models\View\Script[]
 */
function zbase_view_scripts_set($scripts)
{
	if(is_array($scripts) && !empty($scripts))
	{
		foreach ($scripts as $id => $config)
		{
			$config['id'] = $id;
			zbase()->view()->add(\Zbase\Models\View::SCRIPT, $config);
		}
	}
	return zbase_view_scripts();
}

/**
 * Return the Script by $id
 *
 * @param string $id
 * @return Zbase\Models\View\Script
 */
function zbase_view_script($id)
{
	return zbase()->view()->get(\Zbase\Models\View::SCRIPT, $id);
}

/**
 * Check if a Script by $id exists
 *
 * @param string $id
 * @return boolean
 */
function zbase_view_script_has($id)
{
	return zbase()->view()->has(\Zbase\Models\View::SCRIPT, $id);
}

/**
 * Return all Script
 *
 * @return Zbase\Models\View\Script[]
 */
function zbase_view_scripts()
{
	return zbase()->view()->all(\Zbase\Models\View::SCRIPT);
}

/**
 * Render Script
 *
 * @return string
 */
function zbase_view_scripts_render()
{
	return zbase()->view()->render(\Zbase\Models\View::SCRIPT);
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Placeholders">

/**
 * Add item to placeholder
 *
 * @param string $placeholder Placeholder name
 * @param string $id Item ID
 * @return void
 */
function zbase_view_placeholder_add($placeholder, $id, $html)
{
	zbase()->view()->addToPlaceholder($placeholder, $id, $html);
}

/**
 * Return an item from a placeholder by $id
 * 	if $id is empty, will return all items from the given placeholder
 *
 * @param string $placeholder Placeholder name
 * @param string $id Item ID optional
 * @return \Zbase\Interfaces\HtmlInterface|null|\Zbase\Interfaces\HtmlInterface[]
 */
function zbase_view_placeholder($placeholder, $id = null)
{
	return zbase()->view()->getFromPlaceholder($placeholder, $id);
}

/**
 * Check if a Javascript by $id exists
 *
 * @param string $placeholder Placeholder name
 * @param string $id Item ID
 * @return boolean
 */
function zbase_view_placeholder_has($placeholder, $id)
{
	return zbase()->view()->inPlaceholder($placeholder, $id);
}

/**
 * Render a placeholder
 *
 * @param string $placeholder
 * @return string
 */
function zbase_view_placeholder_render($placeholder)
{
	return zbase()->view()->renderPlaceholder($placeholder);
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Plugins">
/**
 * Load a view plugin based on HTML plugin configuration
 * 	See property of each view-plugin type
 * 		view.plugins.$id.type = \Zbase\Models\View::HEADMETA
 * 		view.plugins.$id.enable = false|true
 * 		view.plugins.$id.name = viewport
 * 		view.plugins.$id.content = width=1020
 * 		view.plugins.$id.html.conditions = null|array|string
 * 		view.plugins.$id.html.attributes = null
 * 		view.autoload.plugins = [$id, $id2, $id3...]
 *
 *
 * @param string|array $id
 * @return Zbase\Interfaces\HtmlInterface
 */
function zbase_view_plugin_load($id)
{
	if(is_array($id))
	{
		if(!empty($id['type']) && !empty($id['enable']))
		{
			zbase()->view()->add($id['type'], $id);
			if(!empty($id['dependents']))
			{
				foreach ($id['dependents'] as $d)
				{
					if(is_array($d))
					{
						if($d['type'] !== \Zbase\Models\View::HEADMETA)
						{
							$d['id'] = $id . '-' . $d['id'];
						}
						zbase_view_plugin_load($d);
					}
					else
					{
						zbase_view_plugin_load($d);
					}
				}
			}
		}
		return;
	}
	$plugin = zbase_config_get('view.plugins.' . $id, null);
	if(!is_null($plugin))
	{
		if(!empty($plugin['enable']))
		{
			if(empty($plugin['id']))
			{
				$plugin['id'] = $id;
			}
			$html = zbase()->view()->add($plugin['type'], $plugin);
			if(!empty($plugin['dependents']))
			{
				foreach ($plugin['dependents'] as $d)
				{
					if(is_array($d))
					{
						if($d['type'] !== \Zbase\Models\View::HEADMETA)
						{
							$d['id'] = $id . '-' . $d['id'];
						}
						zbase_view_plugin_load($d);
					}
					else
					{
						zbase_view_plugin_load($d);
					}
				}
			}
			return $html;
		}
	}
	return null;
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Navigation">
/**
 * Add a Navigation
 *
 * @param string $id
 * @param string $attributes
 * @param string $group
 * @return Zbase\Models\View\Nav
 */
function zbase_view_nav_add($id, $attributes, $group)
{
	if(empty($attributes['id']))
	{
		$attributes['id'] = $id;
	}
	return zbase()->view()->add(\Zbase\Models\View::NAVIGATION, $attributes, $group);
}

/**
 * Set multiple Navigation
 *
 * @param array $navs
 * @param string $group
 * @return Zbase\Models\View\Nav[]
 */
function zbase_view_navs_set($navs, $group)
{
	if(is_array($navs) && !empty($navs))
	{
		foreach ($navs as $id => $config)
		{
			if(empty($config['id']))
			{
				$config['id'] = $id;
			}
			zbase()->view()->add(\Zbase\Models\View::NAVIGATION, $config, $group);
		}
	}
	return zbase_view_navs($group);
}

/**
 * Return the Navigation by $id
 *
 * @param string $id
 * @param string $group
 * @return Zbase\Models\View\Nav
 */
function zbase_view_nav($id, $group)
{
	return zbase()->view()->get(\Zbase\Models\View::NAVIGATION, $id, $group);
}

/**
 * Check if a Navigation by $id exists
 *
 * @param string $id
 * @param string $group
 * @return boolean
 */
function zbase_view_nav_has($id, $group)
{
	return zbase()->view()->has(\Zbase\Models\View::NAVIGATION, $id, $group);
}

/**
 * Return all Navigation
 *
 * @param string $group
 * @return Zbase\Models\View\Nav[]
 */
function zbase_view_navs($group)
{
	return zbase()->view()->all(\Zbase\Models\View::NAVIGATION, $group);
}

/**
 * Render Nav
 *
 * @param string $group
 * @return string
 */
function zbase_view_navs_render($group)
{
	$nav = zbase()->view()->all(\Zbase\Models\View::NAVIGATION, $group);
	if(!empty($nav))
	{
		$template = zbase_config_get('view.templates.nav.' . $group, zbase_config_get('view.templates.nav', 'partial.nav'));
		return zbase_view_render(zbase_view_file($template), compact('nav', 'group', 'template'));
	}
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Breadcrumb">
/**
 * Set Breadcrumb
 * @param array $breadcrumb
 */
function zbase_view_breadcrumb($breadcrumb)
{
	zbase()->view()->setBreadcrumb($breadcrumb);
}

/**
 * Render Breadcrumb
 * @return string|html
 */
function zbase_view_breadcrumb_render()
{
	$breadcrumbs = zbase()->view()->getBreadcrumb();
	if(!empty($breadcrumbs))
	{
		$template = zbase_config_get('view.templates.breadcrumb', 'partial.breadcrumb');
		return zbase_view_render(zbase_view_file($template), compact('breadcrumbs', 'template'));
	}
}

// </editor-fold>

/**
 * Extract Page Details from the given $config/array
 * Will check for index: navIndex => nav.front.main.navIndex.meta
 * Will check for index: pageIndex => nav.front.main.navIndex.meta
 * @param array $config
 */
function zbase_view_page_details($config)
{
	if(!empty($config['navIndex']))
	{
		$navIndex = $config['navIndex'];
		$meta = zbase_config_get('nav.front.main.' . $navIndex . '.meta', zbase_config_get('nav.main.' . $navIndex . '.meta', false));
	}
	if(!empty($config['pageIndex']))
	{
		$pageIndex = $config['pageIndex'];
		$meta = zbase_config_get('page.front.' . $pageIndex . '.meta', zbase_config_get('page.' . $pageIndex . '.meta', false));
	}
	if(!empty($config['page']))
	{
		$title = null;
		$headTitle = null;
		$subTitle = null;
		if(!empty($config['page']['title']))
		{
			$title = $config['page']['title'];
		}
		if(!empty($config['page']['headTitle']))
		{
			$headTitle = $config['page']['headTitle'];
		}
		if(!empty($config['page']['subTitle']))
		{
			$subTitle = $config['page']['subTitle'];
		}
		zbase_view_pagetitle_set($headTitle, $title, $subTitle);
		if(!empty($config['page']['breadcrumbs']))
		{
			zbase_view_breadcrumb($config['page']['breadcrumbs']);
		}
	}
	if(!empty($meta))
	{
		zbase_view_extract_meta($meta);
	}
}

/**
 * Extract page metas from given array
 * @param array $meta
 */
function zbase_view_extract_meta($meta)
{
	if(!empty($meta))
	{
		if(!empty($meta['pageTitle']))
		{
			zbase_view_pageTitle($meta['pageTitle']);
		}
		if(!empty($meta['meta']) && is_array($meta['meta']))
		{
			foreach ($meta['meta'] as $name => $content)
			{
				zbase_view_head_meta_add($name, $content);
			}
		}
	}
}

/**
 * The Facebook Head Meta tags
 * 	https://davidwalsh.name/facebook-meta-tags
 * 	Minimum Facebook Head Metas
 * 		title: The title to accompany the URL; In most cases, this should be the article or page title.
 * 		type: Provides Facebook the type of website that you would like your website to be categorized by; blog|article
 * 		locale
 * 		site_name: Provides Facebook the name that you would like your website to be recognized by:
 * 		url: The URL should be the canonical address for the given page:
 * 		description
 * 		image
 * 		app_id = Facebook APP Id
 *
 * @param array $metas AssocArray of Facebook Metas
 * @return void
 */
function zbase_view_facebook_meta($metas)
{
	foreach ($metas as $key => $val)
	{
		if($key == 'app_id')
		{
			zbase_view_head_meta_add('fb:app_id' . $key, $val);
			continue;
		}
		zbase_view_head_meta_add('og:' . $key, $val);
	}
}

/**
 * Set the Page Title
 * @param string $pageTitle
 */
function zbase_view_pageTitle($pageTitle)
{
	zbase()->view()->setPageTitle($pageTitle);
}

/**
 * SEt the Canonical Url
 * @param string $canonicalUrl The Canonical URL
 * @param string $shortUrl The Short version of the URL
 * @return void
 */
function zbase_view_canonicalUrl($canonicalUrl, $shortUrl = null)
{
	zbase_view_head_link_add('canonical', $canonicalUrl, 'canonical');
	if(!empty($shortUrl))
	{
		zbase_view_head_link_add('shortlink', $shortUrl, 'shortlink');
	}
}

/**
 * Render HTML between <head></head>
 *
 * @return string
 */
function zbase_view_render_head()
{
	$str = '';
	zbase()->view()->prepare();
	$str .= '<title>' . zbase()->view()->pageTitle() . '</title>';
	$str .= zbase_view_head_metas_render();
	$str .= zbase_view_stylesheets_render();
	$str .= zbase_view_head_links_render();
	$str .= zbase_view_placeholder_render('head_javascripts');
	$str .= zbase_view_placeholder_render('head_scripts');
	$str .= zbase_view_styles_render();
	return $str;
}

/**
 * Render HTML before </body>
 *
 * @return string
 */
function zbase_view_render_body()
{
	$str = '';
	zbase()->view()->prepare();
	$str .= zbase_view_placeholder_render('body_javascripts');
	$str .= EOF . '<script type="text/javascript">';
	$str .= EOF . zbase_view_placeholder_render('body_scripts');
	$str .= EOF . 'jQuery(document).ready(function(){'
			. EOF . zbase_view_placeholder_render('body_scripts_onload')
			. EOF . '});';
	$str .= EOF . '</script>';
	return $str;
}

/**
 * Display an error page
 * @param string|int $code
 * @param string $msg
 * @return string
 */
function zbase_view_error($code, $msg = null)
{
	$common = [403, 404, 500, 503];
	return \View::make(zbase_view_file('errors.' . (in_array($code, $common) ? $code : 500)), compact('msg', 'code'));
}

/**
 * Check if we have to use CDN
 * @return boolean
 */
function zbase_view_cdn()
{
	return env('ZBASE_CDN', zbase_config_get('view.cdn.enable', false));
}

/**
 * The text to appear in the Footer
 * @return string
 */
function zbase_text_footer()
{
	return zbase_config_get('site.footer.text', 'Zbase Admin by ClaremontDesign.com');
}

/**
 * Compile HTML Strings
 * @param string $html
 */
function zbase_view_compile($html)
{
	return zbase_remove_whitespaces($html);
}
