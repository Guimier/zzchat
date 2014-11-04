<?php

class Users
{
	
/***** Class *****/
	
	public static function getActiveUser( Context $context, string $name )
	{
		$user = null ;
		
		$activeUsers = json_decode( file_get_contents( $c->getDataDir( 'users' ) . '/active.json' ), true ) ;
		
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
	
/**** Instances ****/
	
	private $id = -1 ;
	
	private $name = null ;
	
	private $context = null ;
	
	private $userData = null ;
	
	public function __construct( Context $context, $pointer )  
	{
		$this->context = $context ;
		
		switch( gettype( $pointer ) )
		{
			
			case "integer" :
			
				$this->id = $pointer ;
				$raw = file_get_contents( $this->getUserFile() ) ;
				
				if ( $raw === null ) // err
				{
					throw new NoSuchUserException( $pointer ) ;
				}
				else // charger données
				{
					$this->userData = json_decode( $raw, true ) ;
				}
			
			break ;
			
			case "string" :
			
				$this->name = $pointer ;
				
				file_put_contents( $this->getUserFile() ) ;
				
				$lastidFile = $this->context->getDataDir( 'users' ) . '/lastid.int' ;
				$this->id = 1 + (int) file_get_contents( $lastidFile ) ;
				file_put_contents( $lastidFile, $this->id ) ;
				
				$this->userData = array(
					'name' => $pointer,
					'lastactivity' => time(), 
					'active' => true
				) ;
				
			break ;
			
			
			default :
				throw BadCallException() ; 
		}		
	}
	
	private function getUserFile()
	{
			return $this->context->getDataDir( 'users' ) . '/' . $this->id . '.json' ;
	}
			
}
