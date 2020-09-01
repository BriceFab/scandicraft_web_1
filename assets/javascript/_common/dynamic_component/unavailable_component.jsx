import React, {Component} from 'react';

class UnavailableComponent extends Component {
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