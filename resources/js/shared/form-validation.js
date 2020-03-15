const selector = {
    form: '[data-validate-form]',
    field: '[data-validate]',
    errorPlaceholder: '[data-validate-error-message]'
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

    constructor({ $form }) {
        if (!$form) {
            throw new Error('FormValidation constructor - Invalid $form')
        }
        this.$form = $form
        this.fields = this.$form
            .find(selector.field)
            .toArray()
            .map(extractField)
    }

    validate() {
        const errors = this.collectErrors()
        this.renderErrors(errors)
        return Object.keys(errors).length === 0
    }

    collectErrors() {
        const errors = {}
        this.fields.forEach(({ name, rules, $el }, _, fields) => {
            const value = $el.val()
            const fieldErrors = []
            if (
                rules.required !== undefined &&
                (!value || value.length === 0)
            ) {
                fieldErrors.push(rules.required || 'Required field')
            }
            if (rules.pattern && !new RegExp(rules.pattern).test(value)) {
                fieldErrors.push(rules.patternMessage)
            }
            if (rules.sameAs) {
                const cmpFieldName = rules.sameAs
                const cmpField = fields.find(f => f.name === cmpFieldName)
                if (cmpField && value !== cmpField.$el.val()) {
                    fieldErrors.push(rules.sameAsMessage)
                }
            }
            if (fieldErrors.length > 0) {
                errors[name] = fieldErrors
            }
        })
        return errors
    }

    renderErrors(errors) {
        this.fields.forEach(({ name, $el }) => {
            const fieldErrors = errors[name] || errors[name.replace('[]', '')]
            if (fieldErrors && fieldErrors.length > 0) {
                fieldErrors.forEach(error => this.showErrorMessage($el, error))
            } else {
                this.removeErrorMessage($el)
            }
        })
    }

    showErrorMessage($field, message) {
        let $placeholder = $field.siblings(selector.errorPlaceholder).first()
        if ($placeholder.length === 0) {
            $placeholder = $(`
                <div class="mb-1" ${selector.errorPlaceholder.slice(1, -1)}>
                </div>
            `)
            $field.before($placeholder)
        }
        $placeholder.html(`<span class="text-danger">${message}</span>`)
    }

    removeErrorMessage($field) {
        $field.siblings(selector.errorPlaceholder).remove()
    }
}
