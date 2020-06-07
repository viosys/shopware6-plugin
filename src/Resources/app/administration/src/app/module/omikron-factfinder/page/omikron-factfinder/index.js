import template from './omikron-factfinder.html.twig';
import './omikron-factfinder.scss';

const {Component, Mixin} = Shopware;

Component.register('omikron-factfinder', {
    template,

    mixins: [
        Mixin.getByName('notification'),
    ],

    data() {
        return {
            isLoading: false,
            isSaveSuccessful: false,
            config: null,
        };
    },
    methods: {
        onSave() {
            this.isLoading = true;

            this.$refs.configComponent.save().then((res) => {
                this.isLoading = false;
                this.isSaveSuccessful = true;

                if (res) {
                    this.config = res;
                }

                this.createNotificationSuccess({
                    title: this.$tc('global.default.success'),
                    message: this.$tc('global.default.success'),
                });

            }).catch(() => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('global.default.error'),
                    message: this.$tc('global.default.error'),
                });
            });
        },
    },
});
