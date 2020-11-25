@card([
    'heading' => $hideTitle ? $post_title : '',
    'classList' => [
        $classes
    ],
    'id' => 'mod-alarm',
    'attributeList' => [
        'data-api-alarms'               => '',
        'data-api-alarm-integration'    => "load",
        'data-alarms-per-page'          => $options['alarms_per_page'],
        'data-alarms-current-page'      => "0",
        'data-alarm-api'                => trailingslashit($options['api_url'])
    ]
])
    @if (!$hideTitle && !empty($post_title))
        <div class="c-card__header">
            @typography([
                "element" => "h4"
            ])
                {!! apply_filters('the_title', $post_title) !!}
            @endtypography
        </div>
    @endif

    <div class="c-card__body">
        <div class="box-content">
            <div class="filters o-grid">
                <div class="o-grid-12">

                    @field([
                        'type' => 'text',
                        'size' => 'md',
                        'id' => 'data-alarm-filter-text',
                        'attributeList' => [
                            'type' => 'text',
                            'name' => 'text',
                            'data-alarm-filter' => "text"
                        ],
                        'label' => translate('Search', 'api-alarm-integration')
                    ])
                    @endfield
                </div>
                
                <div class="o-grid-12">
                    @select([
                        'id' => 'data-alarm-filter-place',
                        'label' => __('Place', 'api-alarm-integration'),
                        'required' => true,
                        'attributeList' => ['data-alarm-filter' => 'place'],
                        'options' => '',
                    ])
                        @foreach ((array) \ApiAlarmIntegration\Module::getPlaces(trailingslashit($options['api_url'])) as $place)
                            @if (is_object($place))
                                <option value="{{ $place->id }}">{{ $place->name }}</option>
                                @php $selectArr[$place->id] = $place->name; @endphp
                            @endif
                        @endforeach
                    @endselect
                </div>

               
                <div class="o-grid-12 o-grid-6@md">

                    @field([
                        'type' => 'datepicker',
                        'value' => '',
                        'id' => 'data-alarm-filter-date-from',
                        'label' => translate('Date from', 'api-alarm-integration'),
                        'attributeList' => [
                            'type' => 'text',
                            'name' => 'text',
                            'data-invalid-message' => "You need to add a valid date!",
                            'data-alarm-filter' => 'date-from'
                        ]
                    ])
                    @endfield
                </div>

                <div class="o-grid-12 o-grid-6@md">

                    @field([
                        'type' => 'datepicker',
                        'value' => '',
                        'label' => translate('Date to', 'api-alarm-integration'),
                        'id' => 'data-alarm-filter-date-to',
                        'attributeList' => [
                            'type' => 'text',
                            'name' => 'text',
                            'data-invalid-message' => "You need to add a valid date!",
                            'data-alarm-filter' => 'date-to'
                        ]
                    ])
                    @endfield
                </div>

                <div class="o-grid-12">
                    @button([
                        'text' => translate('Search', 'api-alarm-integration'),
                        'color' => 'primary',
                        'style' => 'filled',
                        'attributeList' => ['data-alarm-filter-search' => '']
                    
                    ])
                    @endbutton
                </div>
            </div>
        </div>

        <div class="box-content no-padding">

            <div data-template="api-alarm-integration-loader" data-api-alarms-loader>
                @loader([
                    'size' => 'sm',
                    'color' => 'primary',
                    'shape' => 'linear'
                ])
                @endloader
            </div>

            <div id="alarm-data-container"
                class="c-accordion modularity-mod-alarms__container"
                data-api-alarms-container=""
                data-api-alarm-integration="load"
                js-expand-container=""
                data-uid="5f843daf7ee74">

                <div class="c-accordion__section modularity-mod-alarms__section" data-template="api-alarm-integration-row" data-api-alarms-row>
                    <button class="c-accordion__button">
                        <span class="c-accordion__button-wrapper" tabindex="-1">
                            <time class="date pull-right text-sm text-dark-gray">{# date #}</time>
                            <span class="link-item link"> {## if (typeof data.place[0] != 'undefined') ##}{# place[0].name #}: {## endif ##}{# title.rendered #}</span>
                            <i id="" class="c-icon c-accordion__icon c-icon--color- c-icon--size-md material-icons keyboard_arrow_down" data-uid="{# id #}">keyboard_arrow_down</i> <!-- No icon defined -->
                        </span>
                    </button>
                    <div class="modularity-mod-alarms__content" data-api-alarms-row-content id="mod-larm-content-{# id #}">
                        <table>
                            <tr>
                                <td><strong><?php _e('Time', 'api-alarm-integration'); ?>:</strong></td>
                                <td>{# date #}</td>
                            </tr>
                            <tr>
                                <td><strong><?php _e('Incident', 'api-alarm-integration'); ?>:</strong></td>
                                <td>{# title.rendered #}</td>
                                <td><strong><?php _e('Level', 'api-alarm-integration'); ?>:</strong></td>
                                <td>{# type #}</td>
                            </tr>
                            <tr>
                                <td><strong><?php _e('Address', 'api-alarm-integration'); ?>:</strong></td>
                                <td>{# address #}, {# place[0].name #}</td>

                                <td><strong><?php _e('Station', 'api-alarm-integration'); ?>:</strong></td>
                                <td> {# station.title #}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @typography([
                    "element"       => "h4",
                    "attributeList" => [
                        "data-template"     => "api-alarm-integration-error"
                    ]
                ])
                    @php _e('Failed to get alarms, please try again later!', 'api-alarm-integration') @endphp
                @endtypography

                @typography([
                    "id"            => 'mod-alarm-no-result',
                    "element"       => "h4",
                    "attributeList" => [
                        "data-template"     => "api-alarm-integration-no-results"
                    ]
                ])
                    @php _e('No results', 'api-alarm-integration') @endphp
                @endtypography
                
                <div data-template="api-alarm-integration-load-more" style="padding:10px;" data-api-alarms-load-more>
                    @button([
                        'text' => translate('Show more alarms', 'api-alarm-integration'),
                        'color' => 'primary',
                        'style' => 'filled',
                        'attributeList' => ['data-action' => 'api-alarm-integration-load-more']
    
                    ])
                    @endbutton
                </div>
            </div>
        </div>
    </div>
@endcard