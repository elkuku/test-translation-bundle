import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['content']

    toggle(event) {
        event.preventDefault();

        this.contentTarget.hidden = !this.contentTarget.hidden;
    }

    hide(event) {
        let shouldFire = !this.element.contains(event.target) || event instanceof KeyboardEvent;

        if (shouldFire && !this.contentTarget.hidden) {
            this.contentTarget.hidden = true;
        }
    }
}
