import './bootstrap';
import { createApp } from 'vue';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import router from './router'
import App from './App.vue';
import Sidebar from '../css/sidebar.css'
// import store from './store/index.js'


import * as ElementPlusIconsVue from '@element-plus/icons-vue'


const app = createApp(App)
            .use(router)
            .use(ElementPlus)
            .use(Sidebar);

for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(key, component)
}
app.mount('#app');
