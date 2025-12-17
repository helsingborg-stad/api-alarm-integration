import type { TransformOperations } from './types';

export const TransformService = (): TransformOperations => ({
	map: (data: any) => ({
		disturbances: {
			small: data?.small || data?.disturbances?.small || [],
			big: data?.big || data?.disturbances?.big || [],
		},
		firedangerlevel: {
			dateTimeChanged: data?.firedangerlevel?.dateTimeChanged || '',
			places: data?.firedangerlevel?.places || [],
		},
	}),
});
