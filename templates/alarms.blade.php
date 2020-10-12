

<div class="{{ $classes }}">

    @if (!$hideTitle && !empty($post_title))
        <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
    @endif

    <div class="box-content">

        <div class="filters hidden" style="display:none;">
            @grid(["container" => true, 'col_gap' => 1, 'row_gap' => 1])
                @grid([
                    "col" => [
                        "xs" => [1,13],
                        "sm" => [1,13],
                        "md" => [1,13],
                        "lg" => [1,13],
                        "xl" => [1,13]
                    ],
                    "row" => [
                        "xs" => [1,1],
                        "sm" => [1,1],
                        "md" => [1,1],
                        "lg" => [1,1],
                        "xl" => [1,1]
                    ]
                ])
                    <label for="data-alarm-filter-text"><?php _e('Text search', 'api-alarm-integration'); ?></label>

                    @field([
                        'type' => 'text',
                        'attributeList' => [
                            'type' => 'text',
                            'name' => 'text',
                            'data-alarm-filter' => "text",
                            'id' => 'data-alarm-filter-text'
                        ],
                        'label' => translate('Search', 'api-alarm-integration')
                    ])
                    @endfield
                @endgrid

                @grid([
                    "col" => [
                        "xs" => [1,13],
                        "sm" => [1,13],
                        "md" => [1,13],
                        "lg" => [1,13],
                        "xl" => [1,13]
                    ],
                    "row" => [
                        "xs" => [2,2],
                        "sm" => [2,2],
                        "md" => [2,2],
                        "lg" => [2,2],
                        "xl" => [2,2]
                    ]
                ])

                <label for="data-alarm-filter-place"><?php _e('Place', 'api-alarm-integration'); ?></label>

                <select id="data-alarm-filter-place" data-alarm-filter="place">
                    @php $selectArr[''] = __('All', 'api-alarm-integration'); @endphp
                    <option value=""><?php _e('All', 'api-alarm-integration'); ?></option>
                    @foreach ((array) \ApiAlarmIntegration\Module::getPlaces($options['api_url']) as $place)
                        @if (is_object($place))
                            <option value="{{ $place->id }}">{{ $place->name }}</option>
                            @php $selectArr[$place->id] = $place->name; @endphp
                        @endif
                    @endforeach
                </select>

            {{--

            @select([
                'label' => __('Place', 'api-alarm-integration'),
                'required' => true,
                'attributeList' => ['data-alarm-filter' => 'place'],
                'options' => $selectArr
            ])
            @endselect

            --}}

                @endgrid

                @grid([
                    "col" => [
                        "xs" => [1,13],
                        "sm" => [1,13],
                        "md" => [1,7],
                        "lg" => [1,7],
                        "xl" => [1,7]
                    ],
                    "row" => [
                        "xs" => [3,3],
                        "sm" => [3,3],
                        "md" => [3,3],
                        "lg" => [3,3],
                        "xl" => [3,3]
                    ]
                ])
                    <label for="data-alarm-filter-date-from"><?php _e('Date from', 'api-alarm-integration'); ?></label>

                    @field([
                        'type' => 'datepicker',
                        'value' => '',
                        'label' => translate('Date from', 'api-alarm-integration'),
                        'attributeList' => [
                            'type' => 'text',
                            'name' => 'text',
                            'data-invalid-message' => "You need to add a valid date!",
                            'data-alarm-filter' => 'date-from',
                            'id' => 'data-alarm-filter-date-from'
                        ]
                    ])
                    @endfield
                    
                @endgrid

                @grid([
                    "col" => [
                        "xs" => [1,13],
                        "sm" => [1,13],
                        "md" => [7,13],
                        "lg" => [7,13],
                        "xl" => [7,13]
                    ],
                    "row" => [
                        "xs" => [4,4],
                        "sm" => [4,4],
                        "md" => [3,3],
                        "lg" => [3,3],
                        "xl" => [3,3]
                    ]
                ])
                    <label for="data-alarm-filter-date-to"><?php _e('Date to', 'api-alarm-integration'); ?></label>

                    @field([
                        'type' => 'datepicker',
                        'value' => '',
                        'label' => translate('Date to', 'api-alarm-integration'),
                        'attributeList' => [
                            'type' => 'text',
                            'name' => 'text',
                            'data-invalid-message' => "You need to add a valid date!",
                            'data-alarm-filter' => 'date-to',
                            'id' => 'data-alarm-filter-date-to'
                        ]
                    ])
                    @endfield
                    
                @endgrid

                @grid([
                    "col" => [
                        "xs" => [1,1],
                        "sm" => [1,1],
                        "md" => [1,1],
                        "lg" => [1,1],
                        "xl" => [1,1]
                    ],
                    "row" => [
                        "xs" => [5,5],
                        "sm" => [5,5],
                        "md" => [4,4],
                        "lg" => [4,4],
                        "xl" => [4,4]
                    ]
                ])
                    @button([
                        'text' => translate('Search', 'api-alarm-integration'),
                        'color' => 'primary',
                        'style' => 'filled',
                        'attributeList' => ['data-alarm-filter' => 'search']
                    
                    ])
                    @endbutton
                @endgrid
            @endgrid
        </div>

        @button([
            'text' => translate('Show filters', 'api-alarm-integration'),
            'color' => 'primary',
            'style' => 'filled',
            'attributeList' => ['data-action' => 'api-alarm-integration-toggle-filters']
        
        ])
        @endbutton
    </div>

    <div class="box-content no-padding">

        <div id="5f843daf7ee5d" class="c-accordion alarms-container" data-api-alarm-integration="load"
             data-alamrs-per-page="{{ $options['alarms_per_page'] }}" data-alamrs-current-page="0"
             data-alarm-api="{{ trailingslashit($options['api_url']) }}" js-expand-container="" data-uid="5f843daf7ee74">

            <div data-template="api-alarm-integration-loading" data-api-alarms-load-more>

            </div>

            <div class="c-accordion__section" data-template="api-alarm-integration-row">
                <button class="c-accordion__button" data-js-toggle-trigger='mod-larm-content-{# id #}'>
                    <span class="c-accordion__button-wrapper" tabindex="-1">
                        <time class="date pull-right text-sm text-dark-gray">{# date #}</time>
                        <span class="link-item link"> {## if (typeof data.place[0] != 'undefined') ##}{# place[0].name #}: {## endif ##}{# title.rendered #}</span>
                        <i id="" class="c-icon c-accordion__icon c-icon--color- c-icon--size-md material-icons keyboard_arrow_down" data-uid="{# id #}">keyboard_arrow_down</i> <!-- No icon defined -->
                    </span>
                </button>
                <div class="accordion-content u-display--none" id="mod-larm-content-{# id #}" js-toggle-class="u-display--none">
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
</div>
