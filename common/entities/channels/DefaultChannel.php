<?php

/** Default channel.
 * @codeCoverageIgnore Simple override.
 */
class DefaultChannel extends Channel
{
	
	/** Constructor. */
	public function __construct()
	{
		parent::__construct( -1 ) ;
	}
	
	/** Get the title of the channel.
	 * 
	 * @return The title of the Channel instance.
	 */
	public function getTitle()
	{
		return Configuration::getMessage( 'channels.default.title' ) ;
	}
	
	/** Get the name of the channel.
	 * 
	 * @return The name of the Channel instance.
	 */
	public function getName()
	{
		return Configuration::getMessage( 'channels.default.name' ) ;
	}
	
	/** Get the type of the channel.
	 * 
	 * @return The type of the Channel instance.
	 * @codeCoverageIgnore Getter.
	 */
	public function getType()
	{
		return Configuration::getValue( 'channels.defaulttype' ) ;
	}
	
	/** Get the creator of the channel.
	 * 
	 * @return The name of the User who has created the channel.
	 * @codeCoverageIgnore Getter.
	 */
	public function getCreator()
	{
		return User::getById( -1 )  ;
	}
	
}

