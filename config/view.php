<?php

/**
 * View configuration
 *
 * @link //zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file view.php
 * @project Zbase
 * @package config
 *
 * view.templates.front.package = packagename
 * view.templates.front.theme = packagename|themename
 */
return [
	'view' => [
		// <editor-fold defaultstate="collapsed" desc="Templates">
		// </editor-fold>
		// <editor-fold defaultstate="collapsed" desc="Plugins">
		'plugins' => [
			'meta-author' => [
				'type' => \Zbase\Models\View::HEADMETA,
				'enable' => true,
				'name' => 'author',
				'content' => 'Dennes B Abing'
			],
			'_token' => [
				'type' => \Zbase\Models\View::HEADMETA,
				'enable' => true,
				'name' => '_token',
				'content' => function(){
					return zbase_csrf_token();
				},
			],
			'zbase' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('js/js.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'zbase-app-init',
						'type' => \Zbase\Models\View::SCRIPT,
						'enable' => true,
						'script' => 'Zbase.init();',
						'onLoad' => true,
					],
					[
						'id' => 'zbase-style',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('css/style.css'),
						'position' => 1000,
						'enable' => true,
					],
				]
			],
			'nodes' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('js/nodes/nodes.js'),
				'enable' => true
			],
			// <editor-fold defaultstate="collapsed" desc="Jquery">
			'jquery' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'cdn' => '//code.jquery.com/jquery-1.11.0.min.js',
				'src' => zbase_path_asset('jquery/jquery-1.11.0.min.js'),
				'enable' => true,
				'position' => 999,
				'dependents' => [
					[
						'id' => 'migrate',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'cdn' => '//code.jquery.com/jquery-migrate-1.2.1.min.js',
						'src' => zbase_path_asset('jquery/jquery-migrate-1.2.1.min.js'),
						'enable' => true,
						'position' => 998,
					],
					[
						'id' => 'jqueryMobile',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'cdn' => '//code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js',
						'enable' => false,
						'position' => 997,
					],
					[
						'id' => 'ui',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'cdn' => '//code.jquery.com/ui/1.11.4/jquery-ui.min.js',
						'src' => zbase_path_asset('jquery/jquery-ui-1.11.4.min.js'),
						'enable' => true,
						'position' => 997,
					]
				]
			],
			'toastr' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('metronic/plugins/bootstrap-toastr/toastr.min.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'toastr-css',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/bootstrap-toastr/toastr.min.css'),
						'enable' => true,
						'position' => 94343,
					],
				]
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="Bootstrap">
			'bootstrap' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'cdn' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',
				'position' => 996,
				'src' => zbase_path_asset('bootstrap/js/bootstrap.min.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'meta-charset',
						'type' => \Zbase\Models\View::HEADMETA,
						'enable' => true,
						'position' => 999,
						'html' => [
							'attributes' => [
								'charset' => 'utf-8'
							]
						],
					],
					[
						'id' => 'meta-viewport',
						'type' => \Zbase\Models\View::HEADMETA,
						'name' => 'viewport',
						'position' => 998,
						'content' => 'width=device-width, initial-scale=1',
						'enable' => true
					],
					[
						'id' => 'meta-compatibility',
						'type' => \Zbase\Models\View::HEADMETA,
						'enable' => true,
						'position' => 997,
						'content' => 'IE=edge',
						'html' => [
							'attributes' => [
								'http-equiv' => 'X-UA-Compatible'
							]
						]
					],
					[
						'id' => 'base',
						'type' => \Zbase\Models\View::STYLESHEET,
						'cdn' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
						'href' => zbase_path_asset('bootstrap/css/bootstrap.min.css'),
						'position' => 999,
						'enable' => true,
					],
					[
						'id' => 'theme',
						'type' => \Zbase\Models\View::STYLESHEET,
						'cdn' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css',
						'position' => 998,
						'href' => zbase_path_asset('bootstrap/css/bootstrap-theme.min.css'),
						'enable' => true,
					],
					[
						'id' => 'html5shiv',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'cdn' => '//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js',
						'src' => zbase_path_asset('bootstrap/js/html5shiv-3.7.2.min.js'),
						'enable' => true,
						'placeholder' => 'head_javascripts',
						'html' => [
							'conditions' => 'if lt IE 9'
						],
					],
					[
						'id' => 'respond',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'cdn' => '//oss.maxcdn.com/respond/1.4.2/respond.min.js',
						'src' => zbase_path_asset('bootstrap/js/respond-1.4.2.min.js'),
						'enable' => true,
						'placeholder' => 'head_javascripts',
						'html' => [
							'conditions' => 'if lt IE 9'
						],
					]
				],
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="Widget:Tree">
			'bootstrap-treeview' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('bootstrap/plugins/treeview/js/bootstrap-treeview.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'bootstrap-treeview',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('bootstrap/plugins/treeview/css/bootstrap-treeview.css'),
						'enable' => true,
					],
				]
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="Widget:JStree">
			'jstree' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('metronic/plugins/jstree/dist/jstree.min.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'jstree-css',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/jstree/dist/themes/default/style.min.css'),
						'enable' => true,
					],
				]
			],
			// //ashleydw.github.io/lightbox/
			'bslightbox' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => '//cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/4.0.1/ekko-lightbox.min.js',
				'enable' => true,
				'dependents' => [
					[
						'id' => 'bslightbox-css',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => '//cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/4.0.1/ekko-lightbox.min.css',
						'enable' => true,
					],
				]
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="Bootstrap Select">
			'bootstrap-select' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('metronic/plugins/bootstrap-select/bootstrap-select.min.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'bootstrap-select-init',
						'type' => \Zbase\Models\View::SCRIPT,
						'enable' => false,
						'script' => '',
						'onLoad' => true,
					],
					[
						'id' => 'bootstrap-select2',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/select2/select2.min.js'),
						'enable' => true,
					],
					[
						'id' => 'bootstrap-select2-jquery-multi',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-multi-select/js/jquery.multi-select.js'),
						'enable' => true,
					],
					[
						'id' => 'bootstrap-select-style',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/bootstrap-select/bootstrap-select.min.css'),
						'enable' => true,
					],
					[
						'id' => 'bootstrap-select2-style',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/select2/select2.css'),
						'enable' => true,
					],
					[
						'id' => 'bootstrap-select2-metronic-style',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/select2/select2-metronic.css'),
						'enable' => true,
					],
					[
						'id' => 'bootstrap-select2-jquery-multi',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/jquery-multi-select/css/multi-select.css'),
						'enable' => true,
					],
				]
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="BootstrapDateTime">
			'bootstrap-datetime' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('metronic/plugins/data-tables/DT_bootstrap.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'bootstrap-datetime-init',
						'type' => \Zbase\Models\View::SCRIPT,
						'enable' => true,
						'script' => 'jQuery(\'.date-picker\').datepicker({autoclose: true});',
						'onLoad' => true,
					],
					[
						'id' => 'bootstrap-datetime-datatables',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/data-tables/jquery.dataTables.js'),
						'enable' => true,
						'position' => 499,
					],
					[
						'id' => 'bootstrap-datetime-picker',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js'),
						'enable' => true,
						'position' => 498,
					],
					[
						'id' => 'bootstrap-datetimetime-picker',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js'),
						'enable' => true,
						'position' => 497,
					],
					[
						'id' => 'bootstrap-daterange-picker',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/bootstrap-daterangepicker/daterangepicker.js'),
						'enable' => true,
						'position' => 496,
					],
					[
						'id' => 'bootstrap-daterange-picker',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'),
						'enable' => true,
						'position' => 495,
					],
					[
						'id' => 'bootstrap-datetime',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/data-tables/DT_bootstrap.css'),
						'enable' => true,
						'position' => 498,
					],
					[
						'id' => 'bootstrap-datetime',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/bootstrap-datepicker/css/datepicker.css'),
						'enable' => true,
						'position' => 497,
					],
					[
						'id' => 'bootstrap-datetimepicker',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css'),
						'enable' => true,
						'position' => 497,
					],
					[
						'id' => 'bootstrap-datetime-range',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css'),
						'enable' => true,
						'position' => 497,
					],
				],
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="FileUpload">
			'fileupload' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('metronic/plugins/fancybox/source/jquery.fancybox.pack.js'),
				'enable' => true,
				'position' => 890,
				'dependents' => [
					[
						'id' => 'fileupload_blueimp',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css'),
						'enable' => true,
						'position' => 895,
					],
					[
						'id' => 'fileupload_style',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/jquery-file-upload/css/jquery.fileupload.css'),
						'enable' => true,
						'position' => 896,
					],
					[
						'id' => 'fileupload_facybox',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/fancybox/source/jquery.fancybox.css'),
						'enable' => true,
						'position' => 897,
					],
					[
						'id' => 'fileupload_ui',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/jquery-file-upload/css/jquery.fileupload-ui.css'),
						'enable' => true,
						'position' => 898,
					],
					[
						'id' => 'fileupload_uiwidget',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js'),
						'enable' => true,
						'position' => 899,
					],
					[
						'id' => 'fileupload_tmpl',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/vendor/tmpl.min.js'),
						'enable' => true,
						'position' => 898,
					],
					[
						'id' => 'fileupload_loadimage',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/vendor/load-image.min.js'),
						'enable' => true,
						'position' => 897,
					],
					[
						'id' => 'fileupload_blob',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js'),
						'enable' => true,
						'position' => 896,
					],
					[
						'id' => 'fileupload_blueimp',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js'),
						'enable' => true,
						'position' => 895,
					],
					[
						'id' => 'fileupload_iframe_transport',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/jquery.iframe-transport.js'),
						'enable' => true,
						'position' => 894,
					],
					[
						'id' => 'fileupload_fileupload',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/jquery.fileupload.js'),
						'enable' => true,
						'position' => 893,
					],
					[
						'id' => 'fileupload_process',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/jquery.fileupload-process.js'),
						'enable' => true,
						'position' => 892,
					],
					[
						'id' => 'fileupload_image',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/jquery.fileupload-image.js'),
						'enable' => true,
						'position' => 891,
					],
					[
						'id' => 'fileupload_audio',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/jquery.fileupload-audio.js'),
						'enable' => false,
						'position' => 890,
					],
					[
						'id' => 'fileupload_video',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/jquery.fileupload-video.js'),
						'enable' => false,
						'position' => 889,
					],
					[
						'id' => 'fileupload_validate',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/jquery.fileupload-validate.js'),
						'enable' => true,
						'position' => 888,
					],
					[
						'id' => 'fileupload_ui',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-file-upload/js/jquery.fileupload-ui.js'),
						'enable' => true,
						'position' => 887,
					],
				]
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="Metronic::Admin">
			'metronic-admin' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('metronic/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'metronic-admin-appinit',
						'type' => \Zbase\Models\View::SCRIPT,
						'enable' => true,
						'script' => 'App.init();',
						'onLoad' => true,
					],
					[
						'id' => 'metronic-admin-slimscroll',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-slimscroll/jquery.slimscroll.min.js'),
						'enable' => true,
						'position' => 499,
					],
					[
						'id' => 'metronic-admin-blockui',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery.blockui.min.js'),
						'enable' => true,
						'position' => 498,
					],
					[
						'id' => 'metronic-admin-cookie',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery.cokie.min.js'),
						'enable' => true,
						'position' => 497,
					],
					[
						'id' => 'metronic-admin-uniform',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/uniform/jquery.uniform.min.js'),
						'enable' => true,
						'position' => 496,
					],
					[
						'id' => 'metronic-admin-app',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/scripts/core/app-zbase.js'),
						'enable' => true,
						'position' => 495,
					],
					[
						'id' => 'metronic-admin-font',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => '//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
						'enable' => true,
						'position' => 499,
					],
					[
						'id' => 'metronic-admin-font-awesome',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/font-awesome/css/font-awesome.min.css'),
						'enable' => true,
						'position' => 498,
					],
					[
						'id' => 'metronic-admin-uniform',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/uniform/css/uniform.default.css'),
						'enable' => true,
						'position' => 498,
					],
					[
						'id' => 'metronic-admin-metronic',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/style-metronic.css'),
						'enable' => true,
						'position' => 497,
					],
					[
						'id' => 'metronic-admin-style',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/style.css'),
						'enable' => true,
						'position' => 496,
					],
					[
						'id' => 'metronic-admin-responsive',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/style-responsive.css'),
						'enable' => true,
						'position' => 495,
					],
					[
						'id' => 'metronic-admin-plugins',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/plugins.css'),
						'enable' => true,
						'position' => 494,
					],
					[
						'id' => 'metronic-admin-theme',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/themes/default.css'),
						'enable' => true,
						'position' => 493,
					],
					[
						'id' => 'metronic-admin-custom',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/custom.css'),
						'enable' => true,
						'position' => 492,
					],
				]
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="Mobile::Angular">
			'mobileangular' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('mobile/angular/dist/js/angular-1.3.0.min.js'),
				'cdn' => '//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.0/angular.min.js',
				'enable' => true,
				'position' => 500,
				'dependents' => [
					'meta-author' => [
						'type' => \Zbase\Models\View::HEADMETA,
						'enable' => true,
						'name' => 'author',
						'content' => 'Dennes B Abing'
					],
					[
						'id' => 'mobileangular-route',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('mobile/angular/dist/js/angular-route-1.3.0.min.js'),
						'cdn' => '//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.0/angular-route.min.js',
						'enable' => true,
						'position' => 499,
					],
					[
						'id' => 'mobileangular-cookies',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('mobile/angular/dist/js/angular-cookies-1.2.13.js'),
						'cdn' => '//code.angularjs.org/1.2.13/angular-cookies.js',
						'enable' => true,
						'position' => 499,
					],
					[
						'id' => 'mobileangular-ui',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('mobile/angular/dist/js/mobile-angular-ui.min.js'),
						'enable' => true,
						'position' => 498,
					],
					[
						'id' => 'mobileangular-ui-gestures',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('mobile/angular/dist/js/mobile-angular-ui.gestures.min.js'),
						'enable' => true,
						'position' => 497,
					],
					[
						'id' => 'mobileangular-zbase',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('mobile/angular/dist/js/zbase-angular.js'),
						'enable' => false,
						'position' => 496,
					],
					[
						'id' => 'mobileangular-ng-flow-standalone',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('mobile/angular/dist/js/ng-flow-standalone.min.js'),
						'enable' => true,
						'position' => 494,
					],
					[
						'id' => 'mobileangular-ui-hover',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('mobile/angular/dist/css/mobile-angular-ui-hover.min.css'),
						'enable' => true,
						'position' => 499,
					],
					[
						'id' => 'mobileangular-ui-base',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('mobile/angular/dist/css/mobile-angular-ui-base.min.css'),
						'enable' => true,
						'position' => 498,
					],
					[
						'id' => 'mobileangular-ui-desktop',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('mobile/angular/dist/css/mobile-angular-ui-desktop.min.css'),
						'enable' => true,
						'position' => 497,
					],
					[
						'id' => 'mobileangular-zbase-style',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('mobile/angular/dist/css/zbase-angular.css'),
						'enable' => true,
						'position' => 496,
					],
				]
			]
		// </editor-fold>
		],
		'autoload' => [
			'plugins' => ['_token']
		]
	// </editor-fold>
	],
];
