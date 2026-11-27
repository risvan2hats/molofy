import { createI18n } from 'vue-i18n';

import de from './locales/de.json';
import en from './locales/en.json';
import it from './locales/it.json';

export const i18n = createI18n({
    legacy: false,

    locale: 'de',

    fallbackLocale: 'en',

    messages: {
        de,
        en,
        it,
    },
});