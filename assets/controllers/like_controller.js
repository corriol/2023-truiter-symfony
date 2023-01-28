import { Controller } from '@hotwired/stimulus';
/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['count', 'icon']
    static values = {
        url: String,
    }
    connect() {
        this.count = 0;
        this.element.addEventListener('click', (event) => {
            this.load();
            event.preventDefault();
            console.log(this.urlValue);
        });
    }

    load() {
        fetch(this.urlValue)
            .then(response => response.json())
            .then(data => this.update(data))
    }

    update(data) {
        console.log(data.result === 'ok');
        if (data.result === "ok") {
            console.log('no entra?');
            this.count++;
            this.countTarget.innerText = this.count;
            this.iconTarget.innerHTML = "<i class=\"text-danger bi bi-heart-fill\"></i>";
        }
    }
    // ...
}
