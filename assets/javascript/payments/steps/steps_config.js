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
};