/**
 * Task Report Manager Component
 * Handles the logic for reporting tasks, including modal management.
 */
import { ModalManager } from '../modules/ui-utils.js';

export class TaskReportManager {
    constructor() {
        this.init();
    }

    init() {
        ModalManager.bindEvents('report-modal');
        this.initGlobalHandlers();
    }

    initGlobalHandlers() {
        window.openReportModal = (advertisementId, reportedAccountId) => {
            const advInput = document.getElementById('report-advertisement-id');
            const accInput = document.getElementById('report-reported-account-id');
            const desc = document.getElementById('report-description');
            
            if (advInput) advInput.value = advertisementId;
            if (accInput) accInput.value = reportedAccountId;
            if (desc) desc.value = '';
            
            ModalManager.open('report-modal');
        };
        
        window.closeReportModal = () => ModalManager.close('report-modal');
    }
}
