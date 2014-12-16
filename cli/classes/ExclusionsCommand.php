<?php

class ExclusionsCommand extends Command
{

	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return array(
			'scenarios' => array(
				'add' => array(
					'parameters' => array( '+name' )
				),
				'show' => array(
					'parameters' => array( 'id' )
				),
				'rm' => array(
					'parameters' => array( '+id' )
				),
				'check' => array(
					'parameters' => array( '+name' )
				)
			),
			'parameters' => array(
				'name' => array(
					'type' => 'string'
				),
				'id' => array(
					'type' => 'array'
				)
			)
		) ;
	}
	
	/** Add an exclusion. */
	protected function execute_add()
	{
		$name = $this->getParameter( 'name' ) ;		
		
		$exclusions = new NameExclusions() ;
		$exclusions->add( $name ) ;
	}
	
	/** Add a name. */
	protected function execute_check()
	{
		$name = $this->getParameter( 'name' ) ;		
		
		$exclusions = new NameExclusions() ;
		$exclusions->checkName( $name ) ;
	}
	
	/** Show an exclusion.
	 * @param number $id Identifiant of the name exclusion.
	 * @param array $exclusion Representation of the name exclusion.
	 */
	private function showExclusion( $id, $exclusion )
	{
		$context = $this->getContext() ;
		
		$this->writeln( "$id] $exclusion ") ;
	}
	
	/** Show all or some name exclusions. */
	protected function execute_show()
	{
		$exclusions = new NameExclusions() ;
		$raw = $exclusions->getAll() ;
		$ids = $this->getParameter( 'id' ) ;
		
		if ( $ids === null )
		{
			foreach ( $raw as $id => $exclusion )
			{
				$this->showExclusion( $id, $exclusion ) ;
			}
		}
		else
		{
			foreach ( $ids as $id )
			{
				$this->showExclusion( $id, $excluisons->get( $id ) ) ;
			}
		}
	}
	
	/** Remove an exclusion. */
	protected function execute_rm()
	{
		$exclusions = new NameExclusions() ;
		$ids = $this->getParameter( 'id' ) ;
		
		foreach ( $ids as $id )
		{
			$exclusions->remove( $id ) ;
		}
	}
	
}


