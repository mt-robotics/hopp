( function ( $, wp ) {
	let isSaving = false;

	function getTexts() {
		return window.hoppHeroMediaAdmin || {};
	}

	function getButtonLabel( mediaKind, hasSelection ) {
		const texts = getTexts();
		if ( mediaKind === 'image' ) {
			return hasSelection ? texts.replaceHeroImage : texts.chooseHeroImage;
		}
		if ( mediaKind === 'video' ) {
			return hasSelection ? texts.replaceHeroVideo : texts.chooseHeroVideo;
		}
		return hasSelection ? texts.replacePoster : texts.choosePoster;
	}

	function renderPreview( $field, attachment ) {
		const $preview = $field.find( '.hopp-hero-media-preview' );
		const emptyLabel = $preview.data( 'empty-label' ) || getTexts().noMediaSelected || 'No media selected yet.';

		if ( ! attachment || ! attachment.id ) {
			$preview.html( '<p style="margin: 0; color: #50575e;">' + emptyLabel + '</p>' );
			$field.find( '.hopp-hero-media-remove' ).addClass( 'hidden' );
			$field.find( '.hopp-hero-media-select' ).text( getButtonLabel( $field.data( 'media-kind' ), false ) );
			return;
		}

		let previewHtml = '';
		const title = attachment.title || attachment.filename || '';
		const filename = attachment.filename && attachment.filename !== title ? attachment.filename : '';
		const thumbUrl =
			attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url :
			attachment.sizes && attachment.sizes.full ? attachment.sizes.full.url :
			attachment.icon || '';

		if ( $field.data( 'media-kind' ) === 'video' && attachment.url ) {
			previewHtml += '<video controls muted playsinline preload="metadata" style="display: block; width: 100%; border-radius: 8px; margin-bottom: 8px;">';
			previewHtml += '<source src="' + attachment.url + '" type="' + ( attachment.mime || 'video/mp4' ) + '">';
			previewHtml += '</video>';
		} else if ( thumbUrl ) {
			previewHtml += '<img src="' + thumbUrl + '" alt="" style="display: block; width: 100%; border-radius: 8px; margin-bottom: 8px;">';
		}

		previewHtml += '<p style="margin: 0; font-weight: 600;">' + title + '</p>';
		if ( filename ) {
			previewHtml += '<p style="margin: 4px 0 0; color: #50575e;">' + filename + '</p>';
		}

		$preview.html( previewHtml );
		$field.find( '.hopp-hero-media-remove' ).removeClass( 'hidden' );
		$field.find( '.hopp-hero-media-select' ).text( getButtonLabel( $field.data( 'media-kind' ), true ) );
	}

	function getFrameConfig( mediaKind ) {
		const texts = getTexts();
		if ( mediaKind === 'image' ) {
			return {
				title: texts.chooseImage,
				button: { text: texts.useImage },
				library: { type: 'image' }
			};
		}
		if ( mediaKind === 'video' ) {
			return {
				title: texts.chooseVideo,
				button: { text: texts.useVideo },
				library: { type: 'video' }
			};
		}
		return {
			title: texts.choosePosterImage,
			button: { text: texts.usePosterImage },
			library: { type: 'image' }
		};
	}

	function updatePanelVisibility( $panel ) {
		const mode = $panel.find( '#hopp-hero-media-type' ).val();
		$panel.find( '.hopp-hero-media-section' ).each( function () {
			const $section = $( this );
			$section.toggle( $section.data( 'mode' ) === mode );
		} );
	}

	function syncHomeHeroVideoNotice( $panel ) {
		const texts = getTexts();
		const postId = Number( texts.postId || 0 );
		const homePageId = Number( texts.homePageId || 0 );
		const isHomePageEditor = postId > 0 && homePageId > 0 && postId === homePageId;
		const showNotice = isHomePageEditor && $panel.find( '#hopp-hero-media-type' ).val() === 'video';
		$( '.hopp-home-hero-video-note' ).toggle( showNotice );
	}

	function syncGenericHeroVideoNotice( $panel ) {
		const showNotice = $panel.find( '#hopp-hero-media-type' ).val() === 'video';
		$panel.find( '.hopp-hero-video-copy-note' ).toggle( showNotice );
		$( '.hopp-structured-hero-copy-note' ).toggle( showNotice );
	}

	function updateEditorState( metaUpdates, extraUpdates ) {
		if ( ! wp || ! wp.data || ! wp.data.select || ! wp.data.dispatch ) {
			return;
		}

		try {
			const currentMeta = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {};
			const payload = Object.assign(
				{},
				extraUpdates || {},
				{
					meta: Object.assign( {}, currentMeta, metaUpdates )
				}
			);

			wp.data.dispatch( 'core/editor' ).editPost( payload );
		} catch ( error ) {
			window.console && window.console.error && window.console.error( 'HOPP hero media state sync failed', error );
		}
	}

	function hideFeaturedImagePanel() {
		document.querySelectorAll( '.editor-post-featured-image' ).forEach( function ( element ) {
			const panel = element.closest( '.components-panel__body' );
			if ( panel ) {
				panel.style.display = 'none';
			} else {
				element.style.display = 'none';
			}
		} );
	}

	function getHeroPayload( $panel ) {
		return {
			hopp_hero_media_type: $panel.find( '#hopp-hero-media-type' ).val(),
			hopp_hero_image_id: Number( $panel.find( '#hopp-hero_image_id' ).val() || 0 ),
			hopp_hero_video_id: Number( $panel.find( '#hopp-hero_video_id' ).val() || 0 ),
			hopp_hero_video_poster_id: Number( $panel.find( '#hopp-hero_video_poster_id' ).val() || 0 ),
			hopp_hero_video_audio_mode: $panel.find( '#hopp-hero-video-audio-mode' ).val() || 'start_muted'
		};
	}

	function setPanelBusy( $panel, busy ) {
		isSaving = busy;
		$panel.find( 'button, select' ).prop( 'disabled', busy );
	}

	function ensureStatusNode( $panel ) {
		let $status = $panel.find( '.hopp-hero-media-status' );
		if ( ! $status.length ) {
			$status = $( '<p class="hopp-hero-media-status description" style="margin-top: 12px;"></p>' );
			$panel.append( $status );
		}
		return $status;
	}

	function saveHeroMedia( $panel ) {
		const texts = getTexts();
		const postId = Number( texts.postId || 0 );
		if ( ! postId || ! wp || ! wp.apiFetch ) {
			return Promise.resolve();
		}

		const meta = getHeroPayload( $panel );
		const featuredMedia = meta.hopp_hero_media_type === 'image' ? meta.hopp_hero_image_id : 0;
		const $status = ensureStatusNode( $panel );

		setPanelBusy( $panel, true );
		$status.text( texts.saving || 'Saving hero media…' );

		updateEditorState( meta, { featured_media: featuredMedia } );

		return wp.apiFetch( {
			path: ( texts.restPath || '/wp/v2/pages/' ) + postId,
			method: 'POST',
			data: {
				meta: meta,
				featured_media: featuredMedia
			}
		} )
			.then( function ( response ) {
				const savedMeta = response && response.meta ? response.meta : meta;
				const savedFeatured = response && typeof response.featured_media !== 'undefined' ? response.featured_media : featuredMedia;
				updateEditorState( savedMeta, { featured_media: savedFeatured } );
				$status.text( texts.saved || 'Hero media saved.' );
			} )
			.catch( function ( error ) {
				window.console && window.console.error && window.console.error( 'HOPP hero media save failed', error );
				$status.text( texts.saveError || 'Hero media could not be saved. Please refresh and try again.' );
			} )
			.finally( function () {
				setPanelBusy( $panel, false );
			} );
	}

	$( function () {
		const $panels = $( '.hopp-hero-media-panel' );
		if ( ! $panels.length || ! wp || ! wp.media ) {
			hideFeaturedImagePanel();
			return;
		}

		$panels.each( function () {
			const $panel = $( this );
			updatePanelVisibility( $panel );
			syncHomeHeroVideoNotice( $panel );
			syncGenericHeroVideoNotice( $panel );

			$panel.on( 'change', '#hopp-hero-media-type', function () {
				if ( isSaving ) {
					return;
				}
				updatePanelVisibility( $panel );
				syncHomeHeroVideoNotice( $panel );
				syncGenericHeroVideoNotice( $panel );
				saveHeroMedia( $panel );
			} );

			$panel.on( 'change', '#hopp-hero-video-audio-mode', function () {
				if ( isSaving ) {
					return;
				}
				saveHeroMedia( $panel );
			} );

			$panel.on( 'click', '.hopp-hero-media-select', function ( event ) {
				event.preventDefault();

				const $button = $( this );
				const $field = $button.closest( '.hopp-hero-media-field' );
				const $input = $( '#' + $button.data( 'target' ) );
				const mediaKind = $button.data( 'media-kind' );
				const frame = wp.media(
					Object.assign(
						{
							multiple: false
						},
						getFrameConfig( mediaKind )
					)
				);

				frame.on( 'select', function () {
					const selection = frame.state().get( 'selection' );
					const model = selection && selection.first ? selection.first() : null;
					if ( ! model ) {
						return;
					}

					const attachment = model.toJSON();
					const metaKey = $input.attr( 'name' );
					const editorUpdates = {};

					renderPreview( $field, attachment );
					$input.val( attachment.id );
					$input.trigger( 'change' );
					editorUpdates[ metaKey ] = attachment.id;

					saveHeroMedia( $panel );
				} );

				frame.open();
			} );

			$panel.on( 'click', '.hopp-hero-media-remove', function ( event ) {
				event.preventDefault();
				const $button = $( this );
				const $field = $button.closest( '.hopp-hero-media-field' );
				const $input = $( '#' + $button.data( 'target' ) );
				const mediaKind = $button.data( 'media-kind' );
				const metaKey = $input.attr( 'name' );
				const editorUpdates = {};

				renderPreview( $field, null );
				$input.val( '' );
				$input.trigger( 'change' );
				editorUpdates[ metaKey ] = 0;
				saveHeroMedia( $panel );
			} );
		} );

		hideFeaturedImagePanel();

		const observer = new MutationObserver( hideFeaturedImagePanel );
		observer.observe( document.body, { childList: true, subtree: true } );
	} );
} )( jQuery, window.wp );
