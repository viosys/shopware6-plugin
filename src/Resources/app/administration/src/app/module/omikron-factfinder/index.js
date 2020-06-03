import './page/omikron-factfinder';
import './extension/sw-plugin';
import './component/omikron-factfinder-main-settings';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

const { Module } = Shopware;

Module.register('omikron-factfinder', {
    type: 'plugin',
    name: 'OmikronFactfinder',
    title: 'omikron-factfinder.general.mainMenuItemGeneral',
    description: 'omikron-factfinder.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
    color: '#9AA8B5',
    icon: 'default-action-settings',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            component: 'omikron-factfinder',
            path: 'index',
            meta: {
                parentPath: 'sw.settings.index'
            }
        }
    }
});
