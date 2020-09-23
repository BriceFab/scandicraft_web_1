import React, {PureComponent} from 'react';
import {CONFIG} from "../../_common/config";
import {Elements} from '@stripe/react-stripe-js';
import {loadStripe} from '@stripe/stripe-js';
import CheckoutForm from "./_stripe/CheckoutForm";

const stripePromise = loadStripe(CONFIG.PAYMENTS.STRIPE.PUBLIC_KEY);

class PaymentStripe extends PureComponent {
    render() {
        const public_key = CONFIG.PAYMENTS.STRIPE.PUBLIC_KEY;

        if (!public_key || !stripePromise) {
            console.warn('PaymentStripe missing public_key');
            return null;
        }

        return (
            <Elements stripe={stripePromise}>
                <CheckoutForm/>
            </Elements>
        );
    }
}

export default PaymentStripe;