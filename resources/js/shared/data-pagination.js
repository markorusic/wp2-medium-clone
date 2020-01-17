import noop from 'lodash/noop'
import templateRender from './template-render'

const renderPagination = (selector, { current_page, per_page, total }) => {
    const $pagination = $(selector)
    const pages = [...Array(Math.ceil(total / per_page)).keys()].map(
        page => page + 1
    )

    if (pages.length < 2) {
        return $pagination.html('')
    }

    const paginationHtml = `
        <ul class="pagination">
            <li class="page-item${templateRender.if(
                current_page < 2,
                ' disabled'
            )}"
                data-page="${current_page - 1}"
            >
                <a class="page-link" href="#">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            ${templateRender.list(
                pages,
                page =>
                    `<li class="page-item${templateRender.if(
                        current_page === page,
                        ' active'
                    )}" data-page="${page}"><a class="page-link" href="#">${page}</a></li>`
            )}
            <li class="page-item${templateRender.if(
                current_page >= pages.length,
                ' disabled'
            )} " data-page="${current_page + 1}">
                <a class="page-link" href="#">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>`

    return $pagination.html(paginationHtml)
}
const defaultPagination = { current_page: 0, per_page: 10, total: 0 }

const dataPagination = {
    render: renderPagination,
    init(
        selector,
        { pagination = defaultPagination, onPageChange = noop } = {}
    ) {
        const $pagination = renderPagination(selector, pagination)
        $pagination.find('.page-item').on('click', event => {
            event.preventDefault()
            const { page } = $(event.currentTarget).data()
            onPageChange(page)
        })
    }
}

export default dataPagination
