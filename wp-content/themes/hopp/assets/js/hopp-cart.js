/* HOPP — AJAX add-to-cart for single product pages */
(function () {
	'use strict';

	var form = document.querySelector( '.product-detail__cart form.cart' );
	if ( ! form ) return;

	form.addEventListener( 'submit', function ( e ) {
		e.preventDefault();

		var addBtn  = form.querySelector( '[name="add-to-cart"]' );
		var qtyEl   = form.querySelector( '[name="quantity"]' );
		var productId = addBtn ? addBtn.value : '';
		var quantity  = qtyEl  ? qtyEl.value  : '1';

		if ( ! productId ) {
			form.submit();
			return;
		}

		addBtn.disabled    = true;
		addBtn.dataset.orig = addBtn.textContent;
		addBtn.textContent  = '…';

		var body = new URLSearchParams();
		body.set( 'product_id', productId );
		body.set( 'quantity',   quantity );

		fetch( '/?wc-ajax=add_to_cart', {
			method:  'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body:    body.toString(),
		} )
			.then( function ( r ) { return r.json(); } )
			.then( function ( data ) {
				addBtn.disabled   = false;
				addBtn.textContent = addBtn.dataset.orig;

				if ( data.error ) {
					hopp_toast( data.error, 'error' );
				} else {
					hopp_toast(
						( addBtn.dataset.orig || 'Item' ) + ' added to your cart.',
						'success'
					);
					hopp_update_cart_count( parseInt( quantity, 10 ) || 1 );
				}
			} )
			.catch( function () {
				addBtn.disabled    = false;
				addBtn.textContent = addBtn.dataset.orig;
				hopp_toast( 'Something went wrong. Please try again.', 'error' );
			} );
	} );

	function hopp_update_cart_count( added ) {
		var el = document.querySelector( '.site-cart__count' );
		if ( ! el ) return;
		var current = parseInt( el.textContent, 10 ) || 0;
		el.textContent = current + added;
		el.classList.add( 'site-cart__count--visible' );
	}

	function hopp_toast( msg, type ) {
		var prev = document.querySelector( '.hopp-toast' );
		if ( prev ) prev.remove();

		var el = document.createElement( 'div' );
		el.className   = 'hopp-toast hopp-toast--' + ( type || 'success' );
		el.setAttribute( 'role', 'alert' );
		el.setAttribute( 'aria-live', 'polite' );
		el.textContent = msg;
		document.body.appendChild( el );

		requestAnimationFrame( function () {
			requestAnimationFrame( function () {
				el.classList.add( 'hopp-toast--visible' );
			} );
		} );

		setTimeout( function () {
			el.classList.remove( 'hopp-toast--visible' );
			setTimeout( function () { el.remove(); }, 300 );
		}, 4000 );
	}
} )();
