
import NoticeModule from './api-alarm-notice';
import { HttpService } from './http-service';
import { TemplateService } from './template-service';
import { TransformService } from './transform-service';
    
document.addEventListener("DOMContentLoaded", function(event) { 
    NoticeModule(settings).render({
       http: HttpService(),
       template: TemplateService(),
       transform: TransformService()
    });
});