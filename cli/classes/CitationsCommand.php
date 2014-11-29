<?php

class CitationsCommand extends Command
{

	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return array(
			'description' => 'cli.citations',
			'scenarios' => array(
				'add' => array(
					'description' => 'cli.citation.add',
					'parameters' => array( 'text', 'author' )
				),
				'show' => array(
					'description' => 'cli.citation.show',
					'parameters' => array()
				)
			),
			'parameters' => array(
				'text' => array(
					'required' => true,
					'description' => 'cli.citations.text',
					'type' => 'string'
				),
				'author' => array(
					'required' => false,
					'description' => 'cli.citations.author',
					'type' => 'string'
				)
			)
		) ;
	}

	/** See Command::execute. */
	protected function execute()
	{
		$context = $this->getContext() ;
		$defaults = $context->getBooleanParameter( 'defaults', 'd' ) ;
		$list = Languages::getInstance()->getAllMessages(
			$context->getLanguage(),
			$defaults
		) ;

		$this->writeln( json_encode( $list ) ) ;
	}

}

