import $ from 'jquery';
import axios from 'axios';
import { toast } from 'react-toastify';

$(() => {
    //register spoils share button
    const spoils_share = $('.btn-share-spoil');
    spoils_share.each((index, element) => {
        element.addEventListener('click', () => {
            setTimeout(() => {

                //post share
                const data = $(`.spoil-share-data-${element.getAttribute('spoil')}`);
                postShare(data.attr('user'), data.attr('spoil'), element.getAttribute('type'));

            }, 8000);   //wait 8 secondes
        });
    });
});

function postShare(user, spoil, share_type) {
    axios.post(`/add/spoil/share/${spoil}/${share_type}`, {
        'type': share_type,
        'user': user,
        'spoil': spoil,
    }).then((res) => {
        // console.log('res ok', res)
        toast.success('Merci pour votre partage !')
    }, (err) => {
        // console.error('share err', err)
        toast.warn('Votre partage n\'a pas pu être enregistré. Vous avez peut-être déjà partager ce spoil')
    })
}