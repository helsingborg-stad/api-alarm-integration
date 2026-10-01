import { HttpService } from './http-service';

describe('HttpService', () => {
	it('uses wp-api-fetch to request a URL with query parameters', async () => {
		const apiFetch = jest.fn().mockResolvedValue({ data: 'response' });
		Object.assign(window, { wp: { apiFetch } });

		await expect(HttpService().fetch('https://example.test/disturbances', { place: '1,2' })).resolves.toEqual({
			data: 'response',
		});
		expect(apiFetch).toHaveBeenCalledWith({
			url: 'https://example.test/disturbances?place=1%2C2',
		});
	});

	it('propagates wp-api-fetch request failures', async () => {
		const error = new Error('Request failed');
		const apiFetch = jest.fn().mockRejectedValue(error);
		Object.assign(window, { wp: { apiFetch } });

		await expect(HttpService().fetch('https://example.test/disturbances')).rejects.toBe(error);
	});
});
