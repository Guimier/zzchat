<?php

/** Run an ajax query in CLI. */
class AjaxCommand extends Command
{

	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return array() ;
	}

	/** See Command::execute. */
	protected function execute()
	{
		$query = new AjaxQuery( new PseudoWebContext( Context::getCanonical() ) ) ;
		$query->execute() ;
		$query->show() ;
	}

}

