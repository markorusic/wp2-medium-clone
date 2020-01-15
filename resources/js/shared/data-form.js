import toastr from 'toastr'
import FormValidation from './form-validation'
import http from './http-service'

require('jquery-serializejson')

let props = {
    createRedirectUrl: null
}

const onFormSubmit = event => {
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
                this.successCreate($form, response)
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
        toastr.error('Error occured during this action!')
        $form.find('button[type="submit"]').removeClass('loading-btn')
    },
    successCreate($form, response) {
        toastr.success('Successfully created!')
        $form.find('button[type="submit"]').hide()
        if (typeof createRedirectUrl === 'function') {
            const url =
                location.protocol +
                '//' +
                location.host +
                props.createRedirectUrl(response.data)
            $(location).attr('href', url)
        }
    },
    successUpdate($form) {
        toastr.success('Successfully updated!')
        $form
            .on('submit', event => event.preventDefault())
            .find('button[type="submit"]')
            .removeClass('loading-btn')
    }
}

const dataFrom = {
    init(_props) {
        props = _props
        $('form').on('submit', onFormSubmit)
    }
}

export default dataFrom
