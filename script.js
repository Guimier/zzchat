console.log( 'Up!' );

document.addEventListener(
	'keypress',
	function ( evt ) {

    if ( evt.which >=  97 && evt.which <= 122 && !evt.altKey && !evt.ctrlKey && !evt.ShiftKey && !evt.MetaKey ) {
			window.alert( 'Tu as tappé une lettre ! Malheureux ! Ferais-tu parti de l\'USB ?' );
		}
	}
);
