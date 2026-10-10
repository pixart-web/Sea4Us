/* Sea4Us dynamic blocks: generic editor UI (no build step). Server-side preview + sidebar fields. */
( function ( wp, defs ) {
	if ( ! wp || ! defs ) {
		return;
	}
	var el = wp.element.createElement;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var MediaUpload = wp.blockEditor.MediaUpload;
	var C = wp.components;
	var SSR = wp.serverSideRender;

	Object.keys( defs ).forEach( function ( name ) {
		var def = defs[ name ];
		wp.blocks.registerBlockType( 'sea4us/' + name, {
			apiVersion: 3,
			title: def.title,
			category: 'sea4us',
			icon: 'admin-site-alt3',
			attributes: def.attributes,
			edit: function ( props ) {
				var controls = Object.keys( def.attributes ).map( function ( key ) {
					var a = def.attributes[ key ];
					var val = props.attributes[ key ];
					var set = function ( v ) {
						var o = {};
						o[ key ] = v;
						props.setAttributes( o );
					};
					if ( a.type === 'boolean' ) {
						return el( C.ToggleControl, { key: key, label: key, checked: !! val, onChange: set } );
					}
					if ( a.type === 'integer' && /imageId$/i.test( key ) ) {
						return el( MediaUpload, {
							key: key,
							onSelect: function ( m ) { set( m.id ); },
							allowedTypes: [ 'image' ],
							value: val,
							render: function ( o ) {
								return el( C.Button, { variant: 'secondary', onClick: o.open }, key + ( val ? ' #' + val : '' ) );
							},
						} );
					}
					if ( a.type === 'integer' ) {
						return el( C.TextControl, { key: key, type: 'number', label: key, value: val, onChange: function ( v ) { set( parseInt( v, 10 ) || 0 ); } } );
					}
					return el( C.TextControl, { key: key, label: key, value: val, onChange: set } );
				} );
				return el(
					'div',
					useBlockProps(),
					el( InspectorControls, {}, el( C.PanelBody, { title: def.title }, controls ) ),
					el( SSR, { block: 'sea4us/' + name, attributes: props.attributes, urlQueryArgs: { post_id: wp.data.select( 'core/editor' ) ? wp.data.select( 'core/editor' ).getCurrentPostId() : 0 } } )
				);
			},
			save: function () {
				return null;
			},
		} );
	} );
} )( window.wp, window.S4U_BLOCKS );
