import React, {PureComponent} from 'react';

class LoadingComponent extends PureComponent {
    render() {
        return (
            <div className="spinner-border text-danger" role="status">
                <span className="sr-only">Chargement...</span>
            </div>
        );
    }
}

export default LoadingComponent;