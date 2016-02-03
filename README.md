# zbase
# CheatSheet: http://ilyes512.github.io/LaravelCS/
ZBase Laravel Framework

Notes:
- Framework dependent
- Auto Add/Modify code to Basic Laravel App @see Reflection

Zbase:
	// Collection of focused entities loaded
	protected Models\Entities Collection $entities;
	protected Models\Request $request
	protected Models\View $view
	protected Models\Config $config

Main Models:
	View
		$pageTitle
		$headMetas
		$headLinks
		$stylesheets
		$javascripts
		$scripts
		$styles
		$contents
		$navigations
		$variables
			View/Headmeta implements Idable, Enableble, Positionable, Configurable
			View/Headlink
			View/Stylesheet
			View/Javascript
			View/Script
			View/Style
			View/Content
			View/Navigation
			View/DisplayType

	Request
		$form
		$query
		$route
		$method
		$isAjax
		$server

	Response

	Config

	Properties
		$config = [];
		- id()
		- title()
		- isEnable()
		- hasAccess()
		- position()
		- config()
		- label()
		- __set()
		- __get()
		- __call()

	Repository

Entities
	Interface
		PositionInterface
			position
		PostInterface
			id,title,description,enable
		AlphaIdInterface
			alphaId
		SeoInterface
			slug,meta_title,meta_description,meta_keywords
		CommentInterface


	Traits
		Position
		Post
		Attributes
		Seo
		AlphaId

	Repository
		Node
			byId
			byTitle
			byStatus
			all
			byCategory

		NodeCategory

	Entity
	NestedEntity

	Node implements PostInterface, SeoInterface,AlphaIdInterface use Post, Seo, AlphaId
	NodeCategory extends NestedEntity implements PostInterface, SeoInterface
	NodeCategoryPivot implements PositionInterface use Position
	NodeAttributes
	NodeStats
	NodeComments
	NodeCommentsReply
	NodeCommentsReplyPivot
	NodeCommentsStats extends NodeStats
	NodeRevisions
	NodeTag
	NodeTagPivot

	File extends Node
		uploadFile($input, $config = null)
		deleteFile()
		sanitize()

Widgets
	Interface
		WidgetEntityInterface
		JsonInterface
			json()
		ViewInterface
			render()
			viewFile()
		ControllerInterface
			controller()
			validate()

Interface:
	AttributeInterface
		attribute($key)
		setAttribute($key, $value)
		__get()
		__set()

	EntityInterface
		id()
		title()
		description()

	StatusInterface
		enable()
		disable()

	AccessInterface
		hasAcces()
		setAccess($access)

	PositionInterface
		position()
		setPosition(integer $position)

	Configurable
		config()
		setConfig($key, $value)

	AlphaInterface
		alpha()
		reverseAlpha(string $alpha)

Traits
	Attribute
		setAttribute
		getAttribute
		__get
		__set
	Id
		id
		name
	Config
	Alpha
	Request
		isPost()
		isAjax()
		formInput();
		routeInput()
		queryInput()

Helpers/function
Config
- zbase_config($key, $default = null, $config = []);
- zbase_config_set($key, $value);

Requests
- zbase_request_input($key, $default = null);
- zbase_request_inputs();
- zbase_request_form_input($key, $default = null);
- zbase_request_form_set($key, $value);
- zbase_request_form_inputs();
- zbase_request_route_input($key)
- zbase_request_route_set($key, $value)
- zbase_request_query_input($key, $default);
- zbase_request_query_inputs();
- zbase_request_query_set($key, $value);
- zbase_request_method();
- zbase_request_is_ajax();

Routes
- zbase_route()

View Helpers
- zbase_view_breadcrumb_set($breads);
- zbase_view_breadcrumb();

Set the Page Title. This is to be displayed on the page
array|string $pageTitle, if array, first index is the pageTitle. second index is the subtitle
- zbase_view_pagetitle_set($pageTitle);
- zbase_view_pagetitle();
- zbase_view_pagetitle_render();

Set the page metas like description,keyword,title
array $metas Assoc array [name=>null,content=>null,condition=>null]
- zbase_view_head_metas_set($metas);
- zbase_view_head_meta_set($name, $content, $cond = null, $id = null);
- zbase_view_head_meta($name);
- zbase_view_head_metas();
- zbase_view_head_metas_render();

- zbase_view_head_links_set($links);
- zbase_view_head_link_set($rel, $href, $cond = null, $type=null, $media=null, $id = null);
- zbase_view_head_link($rel);
- zbase_view_head_links();
- zbase_view_head_links_render();

- zbase_view_stylesheet_add($id, $href, $position = 0, $cond = null, $media=null);
- zbase_view_stylesheets_add($stylesheets);
- zbase_view_stylesheet($id);
- zbase_view_stylesheets();
- zbase_view_stylesheets_render();

