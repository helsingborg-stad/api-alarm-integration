@if (!empty($notices))
    <div @if ($isRefreshable) data-module-refresh-interval="{!! $refreshInterval !!}" data-module-id="{!! $ID !!}" @endif>
        @if (isset($hideTitle) && !$hideTitle && !empty($postTitle))
        @typography([
            'id' => 'mod-fire-danger-levels-' . $ID . '-label',
            'element' => 'h2',
            'classList' => ['module-title']
        ])
        {!! $postTitle !!}
        @endtypography
        @endif

        <div class="o-grid o-grid--half-gutter">
            @foreach($notices as $notice)

            <div class="o-grid-12@sm o-grid-6@md o-grid-4@lg o-grid-4@xl">
                @notice([
                    'type' => $notice['type'],
                    'altColor' => $notice['altColor'],
                    'message' => (object) [
                        'title' => $notice['title'],
                        'text' => $notice['text'],
                        'size' => 'sm',
                    ],
                    'classList' => [$usePreloader ? 'u-preloader' : ''],
                    'attributeList' => ['aria-hidden' => $usePreloader ? 'true' : 'false'],
                    'icon' => [
                        'name' => $notice['iconName'],
                        'size' => 'md',
                        'color' => 'white'
                    ]
                ])
                @endnotice
            </div>

            @endforeach
        </div>

        @typography([
            "element" => "p",
            "classList" => [$usePreloader ? 'u-preloader' : ''],
            'attributeList' => ['aria-hidden' => $usePreloader ? 'true' : 'false']
        ])
        {!! $dateTimeChangedLabel !!}
        @endtypography
    </div>
@endif
