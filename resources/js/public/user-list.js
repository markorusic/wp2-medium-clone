import range from 'lodash/range'
import http from '../shared/http-service'
import templateRender from '../shared/template-render'
import dataPagination from '../shared/data-pagination'

const config = {
    perPage: 5
}

export const userTemplate = user => `
    <a class="d-flex align-items-center p-3 border-bottom" href="/users/${user.id}">
        <img class="avatar mr-3" src="${user.avatar}" alt="${user.name}">
        <span class="text-dark">${user.name}</span>
    </a>
`

export const userListTempalte = users => `
    <div class="d-flex flex-column">
        ${templateRender.list(users, userTemplate)}
    </div>
`

export const userListLoadingTempalte = (numbeOfItems = config.perPage) => `
    <div class="d-flex flex-column">
        ${templateRender.list(
            range(0, numbeOfItems),
            () =>
                `<div class="border-bottom border-white" style="height: 82px; background-color: #ebe6e6"></div>`
        )}
    </div>
`

export const userListAlertTemplate = (message, type = 'info') => `
    <div class="d-flex flex-column">
        <div class="alert alert-${type}" role="alert">
            ${message}
        </div>
    </div>
`

export const fetchUserList = (baseUrl, { onFetch = null } = {}) => event => {
    const $modal = $(event.currentTarget)
    const $content = $modal.find('.modal-body')
    const $footer = $modal.find('.modal-footer')

    const fetchData = (page = 1) => {
        $content.html(userListLoadingTempalte())
        return http
            .get(`${baseUrl}?page=${page}&&size=${config.perPage}`)
            .then(response => {
                if (typeof onFetch === 'function') {
                    onFetch(response.data)
                }
                const users = response.data.data
                if (users.length === 0) {
                    const message = 'No data.'
                    return $content.html(userListAlertTemplate(message))
                }
                $content.html(userListTempalte(users))
                dataPagination.init($footer, {
                    pagination: response.data,
                    onPageChange: fetchData
                })
            })
            .catch(() => {
                const message = 'Error occured while fetching data.'
                $content.html(userListAlertTemplate(message, 'danger'))
            })
    }

    return fetchData()
}
