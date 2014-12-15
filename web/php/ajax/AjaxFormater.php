<?php

/** Format data for Ajax. */
class AjaxFormater
{

	/** Format data about a channel.
	 * @param Channel $channel The channel.
	 */
	public static function channel( Channel $channel )
	{
		return array(
			'id' => $channel->getId(),
			'name' => $channel->getName(),
			'title' => $channel->getTitle(),
			'type' => $channel->getType(),
			'users' => self::users( $channel->getUsers() )
		) ;
	}

	/** Format data about multiple users.
	 * @param array $user The users.
	 */
	public static function users( array $users )
	{
		return array_map( 'AjaxFormater::user', $users ) ;
	}

	/** Format data about a user.
	 * @param User $user The user.
	 */
	public static function user( User $user )
	{
		return array(
			'id' => $user->getId(),
			'name' => $user->getName()
		) ;
	}

	/** Format data about a post.
	 * @param Post $post The post.
	 */
	public static function post( Post $post )
	{
		return array(
			'owner' => self::user( $post->getOwner() ),
			'date' => $post->getDate(),
			'content' => $post->getContent()
		) ;
	}

	/** Format data about multiple posts.
	 * @param array $user The posts.
	 */
	public static function posts( array $posts )
	{
		return array_map( 'AjaxFormater::post', $posts ) ;
	}

}
