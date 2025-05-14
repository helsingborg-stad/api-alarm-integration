export interface DisturbanceSettings {
    htmlSmall: string;
    htmlBig: string;
    htmlFirelevel: string;
    inited: boolean;
    apiUrl: string;
    places: Array<string>;
    more_info: string;
    less_info: string;
    output_small_active: boolean;
    output_big_active: boolean;
    output_firedangerlevel_active: boolean;
    output_small: string;
    output_big: string;
    output_firelevel: string;
}

export interface Services {
    http: HttpOperations;
    template: TemplateOperations;
    transform: TransformOperations;
}
export interface HttpOperations {
    fetch: <T>(url: string, params?: Record<string, any> ) => Promise<T>;
}
export interface TemplateOperations {
    getFirelevelTemplate: (content: string, item: FiredangerPlace) => string;
    getBigTemplate: (content: string, item: WPPost) => string;
    getSmallTemplate: (content: string, item: WPPost) => string;
}
export interface TransformOperations {
    map: (data: any) => ApiResponse;
}
export interface ApiResponse {
    disturbances: {
        small: Array<WPPost>;
        big: Array<WPPost>;
    },
    firedangerlevel: FiredangerLevels;
}


export interface WPPost {
    ID: string
    post_title: string;
    post_content: string;
}
export interface FiredangerPlace {
    place: string;
    level: string;
}
export interface FiredangerLevels {
    dateTimeChanged: string
    places: Array<FiredangerPlace>
}


