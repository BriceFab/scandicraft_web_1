import PaymentPaypal from "../payments/PaymentPaypal";
import PaymentDedipass from "../payments/PaymentDedipass";

export const DYNAMIC_COMPONENTS = {
    'payment_paypal': PaymentPaypal,
    'payment_dedipass': PaymentDedipass,
};