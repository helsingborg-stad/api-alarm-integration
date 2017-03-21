ApiAlarmIntegration = ApiAlarmIntegration || {};
ApiAlarmIntegration.Helper = ApiAlarmIntegration.Helper || {};

ApiAlarmIntegration.Helper.Template = (function () {

    var _templates = [];

    function Template() {
        this.getTemplates();
    }

    Template.prototype.render = function(key, data) {
        // Get template html
        var template = _templates[key];

        // Replace template strings
        template = template.replace(/{{\s*([\w\.]+)\s*}}/g, function($1, $2) {
            var value = data;
            var objKey = $2.split('.');

            for (var i = 0; i < objKey.length; i++) {
                value = value[objKey[i]];
            }

            return value;
        });

        // Return the new html
        return template;
    };

    /**
     * Store templates in _templates array and removes it from the DOM
     * @return {void}
     */
    Template.prototype.getTemplates = function() {
        var templateElements = document.querySelectorAll('[data-template]');

        for (var i = 0; i < templateElements.length; i++) {
            var el = templateElements[i];
            var key = el.getAttribute('data-template');
            var html = el.outerHTML.replace(/data-template="([^\"]*)"/ig, '');

            _templates[key] = html;
            el.parentElement.removeChild(el);
        }
    };

    return new Template();

})();
