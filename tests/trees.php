<?php
// @codeCoverageIgnoreStart

/** From http://php.net/rmdir#110489. */
function rmTree( $dir ) {
	$files = array_diff( scandir( $dir ), array( '.', '..' ) );
	foreach ( $files as $file )
	{
		if ( is_dir( "$dir/$file" ) )
		{
			rmTree( "$dir/$file" ) ;
		}
		else
		{
			unlink( "$dir/$file" );
		}
	}
	return rmdir( $dir );
}

/** From http://php.net/manual/en/function.copy.php#91010. */
function cpTree( $src, $dst ) {
	// file_put_contents( '/dev/tty', JSON::encode( array( $src, $dst ) ) . "\n" ) ;
	$dir = opendir( $src );
	@mkdir( $dst );
	while ( false !== ( $file = readdir( $dir ) ) )
	{
		if ( ( $file != '.' ) && ( $file != '..' ) )
		{
			if ( is_dir( "$src/$file" ) )
			{
				cpTree( "$src/$file", "$dst/$file" );
			}
			else {
				copy( "$src/$file", "$dst/$file" );
			}
		}
	}
	closedir( $dir );
} 
