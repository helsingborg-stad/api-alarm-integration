import AlarmModule from './api-alarm-module';
import NoticeModule from './api-alarm-notice';

document.querySelectorAll('[data-api-alarms]').forEach((el) => {
    const AlarmInstance = new AlarmModule(el);
})

const NoticeInstance = new NoticeModule();