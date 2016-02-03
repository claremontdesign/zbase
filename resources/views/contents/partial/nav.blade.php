<?php if(!empty($nav)): ?>
	<?php if(empty($children)): ?>
		<ul <?php echo zbase_view_ui_tag_attributes('nav', 'class="nav navbar-nav"'); ?>>
			<?php foreach ($nav as $n): ?>
				<?php if(!empty($n->inMenu())): ?>
					<?php if($n->hasChildren()): ?>
						<li class="dropdown">
							<a <?php echo $n->renderHtmlAttributes(); ?> href="<?php echo $n->getHref() ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $n->getLabel() ?> <span class="caret"></span></a>
							<?php echo zbase_view_render($template, ['nav' => $n->getChildren(), 'children' => true, 'template' => $template]); ?>
						</li>
					<?php else: ?>
						<li class="<?php echo!empty($n->active()) ? 'active' : null ?>">
							<a href="<?php echo $n->getHref() ?>" <?php echo $n->renderHtmlAttributes(); ?>><?php echo $n->getLabel() ?> </a>
						</li>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<ul <?php echo zbase_view_ui_tag_attributes('nav-sub', 'class="dropdown-menu"'); ?>>
			<?php foreach ($nav as $n): ?>
				<?php if(!empty($n->inMenu())): ?>
					<?php if($n->hasChildren()): ?>
						<li class="dropdown-submenu">
							<a href="<?php echo $n->getHref() ?>" title="<?php echo $n->getTitle() ?>" <?php echo $n->renderHtmlAttributes(); ?> tabindex="-1"><?php echo $n->getLabel() ?> <span class="caret"></span></a>
							<?php echo zbase_view_render($template, ['nav' => $n->getChildren(), 'children' => true, 'template' => $template]); ?>
						</li>
					<?php else: ?>
						<li class="<?php echo!empty($n->active()) ? 'active' : null ?>">
							<a href="<?php echo $n->getHref() ?>" <?php echo $n->renderHtmlAttributes(); ?>><?php echo $n->getLabel() ?> </a>
						</li>
					<?php endif; ?>
					<?php if($n->hasSeparator()): ?>
						<li role="separator" class="divider"></li>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
		</ul>
	<?php endif; ?>
<?php endif; ?>