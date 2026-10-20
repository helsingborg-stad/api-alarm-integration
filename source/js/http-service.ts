import type { HttpOperations } from './types';

export const HttpService = (): HttpOperations => {
	return {
		fetch: async <T>(url: string, params?: Record<string, any>): Promise<T> => {
			const response = await window.fetch(`${url}?${new URLSearchParams(params)}`);

			if (!response.ok) {
				throw new Error(`HTTP request failed with status ${response.status}`);
			}

			return response.json();
		},
	};
};
