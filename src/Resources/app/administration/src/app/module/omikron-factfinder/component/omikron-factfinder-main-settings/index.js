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

    data() {
        return {
            isTesting: false,
        };
    },

    computed: {
        testButtonDisabled() {
            return false;
        },
    },

    methods: {
        checkTextFieldInheritance(value) {
            if (typeof value !== 'string') {
                return true;
            }

            return value.length <= 0;
        },

        checkBoolFieldInheritance(value) {
            return typeof value !== 'boolean';
        },

        onTestConnection() {
            this.isLoading = true;
            console.log(this.actualConfigData);
            return this.OmikronFactfinderTestConnectionService.testConnection(
                this.actualConfigData['OmikronFactFinder.settings.serverUrl'],
                this.actualConfigData['OmikronFactFinder.settings.channel'],
                this.actualConfigData['OmikronFactFinder.settings.user'],
                this.actualConfigData['OmikronFactFinder.settings.password'],
            ).then((response) => {
                const connectionEstablished = response.connectionEstablished;

                if (connectionEstablished) {
                    this.isLoading = false;
                    this.createNotificationSuccess({
                        title: this.$tc('global.default.success'),
                        message: this.$tc('global.default.success'),
                    });
                } else {
                    this.createNotificationSuccess({
                        title: this.$tc('global.default.success'),
                        message: '=(',
                    });
                }
            });
        },
    },
});
