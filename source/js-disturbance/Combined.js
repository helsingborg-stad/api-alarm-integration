window.addEventListener('load', () => {
	const requestUrl = `${disturbances.apiUrl}wp/v2/disturbances`;
	const params = new URLSearchParams();
	const places = Array.isArray(disturbances.places) ? disturbances.places : [];

	if (places.length > 0) {
		params.set('place', places.join(','));
	}

	if (!disturbances.inited) {
		document.addEventListener('click', (event) => {
			const button = event.target.closest('.notice-disturbance [data-action="toggle-notice-content"]');
			if (!button) {
				return;
			}

			const notice = button.closest('.notice-disturbance');
			const content = notice.querySelector('.notice-content');
			const isOpen = !content.classList.contains('open');
			content.classList.toggle('open', isOpen);
			button.textContent = isOpen ? disturbances.less_info : disturbances.more_info;
			animateVisibility(content, isOpen);
		});

		disturbances.inited = true;
	}

	const query = params.toString();
	fetch(`${requestUrl}${query ? `?${query}` : ''}`, { cache: 'no-store' })
		.then((response) => {
			if (!response.ok) {
				throw new Error('Request failed');
			}
			return response.json();
		})
		.then((response) => {
			if (disturbances.output_small_active) {
				(response.small || []).forEach((item) => {
					renderNotice(item, 'info', 'notice-fullwidth', disturbances.output_small);
				});
			}

			if (disturbances.output_big_active) {
				(response.big || []).forEach((item) => {
					renderNotice(item, 'warning', 'notice-lg notice-fullwidth', disturbances.output_big);
				});
			}
		})
		.catch(() => {
			console.log('API Alarm Integration plugin: Request failed!');
		});
});

function renderNotice(item, type, size, selector) {
	if (document.getElementById(`disturbance-${item.ID}`)) {
		return;
	}

	const notice = document.createElement('div');
	notice.className = `notice notice-disturbance ${size} ${type}`;
	notice.id = `disturbance-${item.ID}`;
	notice.style.display = 'none';

	const container = document.createElement('div');
	container.className = 'container';

	const row = document.createElement('div');
	row.className = 'grid grid-table';

	const titleCell = document.createElement('div');
	titleCell.className = 'grid-auto';

	const icon = document.createElement('i');
	icon.className = type === 'info' ? 'pricon pricon-notice-info' : 'pricon pricon-notice-warning';

	const title = document.createElement('strong');
	title.textContent = item.post_title;
	titleCell.append(icon, document.createTextNode(' '), title);

	const buttonCell = document.createElement('div');
	buttonCell.className = 'grid-fit-content';

	const button = document.createElement('button');
	button.type = 'button';
	button.className = 'btn btn-sm btn-contrasted';
	button.dataset.action = 'toggle-notice-content';
	button.textContent = disturbances.more_info;
	buttonCell.append(button);
	row.append(titleCell, buttonCell);

	const content = document.createElement('div');
	content.className = 'grid notice-content';
	content.style.display = 'none';

	const contentCell = document.createElement('div');
	contentCell.className = 'grid-md-12';
	contentCell.innerHTML = item.post_content;
	content.append(contentCell);

	container.append(row, content);
	notice.append(container);
	document.querySelector(selector)?.prepend(notice);
	animateVisibility(notice, true);
}

function animateVisibility(element, visible) {
	element.getAnimations?.().forEach((animation) => {
		animation.cancel();
	});

	const wasHidden = window.getComputedStyle(element).display === 'none';
	if (visible) {
		element.style.display = 'block';
	}

	const fromHeight = wasHidden ? 0 : element.getBoundingClientRect().height;
	const toHeight = visible ? element.scrollHeight : 0;

	if (typeof element.animate !== 'function') {
		element.style.display = visible ? 'block' : 'none';
		return;
	}

	element.style.overflow = 'hidden';
	const animation = element.animate(
		[
			{ height: `${fromHeight}px`, opacity: visible ? 0 : 1 },
			{ height: `${toHeight}px`, opacity: visible ? 1 : 0 },
		],
		{ duration: 400, easing: 'ease' },
	);
	animation.onfinish = () => {
		element.style.display = visible ? 'block' : 'none';
		element.style.removeProperty('height');
		element.style.removeProperty('overflow');
	};
}
