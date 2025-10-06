import {Controller} from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['object', 'listContainer', 'itemContainer']
    static values = {
        defaultLocale: String,
    }

    initialize() {
        // Called once when the controller is first instantiated (per element)

        // Here you can initialize variables, create scoped callables for event
        // listeners, instantiate external libraries, etc.
        // this._fooBar = this.fooBar.bind(this)
    }

    connect() {
        console.log('Connected - ' + this.defaultLocaleValue);
        // Called every time the controller is connected to the DOM
        // (on page load, when it's added to the DOM, moved in the DOM, etc.)

        // Here you can add event listeners on the element or target elements,
        // add or remove classes, attributes, dispatch custom events, etc.
        // this.fooTarget.addEventListener('click', this._fooBar)
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()"
        // this.fooTarget.removeEventListener('click', this._fooBar)
    }

    async switchObject(event) {
        const objectName = event.params.objectName;

        this.objectTargets.forEach(target => {target.classList.remove('active');});
        event.target.classList.add('active');

        try {
            const response = await fetch(`/translate-ui/list/${objectName}`);
            if (response.ok) {
                this.listContainerTarget.innerHTML = await response.text();
            } else {
                this.listContainerTarget.innerHTML = 'Failed to load partial: ' + response.statusText;
            }
        } catch (error) {
            this.listContainerTarget.innerHTML = 'Error fetching partial: ' + error;
        }
    }

    async loadTranslation({params: {objectName, id, locale, index}}) {
        const target =  this.itemContainerTargets[index]

        try {
            const response = await fetch(`/translate-ui/${objectName}/${id}/${locale}/${index}`);
            if (response.ok) {
                target.innerHTML = await response.text();
            } else {
                target.innerHTML = 'Failed to load partial: ' + response.statusText;
            }
        } catch (error) {
            target.innerHTML = 'Error fetching partial: ' + error;
        }

    }

    saveTranslation(event) {
        const index = event.params.index;
        const element =  this.itemContainerTargets[index]
        console.log(index, element);
        const allInputs = element.querySelectorAll('input');
        console.log(allInputs);
        const allInputs2 = element.querySelectorAll('textarea');
        console.log(allInputs2);
        allInputs.forEach(input => {
            console.log(input.value);
        })
        allInputs2.forEach(input => {
            console.log(input.value);
        })
    }

    cancelTranslation({params: {index}}) {
        this.itemContainerTargets[index].innerHTML = '';
    }
}
