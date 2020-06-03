import OmikronFactfinderTestConnectionService from '../core/service/api/omikron-factfinder-test-connection.service';

const {Application} = Shopware;

Application.addServiceProvider('OmikronFactfinderTestConnectionService', (container) => {
    const initContainer = Application.getContainer('init');

    return new OmikronFactfinderTestConnectionService(initContainer.httpClient, container.loginService);
});
