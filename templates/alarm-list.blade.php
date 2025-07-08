
    <div data-module-refresh-interval="{!! $refreshInterval !!}" data-module-id="{!! $ID !!}">
        @if (isset($hideTitle) && !$hideTitle && !empty($postTitle))
        @typography([
            'id' => 'mod-fire-danger-levels-' . $ID . '-label',
            'element' => 'h2',
            'classList' => ['module-title']
        ])
        {!! $postTitle !!}
        @endtypography
        @endif

        @paper(['classList' => ['c-paper--alarm-list']])
        @collection([
            'id' => 'mod-fire-danger-levels-' . $ID . '-collection',
            'classList' => ['c-collection--alarm-list'],
            'attributeList' => [
                'aria-label' => __('Alarm List', 'api-alarm-integration'),
                'aria-busy' => $isAjaxRequest ? 'true' : 'false'
            ],
            'bordered' => true,
            'unbox' => true,
        ])
            @foreach($alarm as $item)
                @collection__item([])

                    @slot('prefix')
                        <div class="c-collection__icon">
                            @icon(['icon' => $item->icon, 'size' => 'md'])
                            @endicon
                        </div>
                    @endslot

                    @slot('secondary')
                    <div data-tooltip="{{$item->date_time}}" class="u-display--flex u-align-items--center u-gap-1 u-color__text--light">   
                        @icon(['icon' => 'alarm', 'size' => 'sm'])
                        @endicon
                        {{ $item->time }}
                    </div>
                    @endslot

                    @typography(['element' => 'h4'])
                        {!! $item->title !!}
                    @endtypography


                    @if($item->moredetails)

                        <ul class="u-unlist u-padding--0 u-margin__left--0 u-margin__top--1">
                            @foreach($item->moredetails as $detail)
                            <li class="u-padding--0">
                                @typography(['element' => 'div', 'variant' => 'meta'])
                                Station <strong>{{ $detail->unit }}</strong> utlarmad till <strong>{{ $detail->streetname }}</strong> den <strong>{{ $detail->date_time }}</strong>.
                                @endtypography
                            </li>
                        @endforeach
                        </ul>
                    @endif

                @endcollection__item
            @endforeach
        @endcollection
        @endpaper

        @typography([
            "element" => "p",
            "classList" => [!$isAjaxRequest ? 'u-preloader' : ''],
            'attributeList' => ['aria-hidden' => $isAjaxRequest ? 'false' : 'true']
        ])
        {!! $dateTimeChangedLabel !!}
        @endtypography
    </div>

