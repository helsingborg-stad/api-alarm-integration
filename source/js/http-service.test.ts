import { HttpService } from './http-service';

describe('HttpService', () => {
	const originalFetchDescriptor = Object.getOwnPropertyDescriptor(window, 'fetch');

	afterEach(() => {
		if (originalFetchDescriptor) {
			Object.defineProperty(window, 'fetch', originalFetchDescriptor);
		} else {
			Reflect.deleteProperty(window, 'fetch');
		}
	});

	it('uses window.fetch to request a URL with query parameters and parses the response', async () => {
		const data = { data: 'response' };
		const json = jest.fn().mockResolvedValue(data);
		const fetch = jest.fn().mockResolvedValue({
			ok: true,
			json,
		} as unknown as Response);
		Object.assign(window, { fetch });

		await expect(HttpService().fetch('https://example.test/disturbances', { place: '1,2' })).resolves.toEqual({
			data: 'response',
		});
		expect(fetch).toHaveBeenCalledWith('https://example.test/disturbances?place=1%2C2');
		expect(json).toHaveBeenCalledTimes(1);
	});

	it('rejects unsuccessful HTTP responses', async () => {
		const fetch = jest.fn().mockResolvedValue({
			ok: false,
			status: 403,
		} as unknown as Response);
		Object.assign(window, { fetch });

		await expect(HttpService().fetch('https://example.test/disturbances')).rejects.toThrow(
			'HTTP request failed with status 403',
		);
	});

	it('propagates network request failures', async () => {
		const error = new Error('Request failed');
		const fetch = jest.fn().mockRejectedValue(error);
		Object.assign(window, { fetch });

		await expect(HttpService().fetch('https://example.test/disturbances')).rejects.toBe(error);
	});
});
