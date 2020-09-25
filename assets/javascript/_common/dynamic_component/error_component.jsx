import React, {PureComponent} from 'react';

class ErrorComponent extends PureComponent {
    render() {
        const {type, message} = this.props;

        return (
            <div style={{color: 'red'}}>
                Désolé ce composant {type} contient des erreurs.
                {message}
            </div>
        );
    }
}

export default ErrorComponent;