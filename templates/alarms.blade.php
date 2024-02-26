@card([
    'heading' => $hideTitle ? $post_title : '',
    'classList' => [
        'c-card--panel'
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
        <div class="filters o-grid o-grid--form u-margin__bottom--4">
            <div class="o-grid-12">
                @field([
                    'type' => 'search',
                    'name' => 'text',
                    'size' => 'md',
                    'id' => 'data-alarm-filter-text',
                    'attributeList' => [
                        'data-alarm-filter' => "text"
                    ],
                    'label' => translate('Search', 'api-alarm-integration')
                ])
                @endfield
            </div>
            
            @if($places)
                <div class="o-grid-12">
                    @select([
                        'id' => 'data-alarm-filter-place',
                        'label' => __('Place', 'api-alarm-integration'),
                        'required' => true,
                        'attributeList' => ['data-alarm-filter' => 'place'],
                        'options' => '',
                    ])
                        @foreach ($places as $place)
                            @if (is_object($place))
                                <option value="{{ $place->id }}">{{ $place->name }}</option>
                                @php $selectArr[$place->id] = $place->name; @endphp
                            @endif
                        @endforeach
                    @endselect
                </div>
            @endif

            <div class="o-grid-12 o-grid-6@md"> 
                @field([
                    'type' => 'date',
                    'name' => 'date_from',
                    'value' => '',
                    'id' => 'data-alarm-filter-date-from',
                    'label' => translate('Date from', 'api-alarm-integration'),
                    'attributeList' => [
                        'data-invalid-message' => "You need to add a valid date!",
                        'data-alarm-filter' => 'date-from'
                    ]
                ])
                @endfield
            </div>

            <div class="o-grid-12 o-grid-6@md">
                @field([
                    'type' => 'date',
                    'name' => 'date_to',
                    'value' => '',
                    'label' => translate('Date to', 'api-alarm-integration'),
                    'id' => 'data-alarm-filter-date-to',
                    'attributeList' => [
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

        {{-- Loader --}}
        <div data-template="api-alarm-integration-loader" data-api-alarms-loader>
            @loader([
                'size' => 'sm',
                'color' => 'primary',
                'shape' => 'linear'
            ])
            @endloader
        </div>

        {{-- List --}}
        @accordion([
            'id' => 'alarm-data-container',
            'classList' => [
                'modularity-mod-alarms__container'
            ],
            'attributeList' => [
                'data-api-alarms-container' => '',
                'data-api-alarm-integration' => 'load',
                'js-expand-container' => '',
                '' => '',
                '' => '',
            ]
        ])
            <div class="c-accordion__section modularity-mod-alarms__section" data-template="api-alarm-integration-row" data-api-alarms-row>
                <button class="c-accordion__button">
                    <span class="c-accordion__button-wrapper" tabindex="-1">
                        @date(['timestamp' => '{# date #}'])
                        @enddate
                        <span class="link-item link">
                            {## if (typeof data.place[0] != 'undefined') ##}{# place[0].name #}: {## endif ##}{# title.rendered #}
                        </span>

                        @icon(['icon' => 'keyboard_arrow_down', 'size' => 'md', 'classList' => ['c-accordion__icon']])
                        @endicon
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
        @endaccordion

        {{-- Communication error message --}}
        @notice([
            'type' => 'warning',
            'message' => [
                'text' => $lang->communicationError,
                'size' => 'sm'
            ],
            'icon' => [
                'name' => 'report',
                'size' => 'md',
                'color' => 'white'
            ],
            'attributeList' => [
                "data-template"     => "api-alarm-integration-error"
            ]
        ])
        @endnotice

        {{-- No results --}}
        @notice([
            'type' => 'info',
            'message' => [
                'text' => $lang->noResults,
                'size' => 'sm'
            ],
            'icon' => [
                'name' => 'report',
                'size' => 'md',
                'color' => 'white'
            ],
            'attributeList' => [
                "data-template"     => "api-alarm-integration-no-results"
            ]
        ])
        @endnotice

        {{-- Load more button --}}
        <div class="u-display--flex" data-template="api-alarm-integration-load-more" data-api-alarms-load-more>
            @button([
                'text' => $lang->loadMore,
                'color' => 'default',
                'style' => 'filled',
                'attributeList' => [
                    'data-action' => 'api-alarm-integration-load-more'
                ],
                'classList' => [
                    'u-margin__x--auto',
                    'u-margin__top--2',
                    'u-margin__bottom--4'
                ]
            ])
            @endbutton
        </div>
    </div>
@endcard