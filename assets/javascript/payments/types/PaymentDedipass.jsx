import React, {PureComponent} from 'react';
import {injectHeadScript} from "../../_common/utils/injectHeadScript";
import {CONFIG} from "../../_common/config";

class PaymentDedipass extends PureComponent {
    componentDidMount() {
        injectHeadScript('//api.dedipass.com/v1/pay.js');
    }

    render() {
        const public_key = CONFIG.PAYMENTS.DEDIPASS.PUBLIC_KEY;

        if (!public_key) {
            console.warn('PaymentDedipass missing public_key');
            return null;
        }

        return (
            <div data-dedipass={public_key} data-dedipass-custom=""/>
        );
    }
}

export default PaymentDedipass;