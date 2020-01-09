import Dropzone from 'dropzone'
import 'dropzone/dist/min/dropzone.min.css'

Dropzone.autoDiscover = false

export default (() => {

	const MAX_FILE_SIZE = 2 // MB
	const endpoint = window.location.origin + '/admin/upload/photo'

	const state = {
		zones: [],
		galleries: []
	}

	const $dom = {}

	function _cacheDom () {
		$dom.preview = $('.my-preview')
		$dom.zones = $('.dz')
		$dom.zoneGalleries = $('.dz-gallery')
	}

	function _bindEvent () {
		$dom.preview.on('click', event => {
			$(event.target).parent().trigger('click')
		})
	}

	function _initZones	() {
		_initPhotoFields()
		_initGalleryFields()
	}

	function _initPhotoFields () {
		$dom.zones.each((index, dzElement) => {
			const selector = '#' + $(dzElement).attr('id')
			const zone = new Dropzone(selector, { 
					url: endpoint,
					previewTemplate: _preview(),
					maxFilesize: MAX_FILE_SIZE,
					init () {
						const $zone = $(selector)
						this.on('sending', function (file, xhr, formData) {
							$zone.find('span.text').text('')
							formData.append('_token', document.head.querySelector('meta[name="csrf-token"]').content)
	          			}),
						this.on('success', function (file, response) {
							$zone.find('.my-preview img').attr('src', response)
							$zone.prev().val(response)
						}),
						this.on('error', function (file, error) {
							this.removeAllFiles(true)
							$zone.find('span.text').text('Error while uploading photo!')
						})
					}
				}
			)
			state.zones.push({
				id: selector,
				zone
			})
		})
	}

	function _initGalleryFields () {
		$dom.zoneGalleries.each((index, dzElement) => {
			const selector = '#' + $(dzElement).attr('id')
			const gallery = new Dropzone(selector, {
				url: endpoint,
				maxFilesize: MAX_FILE_SIZE,
				init () {
					const $gallery = $(selector)
					const inputName = $gallery.data().name
					this.on('sending', function (file, xhr, formData) {
						formData.append('_token', document.head.querySelector('meta[name="csrf-token"]').content)
					}),
					this.on('success', function (file, response) {
						$gallery.append(`
							<input type="hidden" name="${inputName}" value="${response}">
						`)
					})
				}
			})
			state.galleries.push({
				id: selector,
				gallery 
			})
		})
	}

	const _preview = () => `
		<div class="preview hidden">
			<img class="hidden img-fluid" data-dz-thumbnail />
			<div>
				<div class="hidden"><span data-dz-name></span></div>
				<div class="hidden" data-dz-size></div>
			</div>
			<div class="hidden"><span data-dz-uploadprogress></span></div>
			<div class="hidden"><span>✔</span></div>
			<div class="hidden"><span>✘</span></div>
			<div class="hidden"><span data-dz-errormessage></span></div>
		</div>
	`

	return {
		init () {
			_cacheDom()
			_bindEvent()
			_initZones()
		}
	}
})()