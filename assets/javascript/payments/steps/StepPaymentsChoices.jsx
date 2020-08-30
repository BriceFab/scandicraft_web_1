import React, {Component} from 'react';
import {STEPS_CONFIG} from "./steps_config";

class StepPaymentsChoices extends Component {
    render() {
        return (
            <div>
                <h4>Choissisez votre moyen de paiement</h4>
                <div className={"step_payments_choices_container"}>
                    {STEPS_CONFIG.PAYMENTS_TYPES.map((payment_type, index) => {
                        const {name, help} = payment_type;

                        return (
                            <div key={`steps-payment-choice-${name}-${index}`} className={"step_payments_choice_box"}>
                                <div className={"step_payments_choice_text_container"}>
                                    <div className={"step_payments_choice_text_name"}>
                                        {name}
                                    </div>
                                    <div className={"step_payments_choice_text_help"}>
                                        {help}
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        );
    }
}

export default StepPaymentsChoices;