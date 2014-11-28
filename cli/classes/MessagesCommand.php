<?php

/** Show all messages for a given language. */
class MessagesCommand extends Command
{

	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return array(
			'desc' => 'cli.messages',
			'optional' => array(
				'defaults' => array(
					'desc' => 'cli.messages.defaults',
					'type' => 'boolean',
					'alt' => 'd'
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

