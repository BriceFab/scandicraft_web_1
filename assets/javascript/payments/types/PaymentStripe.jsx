import React, {PureComponent} from 'react';
import {CONFIG} from "../../_common/config";
import {Elements} from '@stripe/react-stripe-js';
import {loadStripe} from '@stripe/stripe-js';
import CheckoutForm from "./_stripe/CheckoutForm";

class PaymentStripe extends PureComponent {
    constructor(props) {
        super(props);

        this.stripePromise = loadStripe(CONFIG.PAYMENTS.STRIPE.PUBLIC_KEY);
    }

    render() {
        const public_key = CONFIG.PAYMENTS.STRIPE.PUBLIC_KEY;

        if (!public_key || !this.stripePromise) {
            console.warn('PaymentStripe missing public_key');
            return null;
        }

        return (
            <Elements stripe={this.stripePromise}>
                <CheckoutForm/>
            </Elements>
        );
    }
}

export default PaymentStripe;