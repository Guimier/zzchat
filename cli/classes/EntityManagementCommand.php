<?php

/** Manage entities. */
abstract class EntityManagementCommand extends Command
{
	
	/** Get the entity class name.
	 * @return The class name as a string.
	 */
	abstract protected function getEntityClass() ;

	/** Merge partial child documentation with parent one.
	 * @param array $exten Extension of the parent documentation
	 *    * Values in `scenarios` array will be used as scenarios descriptions.
	 *    * Value at `description` will be used as global description.
	 */
	protected function buildDocumentation( array $exten )
	{
		return array(
			'description' => $exten['description'],
			'scenarios' => array(
				'eject' => array(
					'description' => $exten['scenarios']['eject'],
					'parameters' => array( '+id' )
				),
				'show' => array(
					'description' => $exten['scenarios']['show'],
					'parameters' => array()
				)
			),
			'parameters' => array(
				'id' => array(
					'description' => 'cli.entities.id',
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
			$entity = $class::getEntity( $id ) ;
			$entity->isNowInactive() ;
		}
	}
	
	/** Show entities. */
	protected function execute_show()
	{
		$class = $this->getEntityClass() ;
		$list = $class::getActiveEntities() ;
		
		foreach ( $list as $name => $id )
		{
			$this->writeln( "$id] $name" ) ;
		}
	}

}

