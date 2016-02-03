<?php if(!empty($breadcrumbs)): ?>
	<ol <?php echo zbase_view_ui_tag_attributes('breadcrumb', 'class="breadcrumb"'); ?>>
		<li class="first">
			<a href="{{ zbase_url_from_route('home') }}" title="Home">Home</a>
		</li>
		<?php $counter = 0; ?>
		<?php foreach ($breadcrumbs as $breadcrumb): ?>
			<?php $counter++; ?>
			<?php $config = zbase_config_get('nav.front.main.' . $breadcrumb, []); ?>
			<?php if(!empty($config)): ?>
				<?php
				$url = zbase_url_from_config($config['url']);
				$label = zbase_value_get($config, 'label', null);
				$title = zbase_value_get($config, 'title', null);
				?>
				<li class="<?php echo $counter == count($breadcrumbs) ? 'last' : null; ?>">
					<a href="<?php echo $url ?>" title="<?php echo $title ?>"><?php echo $label ?></a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>