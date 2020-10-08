export default (function () {

    let _templates = [];

    function Template() {
        this.getTemplates();
    }

    Template.prototype.render = function(key, data) {
        // Get template html
        let template = _templates[key];

        // Replace template strings
        template = template.replace(/{#\s*([\w\.\[\]]+)\s*#}/g, function($1, $2) {
            return Object.byString(data, $2);
        });

        // Handle if-statements
        template = template.replace(/{##\s*if(.*)\s*##}([\s\S]*?){##\s*endif\s*##}/gm, function ($1, $2, $3) {
            if (!eval($2)) {
                return '';
            }

            return $3;
        });

        // Showing container when data is loaded.
        document.querySelector('.alarms-container').classList.add('show-container');

        // Return the new html
        return template;
    };

    /**
     * Store templates in _templates array and removes it from the DOM
     * @return {void}
     */
    Template.prototype.getTemplates = function() {
        let templateElements = document.querySelectorAll('[data-template]');

        for (let i = 0; i < templateElements.length; i++) {
            let el = templateElements[i];
            let key = el.getAttribute('data-template');
            let html = el.outerHTML.replace(/data-template="([^\"]*)"/ig, '');

            _templates[key] = html;
            el.parentElement.removeChild(el);
        }
    };

    return new Template();

})();
