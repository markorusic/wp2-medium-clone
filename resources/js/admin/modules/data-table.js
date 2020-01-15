import debounce from 'lodash/debounce'
import templateRender from '../../shared/template-render'
import http from '../../shared/http-service'

const resourceAction = {
    edit: 'edit',
    delete: 'delete',
    create: 'create'
}

let props = {
    resource: null,
    searchBy: null,
    actions: Object.values(resourceAction),
    columns: []
}

let state = {
    pageSize: 10,
    sort: ''
}

const $dom = {
    root: null,
    search: null,
    table: null,
    pagination: null
}

const view = {
    renderContainer() {
        const html = `
    <div id="${props.resource}-data-table" class="container py-2">
        <div class="card-header">
        <div class="flex-sp-between">
            <h4 class="bold uc-first">${props.resource}</h4>
            ${templateRender.if(
                props.actions.includes(resourceAction.create),
                `<span>
                    <a class="btn btn-success btn-sm" href="/admin/${props.resource}/create">
                        <i class="fa fa-plus" aria-hidden="true"></i> 
                        Create
                    </a>
                </span>`
            )}
        </div>
        ${templateRender.if(
            props.searchBy,
            `<div class="mt-1">
                <input
                    type="text"
                    class="resource-table-search form-control"
                    placeholder="Search"
                    data-resource-search
                >
            </div>`
        )}
        </div>
        <table class="table resource-table" data-resource-table>
        </table>
        <div data-resource-pagination></div>
    `
        $('body main').html(html)
        return $(`#${props.resource}-data-table`)
    },
    renderLoader() {
        const height = 53 * (state.pageSize + 1)
        $dom.table.html(`
            <div class="flex-center" style="height: ${height}px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        `)
    },
    renderTable({ content = [], pagination = {} }) {
        const showEdit = props.actions.includes(resourceAction.edit)
        const showDelete = props.actions.includes(resourceAction.delete)

        const tableHtml = `
    <thead>
        <tr>
        <th>#</th>
        ${templateRender.list(
            props.columns,
            ({ name, displayName = name, sortable = false }) => `
                <th class="uc-first clickable" data-sort="${name}">
                <span>${displayName}<span>
                ${templateRender.if(
                    sortable,
                    '<span><i class="fa fa-sort" aria-hidden="true"></i></span>'
                )}
                </th>
            `
        )}
        ${templateRender.if(showEdit || showDelete, '<th>Actions</th>')}
        </tr>
    </thead>
    <tbody>
        ${templateRender.list(
            content,
            (item, index) => `
            <tr data-id="${item.id}">
                <td>${(pagination.page - 1) * pagination.size + index + 1}</td>
                ${templateRender.list(props.columns, ({ name, type }) =>
                    templateRender.switch(type, {
                        default: `<td data-name="${name}">${item[name]}</td>`,
                        photo: `
                            <td data-name="main_photo">
                                <img src="${item[name]}" alt="Photo not found" class="table-img">
                            </td>`
                    })
                )}
                ${templateRender.if(
                    showEdit || showDelete,
                    `<td class="flex resource-actions">
                        ${templateRender.if(
                            showEdit,
                            `
                            <a class="btn btn-primary white-txt mr-2 btn-sm"
                                href="/admin/${props.resource}/${item.id}/edit" 
                            >
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                            `
                        )}
                        ${templateRender.if(
                            showDelete,
                            `
                            <a class="btn btn-danger white-txt btn-sm"
                                data-delete="/admin/${props.resource}/${item.id}"
                            >
                                <i class="fa fa-trash-o text-white" aria-hidden="true"></i>
                            </a>
                            `
                        )}
                    </td>`
                )}
            </tr>`
        )}
    </tbody>
    `
        const pages = [
            ...Array(
                Math.ceil(pagination.totalElements / pagination.size)
            ).keys()
        ].map(page => page + 1)

        const paginationHtml = `
        <ul class="pagination">
            <li class="page-item${templateRender.if(
                pagination.page < 2,
                ' disabled'
            )}"
                data-page="${pagination.page - 1}"
            >
                <a class="page-link" href="#">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            ${templateRender.list(
                pages,
                page =>
                    `<li class="page-item${templateRender.if(
                        pagination.page === page,
                        ' active'
                    )}" data-page="${page}"><a class="page-link" href="#">${page}</a></li>`
            )}
            <li class="page-item${templateRender.if(
                pagination.page >= pages.length,
                ' disabled'
            )} " data-page="${pagination.page + 1}">
                <a class="page-link" href="#">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>`
        $dom.table.html(tableHtml)
        $dom.pagination.html(paginationHtml)
    },
    renderErrorTable() {
        $dom.pagination.html('')
        $dom.table.html(`
            <div class="alert alert-warning mt-3 text-center" role="alert">
                Error happend while loading ${props.resource}.
            </div>`)
    }
}

const cacheDom = () => {
    $dom.root = view.renderContainer()
    $dom.search = $dom.root.find('[data-resource-search]')
    $dom.table = $dom.root.find('[data-resource-table]')
    $dom.pagination = $dom.root.find('[data-resource-pagination]')
}

const bindSearchEvent = () => {
    $dom.search.on('keyup', debounce(handleSearch, 400))
}

const bindTableEvents = () => {
    $dom.table.find('[data-sort]').on('click', handleSort)
    $dom.table.find('[data-delete]').on('click', handleRecordDelete)
    $dom.pagination.find('[data-page]').on('click', handlePageChange)
}

// Load data procedure
const loadData = ({ page = 0, size = 10, ...rest } = {}) => {
    const baseUrl = '/admin/' + props.resource
    view.renderLoader()
    return http
        .get(baseUrl, { params: { page, size, ...rest } })
        .then(res => res.data)
        .then(({ data, current_page, per_page, total }) => {
            view.renderTable({
                content: data,
                pagination: {
                    page: current_page,
                    size: per_page,
                    totalElements: total
                }
            })
            bindTableEvents()
        })
        .catch(err => {
            console.log(err)
            view.renderErrorTable(err)
        })
}

// Event handlers
const handleSearch = event => {
    const { value } = event.target
    loadData({
        order: state.sort,
        [props.searchBy]: value
    })
}

const handleSort = event => {
    const { sort } = $(event.currentTarget).data()
    const [sortParam, sortOrder] = state.sort.split(',')
    const order =
        sortParam !== sort ? 'desc' : sortOrder === 'desc' ? 'asc' : 'desc'

    state.sort = `${sort},${order}`

    loadData({
        order: state.sort,
        page: $dom.pagination.find('.active').data().page,
        [props.searchBy]: $dom.search.val()
    })
}

const handleRecordDelete = event => {
    const $el = $(event.currentTarget)
    const endpoint = $el.data().delete
    if (!endpoint || !confirm('Are you sure?')) {
        return null
    }
    http.delete(endpoint)
        .then(() => {
            return loadData({
                order: state.sort,
                page: $dom.pagination.find('.active').data().page,
                [props.searchBy]: $dom.search.val()
            })
        })
        .catch(error => {
            console.log(error)
            alert('Error occured during delete!')
        })
}

const handlePageChange = event => {
    event.preventDefault()
    const { page } = $(event.currentTarget).data()
    loadData({
        page,
        order: state.sort,
        [props.searchBy]: $dom.search.val()
    })
}

const dataTable = {
    resourceAction,
    init(configProps) {
        props = { ...props, ...configProps }
        $(() => {
            cacheDom()
            loadData().then(bindSearchEvent)
        })
    }
}

export default dataTable
