import Log from 'core/log';
import Selectors from './local/selectors';

const canvas = document.getElementById('mod_pdfcertificate_canvas');
const ctx = canvas.getContext('2d');

const registerEventListeners = () => {
    document.addEventListener('click', e => {
        // Clear button.
        if(e.target.closest(Selectors.actions.clearButton)) {
            clearCanvas();
        }
    });
};
function addBaseImage(url) {
    var baseImage = new Image();
    baseImage.src = url;
    baseImage.onload = function() {
        ctx.drawImage(baseImage, 0, 0, baseImage.width, baseImage.height);
    };
}

function clearCanvas() {
    Log.info('clear');
}
export const init = (url) => {
    addBaseImage(url);
    registerEventListeners();
};