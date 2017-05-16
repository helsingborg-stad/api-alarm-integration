<div class="{{ $classes }}">
    @if (!$hideTitle && !empty($post_title))
        <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <div class="box-content" style="border-bottom:0;">
        <div class="filters hidden">
            <div class="form-group">
                <label for="data-alarm-filter-text"><?php _e('Text search', 'api-alarm-integration'); ?></label>
                <input type="search" data-alarm-filter="text" id="data-alarm-filter-text" placeholder="<?php _e('Search', 'api-alarm-integration'); ?>…">
            </div>
            <div class="form-group">
                <label for="data-alarm-filter-place"><?php _e('Place', 'api-alarm-integration'); ?></label>
                <select data-alarm-filter="place" id="data-alarm-filter-place">
                    <option value=""><?php _e('All', 'api-alarm-integration'); ?></option>
                    <?php foreach ((array) \ApiAlarmIntegration\Module::getPlaces($apiUrl) as $place) : ?>
                        <?php if (is_object($place)) { ?>
                            <option value="<?php echo $place->id; ?>"><?php echo $place->name; ?></option>
                        <?php } ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <div class="grid">
                    <div class="grid-md-6">
                        <label for="data-alarm-filter-date-from"><?php _e('Date from', 'api-alarm-integration'); ?></label>
                        <input type="date" data-alarm-filter="date-from" id="data-alarm-filter-date-from" class="datepicker">
                    </div>
                    <div class="grid-md-6">
                        <label for="data-alarm-filter-date-to"><?php _e('Date to', 'api-alarm-integration'); ?></label>
                        <input type="date" data-alarm-filter="date-to" id="data-alarm-filter-date-to" class="datepicker">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" data-alarm-filter="search"><?php _e('Search', 'api-alarm-integration'); ?></button>
            </div>
        </div>

        <button class="btn btn-plain btn-block" data-action="api-alarm-integration-toggle-filters"><span><?php _e('Show filters', 'api-alarm-integration'); ?></span> <i class="pricon pricon-caret-down pricon-sm" style="position:relative;top:-2px;"></i></button>
    </div>

    <div class="box-content no-padding">
        <ul class="accordion accordion-list accordion-list-small alarms-container" data-api-alarm-integration="load" data-alamrs-per-page="{{ $options['alarms_per_page'] }}" data-alamrs-current-page="0" data-alarm-api="{{ trailingslashit($options['api_url']) }}">
            <li style="padding:20px 0;" data-template="api-alarm-integration-loading" data-api-alarms-load-more>
                <div class="loading">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </li>
            <li class="accordion-section no-padding" data-template="api-alarm-integration-row">
                <input type="radio" name="active-section" id="alarm-{# id #}">
                <label class="accordion-toggle block-level" for="alarm-{# id #}"><span class="link-item link">{# place[0].name #}: {# title.rendered #}</span><time class="date pull-right text-sm text-dark-gray">{# date #}</time></label>
                <div class="accordion-content">
                    <table>
                        <tr>
                            <td><strong><?php _e('Time', 'api-alarm-integration'); ?>:</strong></td><td>{# date #}</td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Incident', 'api-alarm-integration'); ?>:</strong></td><td>{# title.rendered #}</td>
                            <td><strong><?php _e('Level', 'api-alarm-integration'); ?>:</strong></td><td>{# type #}</td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Address', 'api-alarm-integration'); ?>:</strong></td><td>{# address #}, {# place[0].name #}</td>
                            <td><strong><?php _e('Station', 'api-alarm-integration'); ?>:</strong></td><td> {# station.title #}</td>
                        </tr>
                    </table>
                </div>
            </li>
            <li data-template="api-alarm-integration-load-more" style="padding:10px;" data-api-alarms-load-more><button data-action="api-alarm-integration-load-more" class="btn btn-block"><?php _e('Show more alarms', 'api-alarm-integration'); ?></button></li>
        </ul>
    </div>
</div>
