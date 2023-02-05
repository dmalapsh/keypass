window.Vue = require('vue');

const files = require.context('./components/global', true, /\.vue$/i);
let Components = {};
files.keys().map(key => {
    let name = key.split('/').pop().split('.')[0];
    Components[name] = Vue.component(name, files(key).default);
})


export const nativeroutes = [
    {
        path: '/',
        redirect: '/app/clients'
    },
    {
        path: '/login',
        name: 'login',
        component: Components.Login,
    },
    {
        path: '/app',
        redirect: '/app/clients',
        component: Components.MainPage,
        children: [
            {
                path: 'clients',
                component: Components.Clients,
            },
            {
                path: 'clients/:id',
                component: Components.ClientAction,
            },
            {
                path: 'client/:clientId/action/:action',
                component: Components.ClientAction,
            },
            {
                path: 'client/:clientId/action/:action',
                component: Components.ClientAction,
            },
            {
                path: 'client/:clientId/access',
                component: Components.Access,
            },
            {
                path: 'client/:clientId/access/action/:action',
                component: Components.AccessAction,
            },
            {
                path: 'client/:clientId/access/:accessId/action/:action',
                component: Components.AccessAction,
            },
            {
                path: 'template',
                component: Components.Template,
            },
            {
                path: 'template/action/:action',
                component: Components.TemplateAction,
            },
            {
                path: 'template/:templateId/action/:action',
                component: Components.TemplateAction,
            },
        ]
    },
    {
        path: '/allelem',
        name: 'allelem',
        component: Components.AllElem,
    },
];
