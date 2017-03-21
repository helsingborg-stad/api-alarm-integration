<div class="<?php echo implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $module->post_type, $args)); ?> <?php echo get_field('font_size', $module->ID) ? get_field('font_size', $module->ID) : ''; ?>">
    <?php if (!$module->hideTitle && !empty($module->post_title)) : ?>
        <h4 class="box-title"><?php echo apply_filters('the_title', $module->post_title); ?></h4>
    <?php endif; ?>

    <ul class="accordion accordion-list accordion-list-small" data-api-alarm-integration="load" data-alamrs-per-page="<?php echo get_field('alarms_per_page', $module->ID); ?>" data-alamrs-current-page="0" data-alarm-api="<?php echo trailingslashit(get_field('api_url', $module->ID)); ?>">
        <li class="accordion-section no-padding" data-template="api-alarm-integration-row">
            <input type="radio" name="active-section" id="accordion-section-{{ id }}">
            <label class="accordion-toggle link-item" for="accordion-section-{{ id }}">
                {{ place }}: {{ title.rendered }}
            </label>
            <div class="accordion-content">
                <table>
                    <tr>
                        <td><strong>Tidpunk:</strong></td><td>{{ date }}</td>
                    </tr>
                    <tr>
                        <td><strong>Händelse:</strong></td><td>{{ title.rendered }}</td>
                        <td><strong>Larmnivå:</strong></td><td>{{ type }}</td>
                    </tr>
                    <tr>
                        <td><strong>Adress:</strong></td><td>{{ address }}, {{ place }}</td>
                        <td><strong>Station:</strong></td><td> {{ station.title }}</td>
                    </tr>
                </table>

                {% if data.content.rendered.length %}
                <article>
                    {{ content.rendered }}
                </article>
                {% endif %}
            </div>
        </li>
    </ul>
</div>
