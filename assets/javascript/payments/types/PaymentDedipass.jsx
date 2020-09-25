import React, {PureComponent} from 'react';
import {injectHeadScript} from "../../_common/utils/scriptUtils";

class PaymentDedipass extends PureComponent {
    componentDidMount() {
        injectHeadScript('//api.dedipass.com/v1/pay.js');
    }

    render() {
        const public_key = this.props.public_key?.dedipass;

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