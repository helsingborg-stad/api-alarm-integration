import type { FiredangerPlace, TemplateOperations, WPPost } from './types';

export const TemplateService = (): TemplateOperations => {
	const replace = (content: string, title: string, text: string) =>
		content.replace('{DISTURBANCE_TITLE}', title ?? '').replace('{DISTURBANCE_TEXT}', text ?? '');

	return {
		getFirelevelTemplate: (content: string, item: FiredangerPlace) => replace(content, item.place, ''),
		getBigTemplate: (content: string, item: WPPost) => replace(content, item.post_title, item.post_content),
		getSmallTemplate: (content: string, item: WPPost) => replace(content, item.post_title, item.post_content),
	};
};
