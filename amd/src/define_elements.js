// Define and place elements to be on a pdf base image
import Selectors from './local/selectors';
import Log from 'core/log';
import Url from 'core/url';

//let color = 'black';

const canvas = document.getElementById('mod_pdfcertificate_canvas');
const ctx = canvas.getContext('2d');

const registerEventListeners = (courseid) => {
    document.addEventListener('click', e => {
        if (e.target.closest(Selectors.actions.clearButton)) {
            clearCanvas();
            Log.info('Clear clicked');
        }
        if (e.target.closest(Selectors.actions.cancelButton)) {
            Log.info(courseid);
            Log.info('Cancel clicked');
        }
    });
};

function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

export const init = (courseid) => {
    registerEventListeners(courseid);
};