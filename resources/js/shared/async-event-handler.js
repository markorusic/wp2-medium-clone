import toastr from 'toastr'

const asyncEventHandler = fn => {
    let isLoading = false
    return event => {
        event.preventDefault()
        if (!isLoading) {
            isLoading = true
            return fn(event)
                .catch(error => {
                    if (error.response.status === 422) {
                        toastr.error(error.response.data.message)
                    } else {
                        toastr.error('Error occured during this action!')
                    }
                    return Promise.reject(error)
                })
                .finally(() => {
                    isLoading = false
                })
        }
    }
}

export default asyncEventHandler
