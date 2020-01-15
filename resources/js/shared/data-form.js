import toastr from 'toastr'
import FormValidation from './form-validation'
import http from './http-service'

require('jquery-serializejson')

let props = {
    onCreateSuccess: null,
    onUpdateSuccess: null,
    onError: null
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
            .catch(responseHandlers.error.bind(responseHandlers, $form))
    }
}

const responseHandlers = {
    success($form, config, response) {
        switch (config.method) {
            case 'post':
                this.successCreate($form, response)
                break
            case 'put':
                this.successUpdate($form, response)
                break
            default:
                break
        }
    },
    error($form, error) {
        toastr.error('Error occured during this action!')
        $form.find('button[type="submit"]').removeClass('loading-btn')
        if (typeof props.onError === 'function') {
            props.onError({ $form, error })
        }
    },
    successCreate($form, response) {
        toastr.success('Successfully created!')
        $form.find('button[type="submit"]').hide()
        if (typeof props.onCreateSuccess === 'function') {
            props.onCreateSuccess({
                $form,
                response
            })
        }
    },
    successUpdate($form, response) {
        toastr.success('Successfully updated!')
        $form
            .on('submit', event => event.preventDefault())
            .find('button[type="submit"]')
            .removeClass('loading-btn')
        if (typeof props.onUpdateSuccess === 'function') {
            props.onUpdateSuccess({
                $form,
                response
            })
        }
    }
}

const dataFrom = {
    init(_props = {}) {
        props = { ...props, ..._props }
        $('form').on('submit', onFormSubmit)
    }
}

export default dataFrom
