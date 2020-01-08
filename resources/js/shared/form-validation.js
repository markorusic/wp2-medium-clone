const selector = {
    form: '[data-validate-form]',
    field: '[data-validate]',
    errorPlaceholder: '.invalid-feedback'
}

const defaultRules = {
    required: null,
    pattern: '(.*?)',
    patternMessage: ''
}

const extractField = field => {
    const $field = $(field)
    const { validate, ...rules } = $field.data()
    return {
        name: $field.attr('name'),
        rules: { ...defaultRules, ...rules },
        $el: $field
    }
}

export default class FormValidation {
    static init() {
        $(selector.form).on('submit', event => {
            const $form = $(event.target)
            const validator = new FormValidation({ $form })
            return validator.validate()
        })
    }

    constructor({ $form, validateOnChange = true }) {
        if (!$form) {
            throw new Error('FormValidation constructor - Invalid $form')
        }
        this.$form = $form
        this.fields = this.$form
            .find(selector.field)
            .toArray()
            .map(extractField)
        if (validateOnChange) {
            this.fields.forEach(field => {
                field.$el.on('change', () => {
                    this.validateField(field)
                })
            })
        }
    }

    validate() {
        const fieldValidities = this.fields.map(this.validateField.bind(this))
        return fieldValidities.every(v => v)
    }

    validateField(field) {
        let errorMessages = []
        const { rules, $el } = field
        const value = $el.val()
        if (rules.required && !value) {
            errorMessages.push(rules.required)
        }
        if (rules.pattern && !new RegExp(rules.pattern).test(value)) {
            errorMessages.push(rules.patternMessage)
        }
        if (rules.sameAs) {
            const cmpFieldName = rules.sameAs
            const cmpField = this.fields.find(f => f.name === cmpFieldName)
            if (cmpField && value !== cmpField.$el.val()) {
                errorMessages.push(rules.sameAsMessage)
            }
        }
        if (errorMessages.length > 0) {
            this.showErrorMessage($el, errorMessages.join('<br />'))
            return false
        }
        this.removeErrorMessage($el)
        return true
    }

    showErrorMessage($field, message) {
        let $placeholder = $field.siblings(selector.errorPlaceholder).first()
        if ($placeholder.length === 0) {
            $field.after(
                `<span class="${selector.errorPlaceholder.slice(1)}"></span>`
            )
            $placeholder = $field.next()
        }
        $field.addClass('is-invalid')
        $placeholder.html(`<strong>${message}</strong>`)
    }

    removeErrorMessage($field) {
        $field.removeClass('is-invalid')
        $field.siblings(selector.errorPlaceholder).remove()
    }
}
