/**
 * Public Profile Manager Component
 * Handles star ratings for reviews, profile steps modal,
 * and user reporting functionality.
 */
import { StarRating, ModalManager } from '../modules/ui-utils.js';

export class PublicProfileManager {
    constructor(options = {}) {
        this.options = options;
        this.init();
    }

    init() {
        this.initStarRating();
        this.initModals();
        this.initGlobalHandlers();
    }

    initStarRating() {
        const container = document.getElementById('star-rating');
        const input = document.getElementById('stars-input');
        if (container && input) {
            StarRating.init(container, input);
        }
    }

    initModals() {
        // Register Modals with ModalManager
        ModalManager.bindEvents('profile-steps-modal');
        ModalManager.bindEvents('user-report-modal');
    }

    initGlobalHandlers() {
        // Attach helpers to window for legacy onclick compatibility
        window.openUserReportModal = (id) => this.openUserReportModal(id);
        window.closeUserReportModal = () => ModalManager.close('user-report-modal');
        
        window.openProfileStepsModal = () => ModalManager.open('profile-steps-modal');
        window.closeProfileStepsModal = () => ModalManager.close('profile-steps-modal');
    }

    openUserReportModal(reportedAccountId) {
        const input = document.getElementById('user-report-reported-account-id');
        const desc = document.getElementById('user-report-description');
        
        if (input) input.value = reportedAccountId;
        if (desc) desc.value = '';
        
        ModalManager.open('user-report-modal');
    }
}
