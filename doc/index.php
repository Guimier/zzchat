<?php
/*
 * Index of documentation.
 */

$code = array(
	'title' => 'Code',
	'contents' => array()
) ;

$display = array() ;
$display[] = &$code ;

function addToDisplay( &$section, $dir, $label )
{
	if ( file_exists( __DIR__ . '/' . $dir ) )
	{
		$section['content'][] = array(
			'href' => './' . $dir,
			'label' => $label
		) ;
	}
}

addToDisplay( $code, 'code', 'PHP code' ) ;
addToDisplay( $code, 'coverage', 'PHP test coverage' ) ;

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Agora documentation</title>
		<meta charset="UTF-8" />
	</head>
	<body>
		<h1>Agora — documentation</h1>
<?php
	
$empty = true ;	

foreach ( $display as $section )
{
	if ( count( $section['content'] ) > 0 )
	{
		$empty = false ;
		
		echo '<h2>' .  htmlspecialchars( $section['title'] ) . '</h2><ul>' ;
		
		foreach ( $section['content'] as $elem )
		{
			echo '<li><a href="'
				/* Suppose nobody uses " in directories names */
				. htmlspecialchars( $elem['href'] ) . '">'
				. htmlspecialchars( $elem['label'] )
				. '</a></li>' ;
		}
		
		echo '</ul>' ;
	}
	
	if ( $empty )
	{
		echo '<p><em>No documentation found. Run <code>ant</code> to generate all documentation</em></p>' ;
	}
}

?>
	</body>
</html>
