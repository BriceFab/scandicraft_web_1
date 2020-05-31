export function getConfig() {
    return JSON.parse(document.getElementById('app_editor').getAttribute('config'));
}

export default {
    lang: "fr",
    name: "scandicraft-editor",
    // placeholder: "Votre message",
    autoFocus: true,
    setDefaultStyle: "font-family: 'Open Sans', sans-serif; font-size: 16px;",
    setOptions: {
        addTagsWhitelist: "<script>",
        mode: "classic",
        katex: "window.katex",
        height: '300px',
        minHeight: '300px',
        maxHeight: '700px',
        charCounter: true,
        charCounterType: 'char',
        charCounterLabel: "Caractères: ",
        maxCharCount: getConfig.maxCharCount || 2000,
        font: [
            "Arial",
            "Open Sans",
        ],
        fontSize: [
            14,
            16,
            18,
            20,
        ],
        colorList: [
            [
                "#ff0000",
                "#ff5e00",
                "#ffe400",
                "#abf200"
            ],
            [
                "#00d8ff",
                "#0055ff",
                "#6600ff",
                "#ff00dd"
            ]
        ],
        imageRotation: true,
        "imageUploadUrl": getConfig().imageUploadUrl,
        "imageUploadSizeLimit": "3000000",  //en byte; 3000000 byte = 3 MB
        "imageWidth": "30%",
        // "imageWidth": "300px",
        "imageHeight": "30%",
        // "imageHeight": "300px",
        "videoFileInput": false,
        "audioUrlInput": false,
        "tabDisable": false,
        "lineHeights": [
            {
                "text": "Simple",
                "value": 1
            },
            {
                "text": "Double",
                "value": 2
            }
        ],
        buttonList: [
            [
                "undo",
                "redo",
                "removeFormat",
                "font",
                "fontSize",
                "formatBlock",
                "blockquote",
                "bold",
                "underline",
                "italic",
                "strike",
                "subscript",
                "superscript",
                "fontColor",
                "hiliteColor",
                "outdent",
                "indent",
                "align",
                "horizontalRule",
                "list",
                "lineHeight",
                "table",
                "link",
                "image",
                "video",
                "fullScreen",
                "showBlocks",
                // "codeView",
                "preview",
                // "save",
            ]
        ],
    }
}