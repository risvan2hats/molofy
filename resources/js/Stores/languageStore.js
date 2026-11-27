import { defineStore } from 'pinia';
import { i18n } from '../i18n';

const supportedLanguages = ['de', 'en', 'it'];

export const useLanguageStore = defineStore('language', {
    state: () => ({
        locale: localStorage.getItem('locale') || 'de',
    }),

    actions: {
        setLanguage(locale) {
            if (!supportedLanguages.includes(locale)) {
                return;
            }

            this.locale = locale;

            i18n.global.locale.value = locale;

            localStorage.setItem('locale', locale);
        },

        initializeLanguage() {
            this.setLanguage(this.locale);
        },
    },
});