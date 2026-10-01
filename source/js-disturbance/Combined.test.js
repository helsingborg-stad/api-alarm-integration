describe('disturbance interactions', () => {
	let handlersAdded = false;
	const settings = {
		apiUrl: 'https://example.test/',
		places: ['1', '2'],
		inited: false,
		more_info: 'Show more information',
		less_info: 'Show less information',
		output_small_active: true,
		output_big_active: true,
		output_small: '#small-output',
		output_big: '#big-output',
	};

	beforeAll(() => {
		global.disturbances = { ...settings };
		global.fetch = jest.fn();
		require('./Combined');
	});

	beforeEach(() => {
		document.body.innerHTML = '<div id="small-output"></div><div id="big-output"></div>';
		global.disturbances = { ...settings, inited: handlersAdded };
		global.fetch = jest.fn();
	});

	it('fetches and renders notices without jQuery, and supports toggling', async () => {
		global.fetch.mockResolvedValue({
			ok: true,
			json: async () => ({
				small: [{ ID: 1, post_title: 'Small notice', post_content: '<p>Details</p>' }],
				big: [{ ID: 2, post_title: 'Big notice', post_content: '<p>Details</p>' }],
			}),
		});

		window.dispatchEvent(new Event('load'));
		await new Promise((resolve) => setTimeout(resolve, 0));
		handlersAdded = true;

		expect(global.fetch).toHaveBeenCalledWith(
			'https://example.test/wp/v2/disturbances?place=1%2C2',
			{ cache: 'no-store' },
		);
		expect(document.querySelector('#disturbance-1 strong').textContent).toBe('Small notice');
		expect(document.querySelector('#disturbance-2')).not.toBeNull();

		const button = document.querySelector('#disturbance-1 button');
		const content = document.querySelector('#disturbance-1 .notice-content');
		button.click();
		expect(content.classList.contains('open')).toBe(true);
		expect(content.style.display).toBe('block');
		expect(button.textContent).toBe(settings.less_info);

		button.click();
		expect(content.classList.contains('open')).toBe(false);
		expect(content.style.display).toBe('none');
		expect(button.textContent).toBe(settings.more_info);
	});

	it('does not duplicate manually placed notices', async () => {
		document.querySelector('#small-output').innerHTML =
			'<div id="disturbance-1" class="notice-disturbance"><button data-action="toggle-notice-content">Show more information</button><div class="notice-content" style="display:none"></div></div>';
		global.fetch.mockResolvedValue({
			ok: true,
			json: async () => ({
				small: [{ ID: 1, post_title: 'Existing notice', post_content: 'Details' }],
				big: [],
			}),
		});

		window.dispatchEvent(new Event('load'));
		await new Promise((resolve) => setTimeout(resolve, 0));

		expect(document.querySelectorAll('#small-output #disturbance-1')).toHaveLength(1);
		const button = document.querySelector('#small-output button');
		button.click();
		expect(document.querySelector('#small-output .notice-content').classList.contains('open')).toBe(true);
	});

	it('logs a request failure when the API returns an error', async () => {
		const log = jest.spyOn(console, 'log').mockImplementation();
		global.fetch.mockResolvedValue({ ok: false });

		window.dispatchEvent(new Event('load'));
		await new Promise((resolve) => setTimeout(resolve, 0));

		expect(log).toHaveBeenCalledWith('API Alarm Integration plugin: Request failed!');
		log.mockRestore();
	});
});