- zbase_view_javascript_add($id, $href, $position = 0, $cond = null);
- zbase_view_javascripts_add($javascripts);
- zbase_view_javascript($id);
- zbase_view_javascripts();
- zbase_view_javascripts_render();
- zbase_view_plugin_load($id);

- zbase_view_script_add($id, $script, $onload = false, $position = 0,);
- zbase_view_scripts_add($scripts);
- zbase_view_script($id);
- zbase_view_scripts();
- zbase_view_scripts_render();

- zbase_view_style_add($id, $style, $position = 0);
- zbase_view_styles_add($styles);
- zbase_view_style($id);
- zbase_view_styles();

Template has different placeholder across the page that a content can be added
- zbase_view_content_add($id, $content, $placeholder, $config = [])
- zbase_view_contents_add($blocks)
- zbase_view_content($id)
- zbase_view_contents()
- zbase_view_content_render($id)

Create Form element, buttons, tabs
- zbase_view_ui_create($ui, $config)

Merge nav if id is found
- zbase_view_nav_add($id, $nav, $config = [])
- zbase_view_navs_add($navs)
- zbase_view_navs($id)
- zbase_view_navs()
- zbase_view_nav_render($id)

- zbase_view_entity_set(Entity $entity);
- zbase_view_entity();
- zbase_view_entity_render();

- zbase_view_set($key, $value)
- zbase_view_get($key, $default = null)

- zbase_view_template()
- zbase_view_name($viewName)

- zbase_view_backend_template()
- zbase_view_backend_name($viewName)

- zbase_asset($assetName = null)

Main
- zbase()
- zbase_tag()
- zbase_is_dev()
- zbase_is_testing()
- zbase_section_set($section)
- zbase_section()
- zbase_is_admin()

Module
- zbase_module($id)
- zbase_modules()

Widgets
- zbase_widget($id)
- zbase_widgets()

Events
- zbase_event_hook()
- zbase_event_register()
- zbase_event_fire()

URL
- zbase_url_create_from_current(array $queryString);
Response
- zbase_abort($code, $reason)
- zbase_response()

Values
- zbase_value($object, $index, $default, $options = null)
- zbase_value_callback()

Auth
- zbase_auth()
- zbase_auth_user()
- zbase_auth_check()
- zbase_auth_is()
- zbase_auth_minimum_access()

Dates
- zbase_date_display()

Images
- zbase_image($path)
- zbase_image_resize($path, $width = null, $height = null, $quality = 80, $save = false, $suffix = null, $returnFilename = false)

Database
- zbase_db_tablename($table)


AutoCreate Entity
@return Models\Entity
- zbase_entity_create($config)
- zbase_entity($name)

Others
- zbase_wildcard_url()

Widgets:
	all configs default to module configuration
	Datatable Extend Widget use Properties
		module: moduleName
		enable:
		access: null|string
		placeholder: null|Name of Placeholder
		request
			data: instanceof Widget\Data\Data
				param: the rquest param that data will be fetch or checked. will throw 404 if not found

			parent
				array:
					data:
						param
						entity
					data:
						param
						entity

		view
			template: the template to use

		ui: instanceof View\Ui
			dom:
			script: scripts to load
			style: styles to load

		config
			export : export data to csv|pdf
			order : boolean|array table rows can be ordered
			display :
				type: string|array grid|list|timeline
					grid: []
					list: []
					timeline: []
			sort : can be sorted by columns
				enable:
				entity:
					index
			filter : can be filtered by columns
				enable
				entity:
					index

			columns : instanceof Datatable\Column
				type: instanceof View\DisplayType
					string|array type of display
					array: type, type of type, config
						integer|tinyinteger|smallinteger|mediuminteger|biginteger|
						float|double|decimal
						char|string|text|mediumtext|longtext
						date|datetime|time|timestamp
						boolean|enabledisable|yesno
					type[boolean, enabledisable, []]
				ui: instanceof View\Ui
					dom:
						selector:
							attributes
							script:
							style:
					script:
					style:

				attributes: instanceof Attributes
					label:
					title:
						entity:
							index:

				view: instanceof Widget\View
					template: the template to use
					mask: {{title}} is the best {{id}}

				sort : can be sorted instanceof Widget\Data\Sort
					enable:
					entity:
						property:

				filter : can be filtered instanceof Widget\Data\Filter
					type: range
					enable
					entity: instanceof Widget\Entity
						property:
					input:
						attributes:
							placeholder:
					ui:
						dom:
							selector:
						script:
						style:

				enable:
				entity: EntityModel should be instanceof WidgetEntityInterface
					property:
				access: string|array
				position: integer - ascending position
				hidden: Hidden by default. enable should be true. can be visible via user config and save to cookie




Widget Model
	use \Properties, \Request
	__construct(Module $module, Properties $properties)
