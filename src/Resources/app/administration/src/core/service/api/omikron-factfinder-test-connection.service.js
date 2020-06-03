const ApiService = Shopware.Classes.ApiService;

    class OmikronFactfinderTestConnectionService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'factfinder') {
        super(httpClient, loginService, apiEndpoint);
    }

    testConnection(serverUrl, channel, user, password) {
        const headers = this.getBasicHeaders();

        return this.httpClient
            .get(
                `_action/${this.getApiBasePath()}/test-connection`,
                {
                    params: {serverUrl, channel, user, password},
                    headers: headers,
                },
            )
            .then((response) => {
                return ApiService.handleResponse(response);
            });
    }
}

export default OmikronFactfinderTestConnectionService;
