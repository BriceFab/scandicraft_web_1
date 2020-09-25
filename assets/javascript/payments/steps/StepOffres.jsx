import React, {Component} from 'react';
import UnavailableComponent from "../../_common/dynamic_component/unavailable_component";
import DynamicComponent from "../../_common/dynamic_component/dynamic_component";
import {DYNAMIC_COMPONENTS} from "../../_common/dynamic_component/dynamic_components";

class StepOffres extends Component {
    render() {
        const {steps_data, current_step, component_data} = this.props;

        const current_payment = steps_data[current_step - 1]?.payment;
        const {dynamic_key} = current_payment;

        if (!dynamic_key) {
            return <UnavailableComponent type={`offre de paiement - ${current_payment?.name}`}/>
        }

        return (
            <div>
                <h3>Veuillez choisir votre offre</h3>
                <DynamicComponent
                    dynamic_list={DYNAMIC_COMPONENTS} dynamic_key={dynamic_key}
                    public_key={component_data?.public_key}
                />
            </div>
        );
    }
}

export default StepOffres;