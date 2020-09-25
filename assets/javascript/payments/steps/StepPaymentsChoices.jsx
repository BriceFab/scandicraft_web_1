import React, {Component} from 'react';
import {STEPS_CONFIG} from "./steps_config";

class StepPaymentsChoices extends Component {
    constructor(props) {
        super(props);

        this.load_next_step = this.props.load_next_step.bind(this);
        this.set_step_data = this.props.set_step_data.bind(this);


    }

    render() {
        return (
            <div>
                <h4 className={'step_payment_title'}>Choissisez votre moyen de paiement</h4>
                <div className={"step_payments_choices_container"}>
                    {STEPS_CONFIG.PAYMENTS_TYPES.map((payment_type, index) => {
                        const {name, help} = payment_type;

                        return (
                            <div key={`steps-payment-choice-${name}-${index}`} className={"step_payments_choice_box"}
                                 onClick={() => {
                                     this.set_step_data({
                                         payment: payment_type,
                                     }, () => {
                                         this.load_next_step();
                                     });
                                 }}>
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