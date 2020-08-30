import React, {PureComponent} from 'react';
import {injectHeadScript} from "../_common/utils/injectHeadScript";

class PaymentDedipass extends PureComponent {
    componentDidMount() {
        injectHeadScript('//api.dedipass.com/v1/pay.js');
    }

    render() {
        const {data} = this.props;
        const {public_key} = data;

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