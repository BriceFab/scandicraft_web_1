import React, {PureComponent} from 'react';
import {injectHeadScript} from "../../_common/utils/injectHeadScript";
import {CONFIG} from "../../_common/config";

class PaymentPaypal extends PureComponent {
    componentDidMount() {
        const {CLIENT_ID, CURRENCY, INTENT, DISABLE_FUNDING} = CONFIG.PAYMENTS.PAYPAL;

        injectHeadScript(`https://www.paypal.com/sdk/js?client-id=${CLIENT_ID}&currency=${CURRENCY}&intent=${INTENT}&disable-funding=${DISABLE_FUNDING}`, () => {
            paypal.Buttons({
                createOrder: (data, actions) => {
                    // This function sets up the details of the transaction, including the amount and line item details.
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '100.01'
                            }
                        }]
                    });
                },
                onApprove: (data, actions) => {
                    // This function captures the funds from the transaction.
                    return actions.order.capture().then(function (details) {
                        // This function shows a transaction success message to your buyer.
                        alert('Transaction completed by ' + details.payer.name.given_name);
                    });
                }
            }).render('#paypal_payment_id');
        });
    }

    render() {
        return (
            <div>
                <div id={'paypal_payment_id'}/>
                paiement paypal
            </div>
        );
    }
}

export default PaymentPaypal;