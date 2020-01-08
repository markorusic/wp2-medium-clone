import moment from 'moment'
import http from '../../shared/http-service'

export default (() => {
    function _bindEvents() {
        $('form').on('submit', _handleFormSubmit)
        $('form .delete-gallery-item').on('click', _handleGalleryPhotoDelete)
    }

    function _handleFormSubmit(event) {
        event.preventDefault()
        const $form = $(event.target)
        const formData = _collectData($form)
        const validation = _validateData(formData.data)
        if (!validation.value) {
            alert(validation.error)
            return
        }
        $form.find('button[type="submit"]').addClass('loading-btn')
        _sendRequest(formData)
            .then(
                responseHandlers.success.bind(
                    responseHandlers,
                    $form,
                    formData.config
                )
            )
            .catch(
                responseHandlers.error.bind(
                    responseHandlers,
                    $form,
                    formData.config
                )
            )
    }

    function _sendRequest({ config, data }) {
        const { method, endpoint } = config
        return http[method](endpoint, data)
    }

    function _collectData($form) {
        return {
            config: $form.find('.config').data(),
            data: $form.serializeJSON({
                checkboxUncheckedValue: 'false',
                parseBooleans: true,
                parseNumbers: true
            })
        }
    }

    function _validateData(data) {
        // all main_photo are requried by default
        const isValid = !(data.hasOwnProperty('main_photo') && !data.main_photo)
        return {
            value: isValid,
            error: 'Main photo is required!'
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
        error($form, config, error) {
            alert('Error occured!')
            console.log(error)
            $form.find('button[type="submit"]').removeClass('loading-btn')
        },
        successCreate($form, { resource }) {
            const $alert = $form.find('.response-alert').addClass('p-3')
            let createUrl = location.href
            let seeAllUrl = ''
            let seeAllHTML = ''

            if (resource) {
                createUrl = `${location.origin}/admin/${resource}/create`
                seeAllUrl = `${location.origin}/admin/${resource}`
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
        successUpdate($form, { resource }) {
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

    function _handleGalleryPhotoDelete(event) {
        event.preventDefault()
        const $element = $(event.target)
        const endpoint = $element.data().endpoint
        if (!endpoint || !confirm('Are you sure?')) {
            return
        }
        http.delete(endpoint)
            .then(response => {
                $element
                    .parent()
                    .parent()
                    .fadeOut(() => {
                        $element.remove()
                    })
            })
            .catch(error => {
                alert('Error occured while deleting gallery photo!')
                console.log(error)
            })
    }

    return {
        init() {
            _bindEvents()
        }
    }
})()
