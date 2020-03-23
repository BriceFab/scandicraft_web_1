import React from 'react';
import ReactDOM from 'react-dom';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

toast.configure({
  autoClose: 8000,
  draggable: false,
});

class MainApp extends React.Component {
  render() {
    return (<>
      <ToastContainer />
    </>)
  }
}

ReactDOM.render(<MainApp />, document.getElementById('main_app'));

// Export function for call/use in twig
// window.test = function () {
//   console.log('test');
// };
window.toast = toast;