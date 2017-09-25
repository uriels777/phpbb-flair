<?php
/**
 *
 * Profile Flair. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, Steve Guidetti, https://github.com/stevotvr
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace stevotvr\flair\operator;

use stevotvr\flair\exception\out_of_bounds;
use stevotvr\flair\exception\unexpected_value;

/**
 * Profile Flair flair trigger operator.
 */
class trigger extends operator implements trigger_interface
{
	public function get_triggera($trigger_name)
	{
		return $this->get_trigger_rows("trig_name = '" . $this->db->sql_escape($trigger_name) . "'");
	}

	public function get_flair_triggers($flair_id)
	{
		return $this->get_trigger_rows('flair_id = ' . (int) $flair_id);
	}

	/**
	 * Get a list of trigger entities from the database.
	 *
	 * @param string $where The WHERE clause of the database query
	 *
	 * @return array An associative array of trigger names to values
	 */
	protected function get_trigger_rows($where)
	{
		$triggers = array();

		$sql = 'SELECT *
				FROM ' . $this->trigger_table . '
				WHERE ' . $where;
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$triggers[$row['trig_name']] = (int) $row['trig_value'];
		}
		$this->db->sql_freeresult($result);

		return $triggers;
	}

	public function set_trigger($flair_id, $trigger_name, $trigger_value)
	{
		if ($trigger_value < 0)
		{
			throw new out_of_bounds('trig_value');
		}

		if (!preg_match('/^[a-z_]+$/', $trigger_name))
		{
			throw new unexpected_value('trig_name', 'BAD_TRIG_NAME');
		}

		$this->unset_trigger($flair_id, $trigger_name);

		if (!$trigger_value)
		{
			return;
		}

		$sql_ary = array(
			'flair_id'		=> (int) $flair_id,
			'trig_name'		=> $trigger_name,
			'trig_value'	=> (int) $trigger_value,
		);
		$sql = 'INSERT INTO ' . $this->trigger_table . '
				' . $this->db->sql_build_array('INSERT', $sql_ary);
		$this->db->sql_query($sql);
	}

	public function unset_trigger($flair_id, $trigger_name)
	{
		$sql = 'DELETE FROM ' . $this->trigger_table . '
				WHERE flair_id = ' . (int) $flair_id . "
					AND trig_name = '" . $this->db->sql_escape($trigger_name) . "'";
		$this->db->sql_query($sql);
	}
}
