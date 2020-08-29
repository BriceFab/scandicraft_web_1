import React from "react";
import {render, unmountComponentAtNode} from 'react-dom';
import NotificationContainer from "./NotificationContainer";

class HTMLNotificationsContainer extends HTMLElement {
    connectedCallback() {
        const {notifications} = this.dataset;

        render(<NotificationContainer notifications={JSON.parse(notifications)}/>, this);
    }

    disconnectedCallback() {
        unmountComponentAtNode(this);
    }
}

customElements.define('notifications-container', HTMLNotificationsContainer);