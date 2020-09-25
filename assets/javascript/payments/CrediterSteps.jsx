import React, {Component} from 'react';
import clsx from 'clsx';
import {STEPS_CONFIG} from "./steps/steps_config";
import './styles/crediter_steps.module.scss';
import DynamicComponent from "../_common/dynamic_component/dynamic_component";
import ErrorComponent from "../_common/dynamic_component/error_component";
import LoadingComponent from "../_common/dynamic_component/loading_component";

class CrediterSteps extends Component {
    constructor(props) {
        super(props);

        this.state = {
            step: STEPS_CONFIG.MIN_STEP,
            steps_data: {},
            component_data: null,
        };

        this.nextStep = this.nextStep.bind(this);
        this.previousStep = this.previousStep.bind(this);
        this.changeStep = this.changeStep.bind(this);
        this.setStepData = this.setStepData.bind(this);
    }

    componentDidMount() {
        window.addEventListener('load', () => {
            const script_data = document.getElementById(this.props?.attributes?.component_data);
            if (script_data) {
                try {
                    const data = JSON.parse(script_data.innerText);
                    this.setState({
                        component_data: data,
                    });
                } catch (e) {
                    this.setState({
                        component_data: undefined,
                    });
                }
            } else {
                this.setState({
                    component_data: undefined,
                });
            }
        })
    }

    nextStep() {
        this.changeStep(this.state.step + 1);
    }

    previousStep() {
        this.changeStep(this.state.step - 1);
    }

    changeStep(step) {
        this.setState({
            step: step,
        });
    }

    setStepData(data, callback) {
        let next_steps_data = {...this.state.steps_data};
        next_steps_data[this.state.step] = data;

        this.setState({
            steps_data: next_steps_data,
        }, callback);
    }

    render() {
        const {step, steps_data, component_data} = this.state;

        if (component_data === undefined) return <ErrorComponent/>;
        if (!component_data) return <LoadingComponent/>;

        return (
            <div className={"container-fluid"}>
                <div className={"row"}>
                    <div className={"col-lg-3 col-md-4 col-xs-12"}>
                        {
                            Object.values(STEPS_CONFIG.DYNAMIC_STEPS).map((dynamic_step, index) => {
                                const {name} = dynamic_step;

                                return (
                                    <div
                                        key={`crediter-steps-${name}-${index}`}
                                        onClick={() => {
                                            if (step > index) {
                                                this.changeStep(index)
                                            }
                                        }}
                                        className={clsx('step_shower', index === step && 'step_shower_current', step < index && 'step_disabled')}
                                    >
                                        {name}
                                    </div>
                                );
                            })
                        }
                    </div>
                    <div className={"col-lg-9 col-md-8 col-xs-12"}>
                        <div>
                            <DynamicComponent
                                dynamic_list={STEPS_CONFIG.DYNAMIC_STEPS} dynamic_key={step}
                                current_step={step}
                                steps_data={steps_data}
                                load_next_step={this.nextStep}
                                set_step_data={this.setStepData}
                                component_data={component_data}
                            />
                        </div>
                    </div>
                </div>
                <div className={"row"}>
                    <div className={"col-6"}>
                        {step > STEPS_CONFIG.MIN_STEP && <button
                            className={clsx("button btn-small", step === STEPS_CONFIG.MIN_STEP && "disabled")}
                            disabled={step === STEPS_CONFIG.MIN_STEP}
                            onClick={this.previousStep}>
                            Précédent
                        </button>}
                    </div>
                    <div className={"col-6"} style={{display: 'flex', justifyContent: 'flex-end'}}>
                        {/*<button className={clsx("button btn-small", step === STEPS_CONFIG.MAX_STEP && "disabled")}*/}
                        {/*        disabled={step === STEPS_CONFIG.MAX_STEP}*/}
                        {/*        onClick={this.nextStep}>*/}
                        {/*    Suivant*/}
                        {/*</button>*/}
                    </div>
                </div>
            </div>
        );
    }
}

export default CrediterSteps;