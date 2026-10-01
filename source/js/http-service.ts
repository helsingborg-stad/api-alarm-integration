import type { HttpOperations } from './types';

export const HttpService = (): HttpOperations => {
	return {
		fetch: async <T>(url: string, params?: Record<string, any>): Promise<T> => {
			const apiFetch = (window as unknown as {
				wp: { apiFetch: <T>(options: { url: string }) => Promise<T> };
			}).wp.apiFetch;

			return apiFetch<T>({ url: `${url}?${new URLSearchParams(params)}` });
		},
	};
};
