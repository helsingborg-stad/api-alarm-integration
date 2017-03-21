<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?> <?php echo get_field('font_size', $module->ID) ? get_field('font_size', $module->ID) : ''; ?>">
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <h4 class="box-title"><?php echo apply_filters('the_title', $module->post_title); ?></h4>
    <?php endif; ?>

    <div class="box-content filters" style="border-bottom:0;">
        <div class="grid-table no-padding">
            <div class="grid-auto">
                <input type="search" data-alarm-filter="freetext" placeholder="<?php _e('Search…', 'api-alarm-integration'); ?>">
            </div>
            <div class="grid-auto">
                <select data-alarm-filter="place">
                    <option value="Helsingborg">Helsingborg</option>
                </select>
            </div>
            <div class="grid-fit-content">
                <button class="btn btn-primary">Sök</button>
            </div>
        </div>
    </div>

    <div class="box-content no-padding">
        <ul class="accordion accordion-list accordion-list-small alarms-container" data-api-alarm-integration="load" data-alamrs-per-page="<?php echo get_field('alarms_per_page', $module->ID); ?>" data-alamrs-current-page="0" data-alarm-api="<?php echo trailingslashit(get_field('api_url', $module->ID)); ?>">
            <li style="padding:20px 0;" data-template="api-alarm-integration-loading" data-api-alarms-load-more>
                <div class="loading">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </li>
            <li class="accordion-section no-padding" data-template="api-alarm-integration-row">
                <input type="radio" name="active-section" id="accordion-section-{{ id }}">
                <label class="accordion-toggle link-item" for="accordion-section-{{ id }}">{{ place }}: {{ title.rendered }}</label>
                <div class="accordion-content">
                    <table>
                        <tr>
                            <td><strong><?php _e('Time', 'api-alarm-integration'); ?>:</strong></td><td>{{ date }}</td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Incident', 'api-alarm-integration'); ?>:</strong></td><td>{{ title.rendered }}</td>
                            <td><strong><?php _e('Level', 'api-alarm-integration'); ?>:</strong></td><td>{{ type }}</td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Address', 'api-alarm-integration'); ?>:</strong></td><td>{{ address }}, {{ place }}</td>
                            <td><strong><?php _e('Station', 'api-alarm-integration'); ?>:</strong></td><td> {{ station.title }}</td>
                        </tr>
                    </table>

                    {% if data.content.rendered.length %}
                    <article>
                        {{ content.rendered }}
                    </article>
                    {% endif %}
                </div>
            </li>
            <li data-template="api-alarm-integration-load-more" style="padding:10px;" data-api-alarms-load-more><button data-action="api-alarm-integration-load-more" class="btn btn-block"><?php _e('Show more alarms', 'api-alarm-integration'); ?></button></li>
        </ul>
    </div>
</div>
