
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

        
        @collection([
            'id' => 'mod-fire-danger-levels-' . $ID . '-collection',
            'classList' => ['c-collection--alarm-list'],
            'attributeList' => [
                'aria-label' => __('Alarm List', 'api-alarm-integration'),
                'aria-busy' => $isAjaxRequest ? 'true' : 'false'
            ],
            'bordered' => false,
            'unbox' => true,
        ])
            @foreach($alarm as $item)
       @paper(['classList' => ['c-paper--alarm-list', 'u-margin__bottom--1']])

            @collection__item([])

                

                    @slot('prefix')
                        <div class="c-collection__icon">
                            @icon(['icon' => $item->icon, 'size' => 'md', 'classList' => ['u-color__text--white', 'u-color__bg--primary', 'u-rounded--8', 'u-padding--2', 'u-align-items--center', 'u-justify-content--center']])
                            @endicon
                        </div>
                    @endslot

                    @slot('secondary')
                        @typography(['element' => 'div', 'variant' => 'meta', 'attributeList' => ['data-tooltip' => $item->date_time], 'classList' => ['u-display--flex', 'u-align-items--top', 'u-gap-1', 'u-color__text--light']])
                            {{ $item->time }}
                        @endtypography
                    @endslot

                    @typography(['element' => 'h4'])
                        {!! $item->title !!}
                    @endtypography

                    @typography(['element' => 'div', 'variant' => 'meta'])
                        {{ $item->streetname }}
                    @endtypography  


                    @if($item->moredetails)

                        <div class="u-padding--0 u-margin__top--1">
                            @foreach($item->moredetails as $detail)
                                @typography(['element' => 'span', 'variant' => 'meta', 'classList' => ['u-color__bg--complementary-lighter', 'u-rounded--4', 'u-padding__x--1'], 'attributeList' => ['data-tooltip' => $detail->date_time]])
                                    {{ $detail->unit }}
                                @endtypography
                            @endforeach
                        </div>
                    @endif
                    
                 

                @endcollection__item

                   @endpaper
            @endforeach
        @endcollection
        

        @typography([
            "element" => "p",
            "classList" => [!$isAjaxRequest ? 'u-preloader' : ''],
            'attributeList' => ['aria-hidden' => $isAjaxRequest ? 'false' : 'true']
        ])
        {!! $dateTimeChangedLabel !!}
        @endtypography
    </div>

