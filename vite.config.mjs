import { createViteConfig } from "vite-config-factory";

const entries = {
    "js/api-alarm-integration": "./source/js/api-alarm-integration.ts",
    "js/api-alarm-index": "./source/js/api-alarm-index.ts",
    "css/api-alarm-integration": "./source/sass/api-alarm-integration.scss",
};

export default createViteConfig(entries, {
	outDir: "assets/dist",
	manifestFile: "manifest.json",
});
