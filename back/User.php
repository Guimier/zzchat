<?php

class Users
{
	
/***** Class *****/
	
	public static function getActiveUser( Config $config, string $name )
	{
		$user = null ;
		
		$activeUsers = json_decode( file_get_contents( $config->getDataDir( 'users' ) . '/active.json' ), true ) ;
		
		if ( array_key_exists( $name, $activeUser ) )
		{
			$user = new User( $activeUser[$name] ) ;
			
			if ( ! $user->isActive() )
			{
				$user = null ;
			}
		}
		
		return $user ;
	}
	
	public static function getUser( int $userId )
	{
		static $users = array() ;
		
		$users[$id] = new User( $userId ) ; 
		
		return $users[$userId] ; 
	}
	
	
	 public static function getNamedUser( string $userName )
	{
		Configuration::getInstance() ;
		
		$this->name = $userName ;
		$user = getActiveUser( $userName ) ;
		
		if ( $user !== null )
		{
			$lastidFile = $this->config->getDataDir( 'users' ) . '/lastid.int' ;
			$this->id = 1 + (int) file_get_contents( $lastidFile ) ;
			file_put_contents( $lastidFile, $this->id ) ;
					
			$users = array() ;
			$users[$id] = new User( $this->id ) ;
			$users[$name] = $userName ;
			ret = $users[$name] ;
		}
		else
		{
			ret = null ;
		}
		
		return ret ;
	}
	

	public static function createUser( $userName)
	{
		$this->name = $userName ;
		
		getNamedUser( string $userName ) ;
		
		$lastidFile = $this->config->getDataDir( 'users' ) . '/lastid.int' ;
		$this->id = 1 + (int) file_get_contents( $lastidFile ) ;
		file_put_contents( $lastidFile, $this->id ) ;
		
		$this->userData = array(
			'name' => $userName,
			'lastactivity' => time(), 
			'active' => true
		) ;
		
		file_put_contents( $this->getUserFile(), $this->userData ) ;
	}

	
/**** Instances ****/

	private $instances = array( $userId => User )
	
	private $id = -1 ;
	
	private $name = null ;
	
	private $config = null ;
	
	private $userData = null ;
	
	public function __construct( int $userId )  
	{
		Configuration::getInstance() ;
	
		$this->id = $userId ;
		$raw = file_get_contents( $this->getUserFile() ) ;
		
		if ( $raw === null ) // err
		{
			throw new NoSuchUserException( $userId ) ;
		}
		else // charger données
		{
			$this->userData = json_decode( $raw, true ) ;
		}
	}
	
	private function getUserFile()
	{
		Configuration::getInstance() ;
		
		return $this->config->getDataDir( 'users' ) . '/' . $this->id . '.json' ;
	}
			
}
