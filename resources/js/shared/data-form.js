import toastr from 'toastr'
import FormValidation from './form-validation'
import http from './http-service'
import photoUpload from './photo-upload'
import asyncEventHandler from './async-event-handler'

require('jquery-serializejson')

let props = {
    onCreateSuccess: null,
    onUpdateSuccess: null,
    onError: null,
    successMessage: 'Successfully saved.',
    errorMessage: 'Error occured during this action.'
}

const onFormSubmit = asyncEventHandler(event => {
    const $form = $(event.target)
    const validator = new FormValidation({ $form })
    if (!validator.validate()) {
        return Promise.resolve()
    }
    const { method, endpoint } = $form.find('.config').data()
    $form.find('button[type="submit"]').addClass('loading-btn')
    return photoUpload
        .upload()
        .then(() => {
            const data = $form.serializeJSON({
                checkboxUncheckedValue: 'false',
                parseBooleans: true,
                parseNumbers: true
            })
            return http[method](endpoint, data)
        })
        .then(response => responseHandlers[method]($form, response))
        .catch(error => {
            if (error.response.status === 422) {
                const { errors, message } = error.response.data
                validator.renderErrors(errors)
                toastr.error(message)
            } else {
                toastr.error(props.errorMessage)
            }
            if (typeof props.onError === 'function') {
                props.onError({ $form, error })
            }
        })
        .finally(() => {
            $form.find('button[type="submit"]').removeClass('loading-btn')
        })
})

const responseHandlers = {
    post($form, response) {
        toastr.success(props.successMessage)
        $form.find('button[type="submit"]').hide()
        if (typeof props.onCreateSuccess === 'function') {
            props.onCreateSuccess({
                $form,
                response
            })
        }
    },
    put($form, response) {
        toastr.success(props.successMessage)
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

const dataForm = {
    init({ photoUploadProps, ..._props } = {}) {
        props = { ...props, ..._props }
        photoUpload.init(photoUploadProps)
        $('[data-form]').on('submit', onFormSubmit)
    }
}

export default dataForm
