import React, {PureComponent} from 'react';
import UnavailableComponent from "./unavailable_component";

class DynamicComponent extends PureComponent {
    render() {
        const {dynamic_list, dynamic_key, ...props} = this.props;

        const DynamicStepComponent = this.findDynamicComponent();
        if (!DynamicStepComponent) {
            return <UnavailableComponent type={`dynamic component - ${dynamic_key}`}/>
        }

        return (
            <DynamicStepComponent
                name={dynamic_key}
                {...props}
            />
        );
    }

    findDynamicComponent() {
        const {dynamic_list, dynamic_key} = this.props;

        const dynamic_component = dynamic_list[dynamic_key];
        if (typeof dynamic_component === 'object') {
            return dynamic_component?.component;
        }
        return dynamic_component;
    }
}

export default DynamicComponent;