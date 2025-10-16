import {Controller} from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['object', 'listContainer', 'itemContainer', 'input', 'statusContainer', 'indicator']

    async switchObject(event) {
        this.objectTargets.forEach(target => {
            target.classList.remove('active');
        });

        event.target.classList.add('active');

        try {
            const response = await fetch(event.params.uri);
            if (response.ok) {
                this.listContainerTarget.innerHTML = await response.text();
            } else {
                this.listContainerTarget.innerHTML = 'Failed to load partial: ' + response.statusText;
            }
        } catch (error) {
            this.listContainerTarget.innerHTML = 'Error fetching partial: ' + error;
        }
    }

    async loadTranslation({params: {uri, fieldIndex}}) {
        const target = this.itemContainerTargets[fieldIndex]
        const status = this.statusContainerTargets[fieldIndex]

        status.innerHTML = 'Loading...';

        try {
            const response = await fetch(uri);
            if (response.ok) {
                target.innerHTML = await response.text();
            } else {
                target.innerHTML = 'Failed to load partial: ' + response.statusText;
            }
        } catch (error) {
            target.innerHTML = 'Error fetching partial: ' + error;
        }

        status.innerHTML = '';
    }

    async saveTranslation({params: {uri, data, field, fieldIndex, indicatorIndex}}) {
        const element = this.itemContainerTargets[fieldIndex]
        const status = this.statusContainerTargets[fieldIndex]

        let value = ''

        status.innerHTML = 'Saving...';

        for (const el of ['input', 'textarea']) {
            element.querySelectorAll(el).forEach(e => {
                if (field === e.dataset.property) {
                    value = e.value;
                }
            })
        }

        data.value = value

        try {
            const response = await fetch(uri, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                status.innerHTML = '';
                element.innerHTML = '';
                this.indicatorTargets[indicatorIndex].style.backgroundColor = 'green'

            } else {
                status.innerHTML = 'Failed to load partial: ' + response.statusText;
            }
        } catch (error) {
            status.innerHTML = 'Error fetching partial: ' + error;
        }
    }

    cancelTranslation({params: {index}}) {
        this.itemContainerTargets[index].innerHTML = '';
    }

    async googleTranslate({params: {uri, text, sourceLocale, targetLocale, fieldIndex, inputId}}) {
        const status = this.statusContainerTargets[fieldIndex]
        const data = {
            text: text,
            sourceLocale: sourceLocale,
            targetLocale: targetLocale,
        }

        try {
            const response = await fetch(uri, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                const input = document.getElementById(inputId);
                const translation = await response.text();

                input.value = translation.replace(/^"(.*)"$/, '$1');

            } else {
                status.innerHTML = 'Failed to google translate: ' + response.statusText;
            }
        } catch (error) {
            status.innerHTML = 'Error fetching google translate: ' + error;
        }
    }
}
