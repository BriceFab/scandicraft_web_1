import axios from 'axios';
import {toast} from 'react-toastify';
import {CONFIG} from "../config";

//data
const params = JSON.parse(document.getElementById('script_skin_data').innerText);

//variables
const skin_input = document.getElementById(params['skin_input']);
const btn_send_skin = document.getElementById(params['button_skin_upload']);

//init
toggleVisible(true);

//events
skin_input.addEventListener('change', () => {
    toggleVisible(false);
})

btn_send_skin.addEventListener('click', () => {
    const skin_file = skin_input.files[0];

    /* Checks */
    try {
        const valid_file = checkFile(skin_file);
        if (valid_file) {
            const formData = new FormData();
            formData.append('skin', skin_file);

            axios.post(params['upload_skin_route'], formData, {
                headers: {'Content-Type': 'multipart/form-data'}
            }).then((res) => {
                toast.success('Skin modifié avec succès. Relancez votre jeu.');
                toggleVisible(true);
            }, (err) => {
                toast.error(`Erreur: ${err.response.data.message}`);
                toggleVisible(true);
            });
        }
    } catch (e) {
        toast.error(e.message);
        toggleVisible(true);
    }
});

//Functions
function checkFile(file) {
    //formats
    if (!file.type.includes(CONFIG.SKIN.EXTENSION_FORMATS)) {
        throw new Error('Format invalide. Liste des formats: ' + CONFIG.SKIN.EXTENSION_FORMATS.map(format => {
            return `${format}, `
        }));
    }

    //dimensions
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function (e) {
        let image = new Image();
        image.src = e.target.result.toString();
        image.onload = function () {
            let height = this.height;
            let width = this.width;

            // if (width !== 64 && (height !== 64 || height !== 32)) {
            if (width !== 64 && height !== 64) {
                throw new Error("L'image doit être en 64x64 ou 64x32 pixels");
            }
        };
    }

    //taille
    if (file.size >= 1000000) { //1000000 bytes = 1 MB
        throw new Error("Taille trop grande, l'image doit être moins de 1 MB");
    }

    return true;
}

function toggleVisible(can_send = false) {
    if (can_send) {
        btn_send_skin.style.visibility = 'hidden';
        skin_input.style.visibility = 'visible';
    } else {
        btn_send_skin.style.visibility = 'visible';
        skin_input.style.visibility = 'hidden';
    }
}