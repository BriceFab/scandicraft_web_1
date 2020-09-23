import PaymentPaypal from "../../payments/types/PaymentPaypal";
import PaymentDedipass from "../../payments/types/PaymentDedipass";
import CrediterSteps from "../../payments/CrediterSteps";
import PaymentStripe from "../../payments/types/PaymentStripe";

export const DYNAMIC_COMPONENTS = {
    'payment_paypal': PaymentPaypal,
    'payment_dedipass': PaymentDedipass,
    'payment_stripe': PaymentStripe,
    'crediter_steps': CrediterSteps,
};