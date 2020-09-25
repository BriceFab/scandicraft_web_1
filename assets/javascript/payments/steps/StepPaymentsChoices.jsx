import React, {Component} from 'react';

class StepPaymentsChoices extends Component {
    constructor(props) {
        super(props);

        this.load_next_step = this.props.load_next_step.bind(this);
        this.set_step_data = this.props.set_step_data.bind(this);
    }

    render() {
        const {component_data} = this.props;
        const {payment_types} = component_data;

        return (
            <div>
                <h4 className={'step_payment_title'}>Choissisez votre moyen de paiement</h4>
                <div className={"step_payments_choices_container"}>
                    {payment_types && payment_types.length === 0 && <p style={{padding: 10}}>
                        Aucun moyen de paiement n'est actuellement disponible.
                    </p>}
                    {payment_types && payment_types.length > 0 && payment_types.map((payment_type, index) => {
                        const {name, help_text} = payment_type;

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
                                        {help_text}
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