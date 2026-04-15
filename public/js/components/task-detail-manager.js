/**
 * Task Detail Manager Component
 * Handles interactive elements on the Task Details page,
 * such as the description toggle and image lightboxes.
 */
export class TaskDetailManager {
    constructor() {
        this.init();
    }

    init() {
        this.initToggles();
    }

    initToggles() {
        const toggleBtn = document.getElementById('details-toggle');
        const content = document.getElementById('details-content');
        const text = document.getElementById('details-toggle-text');
        const icon = document.getElementById('details-toggle-icon');
        const fade = document.getElementById('details-fade');

        if (!toggleBtn || !content) return;

        toggleBtn.addEventListener('click', () => {
            const isCollapsed = content.classList.contains('max-h-20');

            if (isCollapsed) {
                content.classList.remove('max-h-20', 'overflow-hidden');
                if (fade) fade.classList.add('hidden');
                if (text) text.textContent = text.dataset.less || 'Show Less';
                if (icon) icon.classList.add('rotate-180');
            } else {
                content.classList.add('max-h-20', 'overflow-hidden');
                if (fade) fade.classList.remove('hidden');
                if (text) text.textContent = text.dataset.more || 'Show More';
                if (icon) icon.classList.remove('rotate-180');
            }
        });
    }
}
