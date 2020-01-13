import toastr from 'toastr'

const asyncEventHandler = fn => {
    let isLoading = false
    return event => {
        event.preventDefault()
        if (!isLoading) {
            isLoading = true
            return fn(event)
                .catch(err => {
                    toastr.error('Error occured during this action!')
                    return Promise.reject(err)
                })
                .finally(() => {
                    isLoading = false
                })
        }
    }
}

export default asyncEventHandler
