import template from './omikron-factfinder-main-settings.html.twig';

const {Component, Mixin} = Shopware;

Component.register('omikron-factfinder-main-settings', {
    template,
    name: 'OmikronFactFinderMainSettings',
    inject: [
        'OmikronFactfinderTestConnectionService',
    ],
    mixins: [
        Mixin.getByName('notification'),
    ],

    props: {
        actualConfigData: {
            type: Object,
            required: true,
        },
        allConfigs: {
            type: Object,
            required: true,
        },
        selectedSalesChannelId: {
            required: true,
        },
        isLoading: {
            type: Boolean,
            required: true,
        },
    },

    computed: {
        testButtonDisabled() {
            return !(this.actualConfigData['OmikronFactFinder.config.serverUrl'] &&
                this.actualConfigData['OmikronFactFinder.config.channel'] &&
                this.actualConfigData['OmikronFactFinder.config.user'] &&
                this.actualConfigData['OmikronFactFinder.config.password']);
        },

        versions() {
            return [
                {
                    id: '7.3',
                    name: '7.3',
                },
                {
                    id: 'NG',
                    name: 'NG',
                },
            ];
        },
    },

    methods: {
        onTestConnection() {
            this.isLoading = true;
            return this.OmikronFactfinderTestConnectionService.testConnection(
                this.actualConfigData['OmikronFactFinder.config.serverUrl'],
                this.actualConfigData['OmikronFactFinder.config.channel'],
                this.actualConfigData['OmikronFactFinder.config.user'],
                this.actualConfigData['OmikronFactFinder.config.password'],
            ).then((response) => {
                const connectionEstablished = response.connectionEstablished;

                if (connectionEstablished) {
                    this.isLoading = false;
                    this.createNotificationSuccess({
                        title: this.$tc('global.default.success'),
                        message: this.$tc('global.default.success'),
                    });
                } else {
                    this.createNotificationError({
                        title: this.$tc('global.default.error'),
                        message: response.error,
                    });
                }
            });
        },
    },
});
