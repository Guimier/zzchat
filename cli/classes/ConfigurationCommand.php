<?php

/** Check input HTML. */
class ConfigurationCommand extends Command
{

	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return array(
			'scenarios' => array(
				'show' => array(
					'parameters' => array( 'describe', 'explain' )
				),
				'set' => array(
					'parameters' => array( '+key', '+value' )
				),
				'unset' => array(
					'parameters' => array( '+key' )
				)
			),
			'parameters' => array(
				'describe' => array(
					'type' => 'boolean'
				),
				'explain' => array(
					'type' => 'boolean'
				),
				'key' => array(
					'type' => 'string'
				),
				'value' => array(
					'type' => 'string'
				)
			)
		) ;
	}

	protected function execute_show()
	{
		$conf = Configuration::getGlobalState() ;
		$describe = $this->getParameter( 'describe' ) ;
		$explain = $this->getParameter( 'explain' ) ;
		
		$context = $this->getContext() ;
		
		foreach ( $conf as $key => $values )
		{
			$this->writeln( $context->getMessage(
				'cli.configuration.key',
				array(
					'key' => $key,
					'type' => gettype( $values['default'] ),
					'val' => JSON::encode( $values['real'] )
				)
			) ) ;
			
			if ( $describe )
			{
				$this->writeln( "\t" . $context->getMessage( "configuration.$key" ) ) ;
			}
			
			if ( $explain && array_key_exists( 'local', $values ) )
			{
				$this->writeln( "\t" . $context->getMessage(
					'cli.configuration.key.compare',
					array(
						'default' => JSON::encode( $values['default'] ),
						'local' => JSON::encode( $values['local'] )
					)
				) ) ;
			}
		}
	}

	protected function execute_set()
	{
		$key = $this->getParameter( 'key' ) ;
		$value = JSON::decodeOrRaw( $this->getParameter( 'value' ) );
		
		if ( $value !== null )
		{
			Configuration::setValue( $key, $value ) ;
			Configuration::saveLocal() ;
		}
	}

	protected function execute_unset()
	{
		$key = $this->getParameter( 'key' ) ;
		Configuration::returnToDefault( $key ) ;
		Configuration::saveLocal() ;
	}

}

