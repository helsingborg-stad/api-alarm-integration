<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?> <?php echo get_field('font_size', $module->ID) ? get_field('font_size', $module->ID) : ''; ?>">
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <h4 class="box-title"><?php echo apply_filters('the_title', $module->post_title); ?></h4>
    <?php endif; ?>
    <ul data-api-alarm-integration="load" data-alamrs-per-page="<?php echo get_field('alarms_per_page', $module->ID); ?>" data-alamrs-current-page="0" data-alarm-api="<?php echo trailingslashit(get_field('api_url', $module->ID)); ?>">
        <li data-template="api-alarm-integration-row">
            <a href="#">
                <span class="link-item title"><strong>{{ place }}:</strong> {{ title.rendered }}</span>
                <time class="date text-sm text-dark-gray">{{ date }}</time>
            </a>
        </li>
    </ul>
</div>
