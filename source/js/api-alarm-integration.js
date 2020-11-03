import ObjectByString from './Helper/Object';
import AlarmModule from './api-alarm-module';

document.querySelectorAll('[data-api-alarms]').forEach((el) => {
    const AlarmInstance = new AlarmModule(el);
})