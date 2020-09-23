import StepPaymentsChoices from "./StepPaymentsChoices";
import StepOffres from "./StepOffres";

export const STEPS_CONFIG = {
    MIN_STEP: 0,
    MAX_STEP: 5,
    DYNAMIC_STEPS: {
        0: {
            name: 'Moyen de paiement',
            component: StepPaymentsChoices,
        },
        1: {
            name: 'Offres',
            component: StepOffres,
        },
    },
    PAYMENTS_TYPES: [
        {
            name: 'Stripe',
            help: 'Paiement par Cartes',
            dynamic_key: 'payment_stripe',
        },
        {
            name: 'Dédipass',
            help: 'Paiement par SMS et Téléphone',
            dynamic_key: 'payment_dedipass',
        },
        {
            name: 'PayPal',
            help: 'Paiement avec votre compte PayPal',
            dynamic_key: 'payment_paypal',
        },
        // {
        //     name: 'PaySafeCard',
        //     help: 'Paiement par code PaySafeCard',
        // },
    ]
};