export const CONFIG = {
    SKIN: {
        EXTENSION_FORMATS: ["image/png"],
    },
    PAYMENTS: {
        PAYPAL: {
            CLIENT_ID: null,    //récupéré depuis .env et transmis à l'html
            CURRENCY: 'EUR',
            INTENT: 'capture',
            DISABLE_FUNDING: 'card',
        },
        DEDIPASS: {
            PUBLIC_KEY: null, //récupéré depuis .env et transmis à l'html
        },
        STRIPE: {
            PUBLIC_KEY: null, //récupéré depuis .env et transmis à l'html
        },
    },
}