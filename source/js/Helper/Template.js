import ObjectByString from './Object';

export default (() => {
	const _templates = [];

	function Template() {
		this.getTemplates();
	}

	Template.prototype.render = (key, data) => {
		// Get template html
		let template = _templates[key];

		// Replace template strings
		template = template.replace(/{#\s*([\w.[\]]+)\s*#}/g, ($1, $2) => Object.byString(data, $2));

		// Handle if-statements
		template = template.replace(/{##\s*if(.*)\s*##}([\s\S]*?){##\s*endif\s*##}/gm, ($1, $2, $3) => {
			if (!eval($2)) {
				return '';
			}

			return $3;
		});

		// Return the new html
		return template;
	};

	/**
	 * Store templates in _templates array and removes it from the DOM
	 * @return {void}
	 */
	Template.prototype.getTemplates = () => {
		const templateElements = document.querySelectorAll('[data-template]');

		for (let i = 0; i < templateElements.length; i++) {
			const el = templateElements[i];
			const key = el.getAttribute('data-template');
			const html = el.outerHTML.replace(/data-template="([^"]*)"/gi, '');

			_templates[key] = html;
			el.parentElement.removeChild(el);
		}
	};

	return new Template();
})();
