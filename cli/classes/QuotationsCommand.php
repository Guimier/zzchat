<?php

class QuotationsCommand extends Command
{

	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return array(
			'description' => 'cli.quotations',
			'scenarios' => array(
				'add' => array(
					'description' => 'cli.quotations.add',
					'parameters' => array( '+text', 'author' )
				),
				'show' => array(
					'description' => 'cli.quotations.show',
					'parameters' => array( 'id' )
				),
				'rm' => array(
					'description' => 'cli.quotations.rm',
					'parameters' => array( '+id' )
				)
			),
			'parameters' => array(
				'text' => array(
					'description' => 'cli.quotations.text',
					'type' => 'string'
				),
				'author' => array(
					'description' => 'cli.quotations.author',
					'type' => 'string'
				),
				'id' => array(
					'description' => 'cli.quotations.id',
					'type' => 'array'
				)
			)
		) ;
	}
	
	/** Add a quotation. */
	protected function execute_add()
	{
		$text = $this->getParameter( 'text' ) ;
		$author = $this->getParameter( 'author' ) ;
		
		$cits = new Quotations() ;
		$cits->add( $text, $author ) ;
	}
	
	/** Show a quotation.
	 * @param number $id Identifiant of the quotation.
	 * @param array $cit Representation of the quotation.
	 */
	private function showQuotation( $id, $cit )
	{
		$context = $this->getContext() ;
		
		$this->writeln(
			"$id] "
			. $context->getMessage(
				'quotations.quote',
				array( 'content' => $cit['text'] )
			)
			. ' '
			. $context->getMessage(
				$cit['author'] === null ? 'quotations.anonymous' : 'quotations.author',
				array( 'name' => $cit['author'] )
			)
		) ;
	}
	
	/** Show all or some quotations. */
	protected function execute_show()
	{
		$cits = new Quotations() ;
		$raw = $cits->getAll() ;
		$ids = $this->getParameter( 'id' ) ;
		
		if ( $ids === null )
		{
			foreach ( $raw as $id => $cit )
			{
				$this->showQuotation( $id, $cit ) ;
			}
		}
		else
		{
			foreach ( $ids as $id )
			{
				$this->showQuotation( $id, $cits->get( $id ) ) ;
			}
		}
	}
	
	/** Remove a quotation. */
	protected function execute_rm()
	{
		$cits = new Quotations() ;
		$ids = $this->getParameter( 'id' ) ;
		
		foreach ( $ids as $id )
		{
			$cits->remove( $id ) ;
		}
	}
	
}

