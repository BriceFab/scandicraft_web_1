import PaymentPaypal from "../payments/types/PaymentPaypal";
import PaymentDedipass from "../payments/types/PaymentDedipass";
import CrediterSteps from "../payments/CrediterSteps";

export const DYNAMIC_COMPONENTS = {
    'payment_paypal': PaymentPaypal,
    'payment_dedipass': PaymentDedipass,
    'crediter_steps': CrediterSteps,
};