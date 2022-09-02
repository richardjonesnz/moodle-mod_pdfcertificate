// Define and place elements to be on a pdf base image
import Selectors from './local/selectors';
import Log from 'core/log';
import Url from 'core/url';

const dragElement(document)

const registerEventListeners = (courseid) => {
    document.addEventListener('click', e => {
        if (e.target.closest(Selectors.actions.clearButton)) {
            Log.info('Clear clicked');
        }
        if (e.target.closest(Selectors.actions.cancelButton)) {
            Log.info(courseid);
            Log.info('Cancel clicked');
        }
    });
};

export const init = (courseid) => {
    registerEventListeners(courseid);
};