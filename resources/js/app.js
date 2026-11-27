import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import { i18n } from './i18n';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import { useLanguageStore } from './Stores/languageStore';

const appName = import.meta.env.VITE_APP_NAME || 'Molofy';

const pages = import.meta.glob([
    './Pages/**/*.vue',
    './Modules/**/Pages/**/*.vue',
]);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    resolve: (name) => {
        const candidates = [
            `./Pages/${name}.vue`,
            `./Modules/${name}.vue`,
            `./${name}.vue`,
        ];

        if (name.includes('/')) {
            const [module, ...rest] = name.split('/');
            candidates.push(`./Modules/${module}/Pages/${rest.join('/')}.vue`);
        }

        const path = candidates.find((p) => pages[p]);

        if (!path) {
            throw new Error(`Page not found: ${name}`);
        }

        return resolvePageComponent(path, pages);
    },

    setup({ el, App, props, plugin }) {
        const pinia = createPinia();

        const app = createApp({
            render: () => h(App, props),
        });

        app.use(plugin);
        app.use(ZiggyVue);
        app.use(pinia);
        app.use(i18n);

        useLanguageStore(pinia).initializeLanguage();

        app.mount(el);
    },

    progress: {
        color: "#2563eb",
    },
});
