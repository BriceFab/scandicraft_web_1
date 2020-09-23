import React from 'react';
import {CardElement, ElementsConsumer} from '@stripe/react-stripe-js';
import axios from 'axios';

import CardSection from './CardSection';
import {toast} from "react-toastify";

class CheckoutForm extends React.Component {
    constructor(props) {
        super(props);

        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleServerResponse = this.handleServerResponse.bind(this);
        this.handleStripeJsResult = this.handleStripeJsResult.bind(this);
        this.stripePaymentMethodHandler = this.stripePaymentMethodHandler.bind(this);
    }

    handleSubmit = async (event) => {
        // We don't want to let default form submission happen here,
        // which would refresh the page.
        event.preventDefault();

        const {stripe, elements} = this.props

        if (!stripe || !elements) {
            // Stripe.js has not yet loaded.
            // Make  sure to disable form submission until Stripe.js has loaded.
            return;
        }

        const result = await stripe.createPaymentMethod({
            type: 'card',
            card: elements.getElement(CardElement),
            billing_details: {
                // Include any additional collected billing details.
                name: 'Jenny Rosen',
            },
        });

        this.stripePaymentMethodHandler(result);
    };

    handleServerResponse(response) {
        if (response.error) {
            // Show error from server on payment form
            console.log('handleServerResponse', response?.error)
            toast.error(response?.error);
        } else if (response.requires_action) {
            // Use Stripe.js to handle required card action
            const {stripe} = this.props

            stripe.handleCardAction(
                response.payment_intent_client_secret
            ).then(this.handleStripeJsResult);
        } else {
            // Show success message
            toast.success(`Merci pour votre achat. Vous avez été crédité de ${response?.amount_received} coins!`)
        }
    }

    handleStripeJsResult(result) {
        if (result.error) {
            // Show error in payment form
            console.log('handleStripeJsResult', result?.error)
            toast.error(result?.error?.message);
        } else {
            // The card action has been handled
            // The PaymentIntent can be confirmed again on the server
            axios.post('/stripe/pay', {
                payment_intent_id: result.paymentIntent.id
            }, {
                headers: {'Content-Type': 'application/json'},
            }).then(result => {
                this.handleServerResponse(result.data);
            })
        }
    }

    stripePaymentMethodHandler(result) {
        if (result.error) {
            // Show error in payment form
            console.log('stripePaymentMethodHandler', result?.error)
            toast.error(result?.error?.message);
        } else {
            // Otherwise send paymentMethod.id to your server (see Step 4)
            axios.post('/stripe/pay', {
                payment_method_id: result.paymentMethod.id,
            }, {
                headers: {'Content-Type': 'application/json'},
            }).then(result => {
                this.handleServerResponse(result.data);
            });
        }
    }

    render() {
        const {stripe} = this.props;

        return (
            <form onSubmit={this.handleSubmit}>
                <CardSection/>
                <button type="submit" disabled={!stripe}>
                    Submit Payment
                </button>
            </form>
        );
    }
}

export default function InjectedCheckoutForm() {
    return (
        <ElementsConsumer>
            {({stripe, elements}) => (
                <CheckoutForm stripe={stripe} elements={elements}/>
            )}
        </ElementsConsumer>
    );
}
