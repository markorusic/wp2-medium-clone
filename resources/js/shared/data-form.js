import toastr from 'toastr'
import FormValidation from './form-validation'
import http from './http-service'
import photoUpload from './photo-upload'
import asyncEventHandler from './async-event-handler'

require('jquery-serializejson')

let props = {
    onCreateSuccess: null,
    onUpdateSuccess: null,
    onError: null
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
        .catch(error => responseHandlers.error($form, error))
})

const responseHandlers = {
    error($form, error) {
        toastr.error('Error occured during this action!')
        $form.find('button[type="submit"]').removeClass('loading-btn')
        if (typeof props.onError === 'function') {
            props.onError({ $form, error })
        }
    },
    post($form, response) {
        toastr.success('Successfully created!')
        $form.find('button[type="submit"]').hide()
        if (typeof props.onCreateSuccess === 'function') {
            props.onCreateSuccess({
                $form,
                response
            })
        }
    },
    put($form, response) {
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
    init({ photoUploadProps, ..._props } = {}) {
        props = { ...props, ..._props }
        photoUpload.init(photoUploadProps)
        $('form').on('submit', onFormSubmit)
    }
}

export default dataFrom
