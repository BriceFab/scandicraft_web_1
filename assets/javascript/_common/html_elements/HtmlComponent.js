import React from "react";
import {render, unmountComponentAtNode} from 'react-dom';
import {DYNAMIC_COMPONENTS} from "../dynamic_components";

const DYNAMIC_COMPONENT_ATTRIBUTE = 'type';

//Create custom html component
class HtmlComponent extends HTMLElement {
    constructor() {
        super();

        this.component_found = false;
    }

    connectedCallback() {
        const type = this.getAttribute(DYNAMIC_COMPONENT_ATTRIBUTE);

        this.component_found = type && DYNAMIC_COMPONENTS.hasOwnProperty(type);
        if (this.component_found) {
            this.renderComponent();
        } else {
            console.warn(`Component ${type} is undefined..`);
        }
    }

    renderComponent() {
        const type = this.getAttribute(DYNAMIC_COMPONENT_ATTRIBUTE);
        const DynamicReactComponent = DYNAMIC_COMPONENTS[type];
        const {data} = this.dataset;

        render(<DynamicReactComponent
            element_ref={this}
            type={type}
            data={data}
        />, this);
    }

    disconnectedCallback() {
        if (this.component_found) {
            unmountComponentAtNode(this);
        }
    }
}

//Register custom html component
customElements.define('html-component', HtmlComponent);