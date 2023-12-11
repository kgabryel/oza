import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import pl from 'vuetify/src/locale/pl';

Vue.use(Vuetify);

const opts = {
    theme: {
        themes: {
            light: {
                primary: '#1565c0',
                secondary: '#64b5f6',
                accent: '#78002e',
                error: '#d50000'
            },
            dark: {
                primary: '#1565c0',
                secondary: '#64b5f6',
                accent: '#78002e',
                error: '#d50000'
            }
        }
    },
    lang: {
        locales: {pl},
        current: 'pl'
    }
};

export default new Vuetify(opts);