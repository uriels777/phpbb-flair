<?php
/**
 *
 * Profile Flair. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, Steve Guidetti, https://github.com/stevotvr
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace stevotvr\flair\controller;

/**
 * Profile Flair user flair management ACP controller interface.
 */
interface acp_user_interface extends acp_base_interface
{
	/**
	 * Show the user search page.
	 */
	public function find_user();

	/**
	 * Handle the user_flair mode.
	 */
	public function edit_user_flair();
}