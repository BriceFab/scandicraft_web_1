import React, { Component } from 'react';
import SunEditor from 'suneditor-react';
import '../../node_modules/suneditor/dist/css/suneditor.min.css'; // Import Sun Editor's CSS File
import editor_config, { getConfig } from './editor_config';

class Editor extends Component {

    constructor(props) {
        super(props);

        this.onImageUpload.bind(this);
        this.onImageUploadError.bind(this);
        this.onChange.bind(this);

        const input_name = getConfig().input_result_name;
        const data = document.getElementById(input_name).value;

        this.state = {
            'message': data
        }

        // console.log('data', data)
    }

    onImageUpload(targetImgElement, index, state, imageInfo, remainingFilesCount) {
        // console.log(targetImgElement, index, state, imageInfo, remainingFilesCount)
    }

    onImageUploadError(errorMessage, result) {
        // console.log(errorMessage, result)
    }

    onChange(data) {
        // console.log('onChange data', data)
        const input_name = getConfig().input_result_name;
        // console.log('result input', document.getElementById(input_name))
        document.getElementById(input_name).value = data;
    }

    render() {
        // console.log('render state', this.state)
        return (
            <SunEditor {...editor_config} setContents={this.state.message} onChange={this.onChange} onImageUpload={this.onImageUpload} onImageUploadError={this.onImageUploadError} />
        );
    }
};

export default Editor;