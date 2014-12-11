<?php

/** Check input HTML. */
class ConfigurationCommand extends Command
{

	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return array(
			'description' => 'cli.configuration',
			'scenarios' => array(
				'show' => array(
					'description' => 'cli.configuration.show',
					'parameters' => array( 'describe', 'explain' )
				),
				'set' => array(
					'description' => 'cli.configuration.set',
					'parameters' => array( '+key', '+value' )
				),
				'unset' => array(
					'description' => 'cli.configuration.unset',
					'parameters' => array( '+key' )
				)
			),
			'parameters' => array(
				'describe' => array(
					'description' => 'cli.configuration.param.describe',
					'type' => 'boolean'
				),
				'explain' => array(
					'description' => 'cli.configuration.param.explain',
					'type' => 'boolean'
				),
				'key' => array(
					'description' => 'cli.configuration.param.key',
					'type' => 'string'
				),
				'value' => array(
					'description' => 'cli.configuration.param.value',
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
				$this->writeln( "\t" . $context->getMessage( "cli.configuration.keys.$key" ) ) ;
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
		$value = JSON::decode( $this->getParameter( 'value' ) );
		
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

