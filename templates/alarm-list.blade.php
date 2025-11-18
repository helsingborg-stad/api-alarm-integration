
<div data-module-refresh-interval="{!! $refreshInterval !!}" data-module-id="{!! $ID !!}">
    @if (isset($hideTitle) && !$hideTitle && !empty($postTitle))
        @typography([
            'id' => 'mod-alarm-list-' . $ID . '-label',
            'element' => 'h2',
            'classList' => ['module-title']
        ])
        {!! $postTitle !!}
        @endtypography
    @endif

    @if($communicationError) 
        @notice([
            'type' => 'info',
            'message' => (object) [
                'title' => $communicationError->get_error_message(),
                'size' => 'sm',
            ],
            'icon' => [
                'name' => 'info',
                'size' => 'md',
                'color' => 'white'
            ]
        ])
        @endnotice
    @else 

        @collection([
            'id' => 'mod-alarm-list-' . $ID . '-collection',
            'classList' => ['c-collection--alarm-list'],
            'attributeList' => [
                'aria-label' => __('Alarm List', 'api-alarm-integration'),
                'aria-busy' => $isAjaxRequest ? 'true' : 'false'
            ],
            'bordered' => false,
            'unbox' => true,
        ])

            @php $previousDate = null; @endphp
            @foreach($alarm as $item)

                @element(['classList' => [
                    'c-collection__header', 
                    'u-display--flex', 
                    'u-align-items--center', 
                    'u-justify-content--space-between', 
                    'u-gap-4', 
                    'u-margin__bottom--1',
                    $loop->first ? '' : 'u-margin__top--4',
                ]])
                    @if($item->date_day !== $previousDate)
                        @php $previousDate = $item->date_day; @endphp
                        @typography([
                            "element" => "p",
                            "variant" => "meta",
                            "classList" => [
                                !$isAjaxRequest ? 'u-preloader' : ''
                            ],
                            'attributeList' => ['aria-hidden' => $isAjaxRequest ? 'false' : 'true']
                        ])
                        <strong>{{ $item->date_day }}</strong>
                        @endtypography
                    @endif

                    @if($loop->first)
                        @typography([
                            "element" => "p",
                            "variant" => "meta",
                            "classList" => [
                                !$isAjaxRequest ? 'u-preloader' : '',
                                'u-color__text--dark',
                            ],
                            'attributeList' => ['aria-hidden' => $isAjaxRequest ? 'false' : 'true']
                        ])
                            {!! $dateTimeChangedLabel !!}
                        @endtypography
                    @endif

                @endelement

                @paper(['classList' => ['c-paper--alarm-list', 'u-margin__bottom--1']])

                    @collection__item([])

                        @slot('prefix')
                            <div class="c-collection__icon">
                                <div class="{{ !$isAjaxRequest ? 'u-preloader' : ''}}">
                                    @icon([
                                        'icon' => $item->icon, 
                                        'size' => 'md', 
                                        'classList' => [
                                            'u-color__text--white', 
                                            'u-color__bg--' . $item->level_color, 
                                            'u-rounded--8', 
                                            'u-padding--2', 
                                            'u-align-items--center', 
                                            'u-justify-content--center',
                                            'u-height--100'
                                        ]])
                                    @endicon
                                </div>
                                <!-- {{ $item->level }} -->
                            </div>
                        @endslot

                        @slot('secondary')

                            @element([
                                'classList' => [
                                    'u-display--flex', 
                                    'u-flex-direction--column', 
                                    'u-gap-1', 
                                    'u-align-items--end', 
                                    'u-justify-content--space-between', 
                                    'u-height--100'
                                ]
                            ])        
                                @typography([
                                    'element' => 'div', 
                                    'variant' => 'meta', 
                                    'attributeList' => ['data-tooltip' => $item->date_time], 
                                    'classList' => [
                                        'u-display--flex', 
                                        'u-align-items--center', 
                                        'u-justify-content--end', 
                                        'u-gap-1', 
                                        'u-color__text--dark',
                                    ]
                                ])
                                <span class="{{ !$isAjaxRequest ? 'u-preloader' : '' }}">
                                    {{ $item->time }}
                                </span>
                                @endtypography

                                @if($item->level && $item->level_numeric)
                                    @element([
                                        'classList' => ['u-display--inline-flex', 'u-gap-0', 'u-align-items--end', 'u-flex-direction--row', !$isAjaxRequest ? 'u-preloader' : ''],
                                        'attributeList' => [
                                            'aria-label' => $item->level_label,
                                            'data-tooltip' => $item->level_label
                                        ]
                                    ])
                                        @for ($i = 0; $i < $item->level_numeric; $i++)
                                            @icon([
                                                'icon' => 'contacts_product',
                                                'size' => 'sm',
                                                'classList' => [
                                                    'u-color__text--dark'
                                                ]
                                            ])
                                            @endicon
                                        @endfor
                                    @endelement
                                @endif
                            @endelement
                        @endslot

                        @typography(['element' => 'h4', 'autopromote' => false, 'useHeadingsContext' => false, 'classList' => [!$isAjaxRequest ? 'u-preloader' : '']])
                            {!! $item->title !!} 
                        @endtypography

                        @typography(['element' => 'div', 'variant' => 'meta', 'classList' => [!$isAjaxRequest ? 'u-preloader' : '']])
                            {{ $item->streetname }}, {{ $item->city }}
                        @endtypography  

                        @if($item->moredetails)
                            <div class="u-padding--0 u-margin__top--1">
                                @foreach($item->moredetails as $detail)
                                <span class="{{ !$isAjaxRequest ? 'u-preloader' : '' }}">
                                    @typography([
                                        'element' => 'span', 
                                        'variant' => 'meta', 
                                        'classList' => [
                                            'u-color__bg--complementary-lighter', 
                                            'u-rounded--4', 'u-padding__x--1',
                                            
                                        ], 
                                        'attributeList' => ['data-tooltip' => $detail->date_time]])
                                        {{ $detail->unit }}
                                    @endtypography
                                </span>
                                @endforeach
                            </div>
                        @endif
                    @endcollection__item
                @endpaper
            @endforeach
        @endcollection
    @endif
</div>
