<?php

abstract class Entity
{
	/** Get the name of the directory of the object. 
	 * 
	 * @return The name of the directory.
	 */
	abstract protected static function getEntityType() ;
	
/***** Class *****/

	/** Get an active entity by name.
	 * 
	 * @param string $name The name to look for.
	 * 
	 * @return The Entity instance or null.
	 */
	public static function getActiveEntity( $name )
	{
		$entity = null ;
		
		$config = Configuration::getInstance() ;
		
		$activeEntities = $config->loadJson( $config->getDataDir( self::getEntityType() ) . '/active.json', array() ) ;
		
		if ( array_key_exists( $name, $activeEntities ) )
		{
			$user = self::getUser( $activeEntities[$name]->getId() ) ;

			if ( ! $entity->isActive() )
			{
				$entity = null ;
			}
		}
		
		return $entity ;
	}
	
	/** Get active entities.
	 *  
	 * @return The list of the entities which are active.
	 */
	public static function getActiveEntities() 
	{
		$config = Configuration::getInstance() ;
		
		$activeEntities = $config->loadJson( $config->getDataDir( self::getEntityType() ) . '/active.json', array() ) ;
		
		return $activeEntities ;
	}
	
	/** Get an entity by id.
	 * 
	 * @param int $entityId The id to look for.
	 * 
	 * @return The Entity instance.
	 */
	
	public static function getEntity( $entityId )
	{
		static $entities = array() ;
		
		if ( ! array_key_exists( $entityId, $entities ) )
		{
			$entities[$entityId] = new Entity( $entityId ) ; 
		}
		
		return $entities[$entityId] ; 
	}
	
	/** Create a entity.
	 * 
	 * @param string $entityName The name of the entity which is created.
	 * 
	 * @throw EntityNameTooShortException If the name is too short.
	 * @throw EntityNameAlreadyTakenException If the name is already in use.
	 * 
	 * @return The Entity instance.
	 */
	protected static function createEntity( $name, $initialArray )
	{
		$config = Configuration::getInstance() ; 
		
		if ( self::containsIllegalCharacter( $name ) )
		{
			throw new ContainsIllegalCharacterException( $name ) ;
		}
		
		
		
		if ( strlen( $name ) < $config->getValue( self::getEntityType() . '.minnamelength' ) )
		{
			throw new EntityNameTooShortException( $name ) ;
		}
		
		if ( strlen( $name ) > $config->getValue( self::getEntityType() . '.maxnamelenght') )
		{
			throw new EntityNameTooLongException( $name ) ;
		}		
		 
		
		if ( self::getActiveEntity( $name ) !== null )
		{
			throw new EntityNameAlreadyTakenException( $name ) ;
		}
		
		$lastidFile = $config->getDataDir( self::getEntityType() ) . '/lastid.int' ;
		$id = $config->incrementCounter( $lastIdFile ) ;
		
		$data = array( 'name' => $name ) ;
		$data += $initialArray ;
		
		$config->saveJson(
			self::getEntitiesFile( $id ),
			$data
		) ;
		
		$activeEntitiesFile = $config->getDataDir( self::getEntityType() ) . '/active.json' ;
		$activeEntities = $config->loadJson( $activeEntitiesFile, array() ) ;
		
		$activeEntities[] = array(
							'name' => $name,
							'id' => $id
		) ;
		$config->saveJson( $activeEntitiesFile, $activeEntities ) ;
		
		
		return self::getEntity( $id ) ;
	}
	
	/** Get the file of the entity by id.
	 * @param int $entityId The id of the entity whose file is searched.  
	 * 
	 * @return The File of the entity.
	 */
	private static function getEntityFile( $entityId )
	{
		return Configuration::getInstance()->getDataDir( self::getEntityType ) . '/' . $entityId . '.json' ;
	}
	
	
/***** Instances *****/
	
	/** The id of the entity. */
	private $id = -1 ;
	
	/** The array which contains the data concerning the entity. */
	private $data = null ;
	
	/** The data have been edited */
	private $modified = false ;
	
	/** Constructor.
	 * 
	 * @param int $entityId The id of the Entity which is built.
	 * 
	 * @throw NoSuchEntityException If there is no entity with this id.
	 */
	public function __construct( $entityId )  
	{
		$this->id = $entityId ;
		$this->data = Configuration::getInstance()->loadJson(
			$this->getEntityFile( $entityId )
		) ;
		
		if ( $this->Data === null )
		{
			throw new NoSuchEntityException( $entityId ) ;
		}
	}
	
	/** Destructor.
	 * Save the data if modified.
	 */
	public function __destruct()  
	{
		if ( $this->modified )
		{
			Configuration::getInstance()->saveJson(
				$this->getEntityFile( $this->id ),
				$this->data
			) ;
		}
	}
	
	/** Change a key in the data array.
	 * @param string $key The key to change.
	 * @param mixed $value The new value.
	 */
	private function setValue( $key, $value )
	{
		$this->data[$key] = $value ;
		$this->modified = true ;
	}
	
	/** Get a key in the data array.
	 * @param string $key The key to change.
	 * @param mixed $value The new value.
	 */
	private function getValue( $key )
	{
		return $this->data[$key] ;
	}
	
	/** Check whether the entity is active or not.
	 * 
	 * @return True if the entity is active, false otherwise. 
	 */
	public function isActive()
	{
		$config = Configuration::getInstance() ;
		return time() - $this->data['last-action'] < $config->getValue( self::getEntityType() . '.inactivity' ) ;	
	}
	
	/** Put an entity inactive
	 *  
	 * @throw EntityAlreadyInactiveException If the entity is already inactive. 
	 */
	public function isNowInactive()
	{
		if ( ! $this->isActive() )
		{
			throw new EntityAlreadyInactiveException( $this->id ) ;
		}
		else
		{
			$this->setValue( 'logged-out', true ) ;
			
			$config = Configuration::getInstance() ;
			$activeFile = $config->getDataDir( self::getEntityType() ) . '/active.json' ;
			
			$activeEntities = $config->loadJson( $activeFile, array() ) ;
			
			unset ( $activeEntities[$this->data['name']] ) ;
			
			$config->saveJson( $activeFile, $activeEntities) ;
		}
	}
	
	/** Remember the entity is active just now. */
	public function isActiveNow()
	{
		$this->setValue( 'last-action', time() ) ;
	}
	
	/** Get the id of the entity.
	 * 
	 * @return The id of the Entity instance.
	 * @codeCoverageIgnore
	 */ 
	public function getId()
	{
		return $this->id ;
	}
	
	
	/** Get the name of the entity.
	 * 
	 * @return The name of the Entity instance.
	 * @codeCoverageIgnore
	 */ 
	public function getName()
	{
		return $this->data['name'] ;
	}
	
	/** Send error if the string contains illegal characters.
	 * 
	 * @param string $name
	 * 
	 * @return bool true: there is illegal characters, false otherwise.
	 */ 
	 public function containsIllegalCharacter( $name )
	 {
		 return ! preg_match( '#^[A-ZÉÈÊÀÙÂÎÔÛÏËÜÖÇa-zéèêàùâîôûïëüöç\' -]*$#', $name ) ;
	 }
	 
}
