import http from '../../shared/http-service'

export default (() => {
    const $dom = {}

    function _cacheDom() {
        $dom.delete = $('[data-delete]')
    }

    function _bindEvents() {
        $dom.delete.on('click', _deleteResource)
        $dom.delete.find('i').on('click', event => {
            $(event.target)
                .parent()
                .trigger('click')
        })
    }

    function _deleteResource(event) {
        const $el = $(event.target)
        const $resourceEl = $el.parent().parent()
        const endpoint = $el.data().delete
        if (!endpoint || !confirm('Are you sure?')) {
            return null
        }
        http.delete(endpoint)
            .then(() => {
                $resourceEl.fadeOut()
            })
            .catch(error => {
                alert('error')
                console.log(error)
            })
    }

    return {
        init() {
            _cacheDom()
            _bindEvents()
        }
    }
})()
