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
        id: Number
    }
    connect() {
        this.init(this.idValue);

        this.element.addEventListener('click', (event) => {
            this.load();
            event.preventDefault();
        });
    }

    init(id){
        console.log('dd');
        fetch('/api/tweets/' + id.toString())
            .then(response => response.json())
            .then(data => this.setCounter(data))
    }

    setCounter(data) {
        this.count = data.result;
        if (data.liked) {
            this.countTarget.innerText = this.count;
            this.iconTarget.innerHTML = "<i class=\"text-danger bi bi-heart-fill\"></i>";
        }
    }

    load() {
        fetch(this.urlValue)
            .then(response => response.json())
            .then(data => this.update(data))
    }

    update(data) {
        if (data.result === "ok") {
            this.count++;
            this.countTarget.innerText = this.count;
            this.iconTarget.innerHTML = "<i class=\"text-danger bi bi-heart-fill\"></i>";
        }
    }
    // ...
}
