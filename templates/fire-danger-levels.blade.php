<div data-module-refresh-interval="{!! $refreshInterval !!}" data-module-id="{!! $ID !!}">
    @if (!$hideTitle && !empty($postTitle))
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

        <div class="o-grid-12@sm o-grid-6@md o-grid-4@lg">
            @notice([
                'type' => $notice['type'],
                'message' => [
                'title' => $notice['title'],
                'text' => $notice['text'],
                'size' => 'sm',
                ],
                'classList' => [!$isAjaxRequest ? 'u-preloader' : ''],
                'attributeList' => ['aria-hidden' => $isAjaxRequest ? 'false' : 'true'],
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
        "classList" => [!$isAjaxRequest ? 'u-preloader' : ''],
        'attributeList' => ['aria-hidden' => $isAjaxRequest ? 'false' : 'true']
    ])
    {!! $dateTimeChangedLabel !!}
    @endtypography
</div>
