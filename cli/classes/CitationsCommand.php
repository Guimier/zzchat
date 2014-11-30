<?php

class CitationsCommand extends Command
{

	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return array(
			'description' => 'cli.citations',
			'scenarios' => array(
				'add' => array(
					'description' => 'cli.citation.add',
					'parameters' => array( 'text', 'author' )
				),
				'show' => array(
					'description' => 'cli.citation.show',
					'parameters' => array()
				),
				'rm' => array(
					'description' => 'cli.citation.rm',
					'parameters' => array( 'id' )
				)
			),
			'parameters' => array(
				'text' => array(
					'required' => true,
					'description' => 'cli.citations.text',
					'type' => 'string'
				),
				'author' => array(
					'required' => false,
					'description' => 'cli.citations.author',
					'type' => 'string'
				),
				'id' => array(
					'required' => true,
					'description' => 'cli.citations.id',
					'type' => 'string'
				)
			)
		) ;
	}
	
	/** Add a citation */
	protected function execute_add()
	{
		$text = $this->getContext()->getParameter( 'text' ) ;
		$author = $this->getContext()->getParameter( 'author' ) ;
		
		if ( $text === null )
		{
			throw new CliMissingParameterException( 'text' ) ;
		}
		
		$cits = new Citations() ;
		$cits->add( $text, $author ) ;
	}
	
	/** Show all citations */
	protected function execute_show()
	{
		$cits = new Citations() ;
		$raw = $cits->getAll() ;
		
		foreach ( $raw as $i => $cit )
		{
			$this->writeln(
				"$i] "
				. $this->getContext()->getMessage(
					'citations.quotation',
					array( 'content' => $cit['text'] )
				)
				. ' — '
				. ( $cit['author'] === null
					? $this->getContext()->getMessage( 'citations.anonymous' )
					: $cit['author']
				)
			) ;
		}
	}
	
}

