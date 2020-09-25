import React, {PureComponent} from 'react';
import {Elements} from '@stripe/react-stripe-js';
import {loadStripe} from '@stripe/stripe-js';
import CheckoutForm from "./_stripe/CheckoutForm";

class PaymentStripe extends PureComponent {
    constructor(props) {
        super(props);

        this.stripePromise = loadStripe(props.public_key?.stripe);
    }

    render() {
        const public_key = this.props.public_key?.stripe;

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