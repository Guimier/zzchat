<?php

/** Manage entities. */
abstract class EntityManagementCommand extends Command
{
	
	/** Get the entity class name.
	 * @return The class name as a string.
	 */
	abstract protected function getEntityClass() ;

	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return array(
			'scenarios' => array(
				'eject' => array(
					'parameters' => array( '+id' )
				),
				'show' => array(
					'parameters' => array()
				)
			),
			'parameters' => array(
				'id' => array(
					'description' => 'cli.entities.parameter.id',
					'type' => 'array'
				)
			)
		) ;
	}

	/** Eject an entity. */
	protected function execute_eject()
	{
		$class = $this->getEntityClass() ;
		$ids = $this->getParameter( 'id' ) ;
		
		foreach ( $ids as $id )
		{
			$entity = $class::getById( $id ) ;
			$entity->eject() ;
		}
	}
	
	/** Show entities. */
	protected function execute_show()
	{
		$class = $this->getEntityClass() ;
		$list = $class::getAllActive() ;
		
		foreach ( $list as $entity )
		{
			$this->writeln( $entity->getId() . '] ' . $entity->getName() ) ;
		}
	}

}

