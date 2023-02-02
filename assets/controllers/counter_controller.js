import { Controller } from '@hotwired/stimulus';
export default class extends Controller {
    static targets = ['count'];
    connect() {
        this.count = 0;
        this.element.addEventListener('click', () => {
            this.count++;
            this.countTarget.innerText = this.count;
        });
    }
}