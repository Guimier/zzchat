<?php

/** Show all messages for a given language. */
class MessagesCommand extends Command
{

	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return array(
			'parameters' => array(
				'defaults' => array(
					'type' => 'boolean',
					'alt' => 'd'
				)
			)
		) ;
	}

	/** See Command::execute. */
	public function execute()
	{
		$defaults = $this->getParameter( 'defaults' ) ;
		$list = Languages::getInstance()->getAllMessages(
			$this->getContext()->getLanguage(),
			$defaults
		) ;

		$this->writeln( JSON::encode( $list ) ) ;
	}

}

