import React, {PureComponent} from 'react';

class UnavailableComponent extends PureComponent {
    render() {
        const {type} = this.props;

        return (
            <div>
                Désolé cet élément n'est pas encore disponible ({type}).
            </div>
        );
    }
}

export default UnavailableComponent;