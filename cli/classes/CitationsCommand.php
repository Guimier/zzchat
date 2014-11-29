<?php

class CitationsCommand extends Command
{

	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return array(
			'desc' => 'cli.citations',
			'actions' => array( '', 'add', 'show' ),
			'optional' => array(
				'text' => array(
					'desc' => 'cli.citations.text',
					'type' => 'string'
				),
				'author' => array(
					'desc' => 'cli.citations.author',
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

