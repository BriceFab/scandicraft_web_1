import React, {PureComponent} from 'react';
import {toast, ToastContainer} from "react-toastify";
import 'react-toastify/dist/ReactToastify.css';

toast.configure({
    autoClose: 8000,
    draggable: false,
    enableMultiContainer: false,
});

class NotificationContainer extends PureComponent {
    componentDidMount() {
        const {notifications} = this.props;
        const notices = notifications.notice;
        const errors = notifications.error;

        this.displayNotifications(notices, toast.TYPE.INFO);
        this.displayNotifications(errors, toast.TYPE.ERROR);
    }

    displayNotifications(notifications, type) {
        if (notifications && notifications.length > 0) {
            notifications.forEach(notification => {
                const toast_type = toast[type];
                if (toast_type) {
                    toast_type(notification);
                } else {
                    console.warn(`notifications: toast ${type} not defined`);
                }
            });
        }
    }

    render() {
        return (
            <ToastContainer/>
        );
    }
}

export default NotificationContainer;