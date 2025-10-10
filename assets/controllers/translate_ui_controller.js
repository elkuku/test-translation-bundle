import {Controller} from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['object', 'listContainer', 'itemContainer', 'statusContainer', 'indicator']

    async switchObject(event) {
        const objectName = event.params.objectName;

        this.objectTargets.forEach(target => {
            target.classList.remove('active');
        });
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

    async loadTranslation({params: {objectName, field, id, locale, index, indicatorIndex}}) {
        const target = this.itemContainerTargets[index]
        const status = this.statusContainerTargets[index]

        status.innerHTML = 'Loading...';

        try {
            const response = await fetch(`/translate-ui/${objectName}/${id}/${field}/${locale}/${index}/${indicatorIndex}`, {});
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

    async saveTranslation({params: {objectName, id, field, locale, index, indicatorIndex}}) {
        const element = this.itemContainerTargets[index]
        const status = this.statusContainerTargets[index]

        status.innerHTML = 'Saving...';
        let value = ''

        for (const el of ['input', 'textarea']) {
            element.querySelectorAll(el).forEach(e => {
                if (field === e.dataset.property) {
                    value = e.value;
                }
            })
        }

        const data = {
            objectName: objectName,
            field: field,
            id: id,
            value: value,
            locale: locale
        };

        try {
            const response = await fetch('/translate-ui/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                status.innerHTML = await response.text();
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

    async googleTranslate({params: {text, sourceLocale, targetLocale}}) {
        console.log(text);
        const data = {
            text: text,
            sourceLocale: sourceLocale,
            targetLocale: targetLocale,
        }
        /*
            objectName: objectName,
            field: field,
            id: id,
            value: value,
            locale: locale
        };
*/
        try {
            const response = await fetch('/translate-ui/google-translate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                console.log(await response.text())
                return
                status.innerHTML = await response.text();
                element.innerHTML = '';

            } else {
                status.innerHTML = 'Failed to load partial: ' + response.statusText;
            }
        } catch (error) {
            status.innerHTML = 'Error fetching partial: ' + error;
        }
    }
}
