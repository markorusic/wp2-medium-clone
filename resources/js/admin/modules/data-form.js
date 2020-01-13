import moment from 'moment'
import FormValidation from '../../shared/form-validation'
import http from '../../shared/http-service'

const bindEvents = () => {
    $('form').on('submit', handleFormSubmit)
    $('form .delete-gallery-item').on('click', handleGalleryPhotoDelete)
}

const handleFormSubmit = event => {
    event.preventDefault()
    const $form = $(event.target)
    const validator = new FormValidation({ $form })

    if (validator.validate()) {
        const config = $form.find('.config').data()
        const data = $form.serializeJSON({
            checkboxUncheckedValue: 'false',
            parseBooleans: true,
            parseNumbers: true
        })

        $form.find('button[type="submit"]').addClass('loading-btn')
        http[config.method](config.endpoint, data)
            .then(
                responseHandlers.success.bind(responseHandlers, $form, config)
            )
            .catch(responseHandlers.error.bind(responseHandlers, $form, config))
    }
}

const responseHandlers = {
    success($form, config, response) {
        console.log(response)
        switch (config.method) {
            case 'post':
                this.successCreate($form, config)
                break
            case 'put':
                this.successUpdate($form, config)
                break
            default:
                break
        }
    },
    error($form, _, error) {
        console.log(error)
        alert('Error occured!')
        $form.find('button[type="submit"]').removeClass('loading-btn')
    },
    successCreate($form, { resource }) {
        const $alert = $form.find('.response-alert').addClass('p-3')
        let createUrl = location.href
        let seeAllUrl = ''
        let seeAllHTML = ''

        if (resource) {
            createUrl = `${location.origin}/admin/${resource}/create`
            seeAllUrl = `${location.origin}/admin/${resource}/show-all`
            seeAllHTML = `<li><a class="bold" href="${seeAllUrl}">See all</a></li>`
        }

        const linksHTML = `
				<ul class="flex-list nice-list">
					<li><a class="bold" href="${createUrl}">Create new</a></li>
					${seeAllHTML}
				</ul>
			`

        $alert.find('.message').text('Successfuly created!')
        $alert.find('.options').html(linksHTML)

        $form
            .on('submit', event => event.preventDefault())
            .find('button[type="submit"]')
            .fadeOut()
    },
    successUpdate($form) {
        // clear gallery fields
        $('.dz-gallery')
            .find('input[type="hidden"]')
            .remove()

        const $alert = $form.find('.response-alert').addClass('p-3')
        const time = moment().format('HH:mm:ss')

        $alert.find('.message').text('Successfuly updated!')
        $alert.find('.options').text(`Updated at: ${time}`)

        $form
            .on('submit', event => event.preventDefault())
            .find('button[type="submit"]')
            .removeClass('loading-btn')
    }
}

function handleGalleryPhotoDelete(event) {
    event.preventDefault()
    const $element = $(event.target)
    const endpoint = $element.data().endpoint
    if (!endpoint || !confirm('Are you sure?')) {
        return
    }
    http.delete(endpoint)
        .then(() => {
            $element
                .parent()
                .parent()
                .fadeOut(() => {
                    $element.remove()
                })
        })
        .catch(error => {
            console.log(error)
            alert('Error occured while deleting gallery photo!')
        })
}

const dataFrom = {
    init() {
        bindEvents()
    }
}

export default dataFrom
