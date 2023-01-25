import UIkit from 'uikit';
import { Header, Sticky } from './header';
import { $$, isRtl, isVisible, ready, swap } from 'uikit-util';

UIkit.component('Header', Header);
UIkit.mixin(Sticky, 'sticky');

UIkit.mixin(
    {
        events: {
            beforescroll() {
                if (!this.$props.offset) {
                    for (const navbar of $$('[uk-sticky] [uk-navbar]')) {
                        if (isVisible(navbar)) {
                            this.offset = navbar.offsetHeight;
                        }
                    }
                }
            },
        },
    },
    'scroll'
);

if (isRtl) {
    const mixin = {
        created() {
            this.$props.pos = swap(this.$props.pos, 'left', 'right');
        },
    };

    UIkit.mixin(mixin, 'drop');
    UIkit.mixin(mixin, 'tooltip');
}

ready(() => {
    const { $load = [], $theme = {} } = window;

    function load(stack, config) {
        stack.length && stack.shift()(config, () => load(stack, config));
    }

    load($load, $theme);
});
