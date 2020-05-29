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
        document.getElementById(input_name).value = data;
    }

    render() {
        return (
            <SunEditor {...editor_config} onChange={this.onChange} onImageUpload={this.onImageUpload} onImageUploadError={this.onImageUploadError} />
        );
    }
};

export default Editor;