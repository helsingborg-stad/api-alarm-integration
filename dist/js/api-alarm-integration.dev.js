var ApiAlarmIntegration = {};
ApiAlarmIntegration.FetchAlarms = (function ($) {

    function FetchAlarms() {
        $('[data-api-alarm-integration="load"]').each(function (index, element) {
            console.log(element);
        }.bind(this));
    }



    return new FetchAlarms();

})(jQuery);
