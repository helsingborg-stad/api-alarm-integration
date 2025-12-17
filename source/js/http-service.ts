import type { HttpOperations } from './types';

export const HttpService = (): HttpOperations => {
	return {
		fetch: async <T>(url: string, params?: Record<string, any>): Promise<T> => {
			return fetch(`${url}?${new URLSearchParams(params)}`, {
				cache: 'no-cache',
				redirect: 'follow',
				referrerPolicy: 'no-referrer',
			}).then((response) => {
				if (response.status !== 200) {
					throw new Error(`Error: ${response.status}`);
				}
				return response.json() as Promise<T>;
			});
		},
	};
};
