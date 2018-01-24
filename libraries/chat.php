<?php

namespace libraries;

use \system\Database;

class Chat {

	private $db;

	public function __construct() {
		
		$this->db = Database::connection();
		
	}

	public function operator_update_status($operator_id, $operator_timeout) {

		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);

		if ($this->db->count('SELECT operator_id FROM ' . config_item('database', 'table_prefix') . 'operators WHERE operator_id = :operator_id', $parameters) > 0) {

			$result = $this->db->get_field(config_item('database', 'table_prefix') . 'operators', 'last_activity', 'operator_id = :operator_id', $parameters);

			$last_activity = ((time() - $result['last_activity']) > $operator_timeout) ? time() : 0;

			$parameters = array(
				'last_activity'	=> $last_activity,
				'operator_id'	=> sanitize($operator_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'operators', 'last_activity = :last_activity', 'operator_id = :operator_id', $parameters);

			$data = array(
				'success' => true
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;

	}

	public function operator_get_status($operator_id, $operator_timeout) {

		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);
		
		$result = $this->db->get_field(config_item('database', 'table_prefix') . 'operators', 'last_activity', 'operator_id = :operator_id', $parameters);

		if ((time() - $result['last_activity']) > $operator_timeout) {

			$parameters = array(
				'last_activity'	=> 0,
				'operator_id'	=> sanitize($operator_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'operators', 'last_activity = :last_activity', 'operator_id = :operator_id', $parameters);

			$data = array(
				'success' => false
			);

		} else {

			$data = array(
				'success' => true
			);

		}
		
		return $data;

	}
	
	public function operator_get_pending_chats($operator_id, $chat_timeout) {

		$this->db->update(config_item('database', 'table_prefix') . 'chats', 'chat_status = 3, time_end = UNIX_TIMESTAMP(), last_activity = UNIX_TIMESTAMP()', 'time_end = 0 AND last_activity < (UNIX_TIMESTAMP() - ' . $chat_timeout . ')');

		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);
		
		$sql = 'SELECT *, c.last_activity AS last_activity FROM ' . config_item('database', 'table_prefix') . 'chats c JOIN ' . config_item('database', 'table_prefix') . 'department_operators do ON c.department_id = do.department_id JOIN ' . config_item('database', 'table_prefix') . 'operators o ON do.operator_id = o.operator_id WHERE c.chat_status != 3 AND c.chat_status != 1 AND o.operator_id = :operator_id';
		
		if ($this->db->count($sql, $parameters) > 0) {
		
			$pending_chats = array();

			foreach ($this->db->select($sql, $parameters) as $row) {

				$parameters = array(
					'chat_id' => $row['chat_id']
				);

				$pending_chats[] = array(
					'chat_id'			=> $row['chat_id'],
					'chat_status'		=> $row['chat_status'],
					'chat_active'		=> ($this->db->count('SELECT chat_active FROM ' . config_item('database', 'table_prefix') . 'operator_sessions WHERE chat_id = :chat_id AND chat_active = 1', $parameters) > 0) ? true : false,
					'new_activity'		=> $row['new_activity'],
					'current_chat_id'	=> $this->operator_get_chat_id($operator_id),
					'username'			=> $row['username'],
					'ip_address'		=> $row['ip_address'],
					'department_name'	=> $row['department_name'],
					'elapsed_time'		=> elapsed_time(time(), $row['time_start'])
				);

				if ($this->operator_get_chat_id($operator_id) == $row['chat_id']) {

					$parameters = array(
						'new_activity'	=> 0,
						'chat_id'		=> $row['chat_id']
					);

					$this->db->update(config_item('database', 'table_prefix') . 'chats', 'new_activity = :new_activity', 'chat_id = :chat_id', $parameters);

				}

			}

			$data = array(
				'success'	=> true,
				'data' 		=> $pending_chats
			);

		} else {

			$data = array(
				'success' => false
			);

		}
		
		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);
	
		$result = $this->db->select('SELECT MAX(chat_id) AS chat_id FROM ' . config_item('database', 'table_prefix') . 'chats c JOIN ' . config_item('database', 'table_prefix') . 'department_operators do ON c.department_id = do.department_id JOIN ' . config_item('database', 'table_prefix') . 'operators o ON do.operator_id = o.operator_id WHERE c.chat_status != 3 AND c.chat_status != 1 AND o.operator_id = :operator_id', $parameters);

		$data['last_chat_id'] = $result[0]['chat_id'];
		$data['new_chat'] = false;

		$result = $this->db->get_field(config_item('database', 'table_prefix') . 'operator_sessions', 'last_chat_id', 'operator_id = :operator_id', $parameters);

		if ($data['last_chat_id'] > $result['last_chat_id']) {

			$data['new_chat'] = true;

			$parameters = array(
				'last_chat_id'	=> $data['last_chat_id'],
				'operator_id'	=> sanitize($operator_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'operator_sessions', 'last_chat_id = :last_chat_id', 'operator_id = :operator_id', $parameters);

		}

		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);

		$result = $this->db->select('SELECT MAX(c.last_activity) AS last_activity FROM ' . config_item('database', 'table_prefix') . 'chats c JOIN ' . config_item('database', 'table_prefix') . 'department_operators do ON c.department_id = do.department_id JOIN ' . config_item('database', 'table_prefix') . 'operators o ON do.operator_id = o.operator_id WHERE c.chat_status != 1 AND o.operator_id = :operator_id', $parameters);

		$data['last_activity'] = $result[0]['last_activity'];
		
		return $data;

	}

	public function operator_get_chat_messages($operator_id, $chat_timeout, $date_format, $time_format) {

		$parameters = array(
			'chat_id' => $this->operator_get_chat_id($operator_id)
		);

		if ($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_id = :chat_id', $parameters) > 0 && $this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'operator_sessions WHERE chat_id = :chat_id', $parameters) > 0) {

			$result = $this->db->select('SELECT last_activity FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_id = :chat_id', $parameters);

			$total = time() - $result[0]['last_activity'];

			if ($total > $chat_timeout) {

				$data = array(
					'success' => false
				);

			} else {

				$sql = 'SELECT m.message, m.time, m.operator_name, m.operator_email, c.username, c.email, c.user_agent, c.referer, c.ip_address FROM ' . config_item('database', 'table_prefix') . 'messages m JOIN ' . config_item('database', 'table_prefix') . 'chats c ON m.chat_id = c.chat_id WHERE m.chat_id = :chat_id ORDER BY m.message_id ASC';

				if ($this->db->count($sql, $parameters) > 0) {

					$messages = array();

					foreach ($this->db->select($sql, $parameters) as $row) {

						$name = (empty($row['operator_name'])) ? $row['username'] : $row['operator_name'];

						$messages[] = array(
							'message'			=> $row['message'],
							'operator_name'		=> $row['operator_name'],
							'operator_email'	=> $row['operator_email'],
							'name'				=> $name,
							'date'				=> date($date_format, $row['time']),
							'time'				=> date($time_format, $row['time'])
						);
						
						$referer = parse_url($row['referer']);
						
						$visitor_details = array(
							'username'		=> $row['username'],
							'email'			=> $row['email'],
							'platform'		=> platform($row['user_agent']),
							'browser'		=> browser($row['user_agent']),
							'referer_url'	=> $row['referer'],
							'referer_from'	=> $referer['scheme'] . '://' .  $referer['host'],
							'ip_address'	=> $row['ip_address']
						);

					}

					$data = array(
						'success'			=> true,
						'messages'			=> $messages,
						'visitor_details'	=> $visitor_details
					);

				} else {

					$data = array(
						'success' => false
					);

				}

			}
			
			$results = $this->db->select('SELECT last_activity, user_typing FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_id = :chat_id', $parameters);
			$data['last_activity'] = $results[0]['last_activity'];
			$data['user_typing'] = ($results[0]['user_typing']) ? true : false;
			$data['chat_expired'] = ($total > $chat_timeout) ? true : false;

			$result = $this->db->get_field(config_item('database', 'table_prefix') . 'messages', 'message_id', 'chat_id = :chat_id ORDER BY message_id DESC LIMIT 1', $parameters);

			$data['message_id'] = $result['message_id'];
			$data['new_message'] = false;

			$parameters = array(
				'chat_id' 		=> $this->operator_get_chat_id($operator_id),
				'operator_id' 	=> $operator_id
			);
			
			$results = $this->db->select('SELECT chat_active, last_message_id FROM ' . config_item('database', 'table_prefix') . 'operator_sessions WHERE operator_id = :operator_id AND chat_id = :chat_id', $parameters);
			
			$data['chat_active'] = ($results[0]['chat_active']) ? true : false;
			
			if ($data['message_id'] > $results[0]['last_message_id']) {
				
				$data['new_message'] = true;

				$parameters = array(
					'last_message_id'	=> $data['message_id'],
					'chat_id'			=> $this->operator_get_chat_id($operator_id)
				);

				$this->db->update(config_item('database', 'table_prefix') . 'operator_sessions', 'last_message_id = :last_message_id', 'chat_id = :chat_id', $parameters);
				
			}
			
		} else {
			
			$data = array(
				'success'		=> false,
				'user_typing'	=> false,
				'new_message'	=> false,
				'last_activity'	=> 0,
				'chat_expired'	=> false,
				'chat_active'	=> false
			);
			
		}
		
		return $data;

	}

	public function operator_get_id($user_id) {

		$parameters = array(
			'user_id' => sanitize($user_id, 'integer')
		);

		$result = $this->db->select('SELECT operator_id FROM ' . config_item('database', 'table_prefix') . 'operators WHERE user_id = :user_id', $parameters);

		return $result[0]['operator_id'];

	}

	public function operator_get_chat_id($operator_id) {

		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);

		$result = $this->db->get_field(config_item('database', 'table_prefix') . 'operator_sessions', 'chat_id', 'operator_id = :operator_id', $parameters);

		return $result['chat_id'];

	}

	public function operator_get_full_name($operator_id) {

		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);

		$result = $this->db->select('SELECT CONCAT_WS(" ", first_name, last_name) AS full_name FROM ' . config_item('database', 'table_prefix') . 'operators WHERE operator_id = :operator_id', $parameters);

		return $result[0]['full_name'];

	}

	public function operator_get_email($operator_id) {

		$parameters = array(
			'operator_id' => sanitize($operator_id, 'integer')
		);

		$result = $this->db->select('SELECT u.user_email FROM ' . config_item('database', 'table_prefix') . 'users u JOIN ' . config_item('database', 'table_prefix') . 'operators o ON u.user_id = o.user_id AND o.operator_id = :operator_id', $parameters);

		return $result[0]['user_email'];

	}

	public function operator_send_message($operator_id, $message) {
		
		$parameters = array(
			array(
				'chat_id' 		=> $this->operator_get_chat_id($operator_id)
			),
			array(
				'operator_id' 	=> $operator_id,
				'chat_id' 		=> $this->operator_get_chat_id($operator_id)
			)
		);

		if ($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_id = :chat_id', $parameters[0]) > 0 && $this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'operator_sessions WHERE operator_id = :operator_id AND chat_id = :chat_id AND chat_active = 1', $parameters[1]) > 0) {

			$data = array(
				'chat_id'			=> $this->operator_get_chat_id($operator_id),
				'message'			=> sanitize($message, 'string'),
				'operator_name'		=> $this->operator_get_full_name(sanitize($operator_id, 'integer')),
				'operator_email'	=> $this->operator_get_email(sanitize($operator_id, 'integer')),
				'time'				=> time()
			);

			$this->db->insert(config_item('database', 'table_prefix') . 'messages', $data);

			$parameters = array(
				'last_activity'	=> time(),
				'operator_id'	=> sanitize($operator_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'operators', 'last_activity = :last_activity', 'operator_id = :operator_id', $parameters);

			$parameters = array(
				'operator_typing' 	=> 0,
				'last_activity'		=> time(),
				'chat_id'			=> $this->operator_get_chat_id($operator_id)
			);

			$this->db->update(config_item('database', 'table_prefix') . 'chats', 'operator_typing = :operator_typing, last_activity = :last_activity', 'chat_id = :chat_id', $parameters);

			$data = array(
				'success' => true
			);
			
		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;

	}

	public function operator_typing($operator_id, $operator_typing) {
		
		$parameters = array(
			array(
				'chat_id' 		=> $this->operator_get_chat_id($operator_id)
			),
			array(
				'operator_id' 	=> $operator_id,
				'chat_id' 		=> $this->operator_get_chat_id($operator_id)
			)
		);

		if ($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_id = :chat_id', $parameters[0]) > 0 && $this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'operator_sessions WHERE operator_id = :operator_id AND chat_id = :chat_id AND chat_active = 1', $parameters[1]) > 0) {

			$parameters = array(
				'operator_typing' 	=> sanitize($operator_typing, 'string'),
				'last_activity'		=> time(),
				'chat_id'			=> $this->operator_get_chat_id($operator_id)
			);

			$this->db->update(config_item('database', 'table_prefix') . 'chats', 'operator_typing = :operator_typing, last_activity = :last_activity', 'chat_id = :chat_id', $parameters);

			$data = array(
				'success' => true
			);

		} else {

			$data = array(
				'success' => false
			);
			
		}
		
		return $data;

	}

	public function operator_transfer_chat($operator_id, $department_id, $message) {

		$parameters = array(
			array(
				'chat_id' 		=> $this->operator_get_chat_id($operator_id)
			),
			array(
				'operator_id' 	=> $operator_id,
				'chat_id' 		=> $this->operator_get_chat_id($operator_id)
			)
		);

		if ($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_id = :chat_id', $parameters[0]) > 0 && $this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'operator_sessions WHERE operator_id = :operator_id AND chat_id = :chat_id AND chat_active = 1', $parameters[1]) > 0) {

			$parameters = array(
				'department_id' => sanitize($department_id, 'integer')
			);
			
			if ($this->db->count('SELECT department_id FROM ' . config_item('database', 'table_prefix') . 'departments WHERE department_id = :department_id', $parameters) > 0) {

				$result = $this->db->get_field(config_item('database', 'table_prefix') . 'departments', 'department_name', 'department_id = :department_id', $parameters);

				$parameters = array(
					'chat_status' 		=> 0,
					'last_activity'		=> time(),
					'department_name'	=> $result['department_name'],
					'department_id'		=> sanitize($department_id, 'integer'),
					'chat_id'			=> $this->operator_get_chat_id($operator_id)
				);

				$this->db->update(config_item('database', 'table_prefix') . 'chats', 'chat_status = :chat_status, last_activity = :last_activity, department_name = :department_name, department_id = :department_id', 'chat_id = :chat_id', $parameters);

				$data = array(
					'chat_id'			=> $this->operator_get_chat_id($operator_id),
					'message'			=> sanitize($message, 'string'),
					'operator_name'		=> $this->operator_get_full_name(sanitize($operator_id, 'integer')),
					'operator_email'	=> $this->operator_get_email(sanitize($operator_id, 'integer')),
					'time'				=> time()
				);

				$this->db->insert(config_item('database', 'table_prefix') . 'messages', $data);

				$parameters = array(
					'chat_id' 		=> 0,
					'chat_active'	=> 0,
					'operator_id' 	=> sanitize($operator_id, 'integer')
				);

				$this->db->update(config_item('database', 'table_prefix') . 'operator_sessions', 'chat_id = :chat_id, chat_active = :chat_active', 'operator_id = :operator_id', $parameters);

				$data = array(
					'success' => true
				);

			} else {

				$data = array(
					'success' => false
				);

			}
			
		} else {

			$data = array(
				'success' => false
			);

		}
		
		return $data;

	}

	public function operator_start_chat($operator_id, $chat_id, $message) {
		
		$parameters = array(
			'chat_id' => sanitize($chat_id, 'integer')
		);
		
		if ($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_id = :chat_id', $parameters) > 0) {

			$result = $this->db->get_field(config_item('database', 'table_prefix') . 'chats', 'chat_status', 'chat_id = :chat_id', $parameters);
			
			if ($result['chat_status'] == 0) {
				
				$data = array(
					'chat_id'			=> sanitize($chat_id, 'integer'),
					'message'			=> sanitize($message, 'string'),
					'operator_name'  	=> $this->operator_get_full_name(sanitize($operator_id, 'integer')),
					'operator_email' 	=> $this->operator_get_email(sanitize($operator_id, 'integer')),
					'time'				=> time()
				);

				$this->db->insert(config_item('database', 'table_prefix') . 'messages', $data);
				
			}

			$parameters = array(
				'chat_status'	=> 2,
				'last_activity'	=> time(),
				'new_activity'	=> 0,
				'chat_id'		=> sanitize($chat_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'chats', 'chat_status = :chat_status, last_activity = :last_activity, new_activity = :new_activity', 'chat_id = :chat_id', $parameters);

			$parameters = array(
				'chat_id'		=> sanitize($chat_id, 'integer'),
				'chat_active'	=> 1,
				'last_activity'	=> time(),
				'operator_id'	=> sanitize($operator_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'operator_sessions', 'chat_id = :chat_id, chat_active = :chat_active, last_activity = :last_activity', 'operator_id = :operator_id', $parameters);

			$data = array(
				'success' => true
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;
		
	}

	public function operator_stop_chat($operator_id, $message) {
		
		$parameters = array(
			array(
				'chat_id' 		=> $this->operator_get_chat_id($operator_id)
			),
			array(
				'operator_id' 	=> $operator_id,
				'chat_id' 		=> $this->operator_get_chat_id($operator_id)
			)
		);

		if ($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_id = :chat_id', $parameters[0]) > 0 && $this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'operator_sessions WHERE operator_id = :operator_id AND chat_id = :chat_id AND chat_active = 1', $parameters[1]) > 0) {

			$parameters = array(
				'chat_status'	=> 3,
				'time_end'		=> time(),
				'last_activity'	=> time(),
				'chat_id' 		=> $this->operator_get_chat_id($operator_id)
			);

			$this->db->update(config_item('database', 'table_prefix') . 'chats', 'chat_status = :chat_status, time_end = :time_end, last_activity = :last_activity', 'chat_id = :chat_id', $parameters);

			$data = array(
				'chat_id'			=> $this->operator_get_chat_id($operator_id),
				'message'			=> sanitize($message, 'string'),
				'operator_name'		=> $this->operator_get_full_name(sanitize($operator_id, 'integer')),
				'operator_email'	=> $this->operator_get_email(sanitize($operator_id, 'integer')),
				'time'				=> time()
			);

			$this->db->insert(config_item('database', 'table_prefix') . 'messages', $data);

			$parameters = array(
				'chat_id' 		=> 0,
				'chat_active'	=> 0,
				'operator_id' 	=> sanitize($operator_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'operator_sessions', 'chat_id = :chat_id, chat_active = :chat_active', 'operator_id = :operator_id', $parameters);

			$data = array(
				'success' => true
			);

		} elseif ($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_id = :chat_id', $parameters[0]) > 0 && $this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'operator_sessions WHERE operator_id = :operator_id AND chat_id = :chat_id AND chat_active = 0', $parameters[1]) > 0) {

			$parameters = array(
				'last_activity' => time(),
				'chat_id' 		=> $this->operator_get_chat_id($operator_id)
			);

			$this->db->update(config_item('database', 'table_prefix') . 'chats', 'last_activity = :last_activity', 'chat_id = :chat_id', $parameters);
			
			$parameters = array(
				'chat_id' 		=> 0,
				'chat_active'	=> 0,
				'operator_id' 	=> sanitize($operator_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'operator_sessions', 'chat_id = :chat_id, chat_active = :chat_active', 'operator_id = :operator_id', $parameters);
			
			$data = array(
				'success' => true
			);

		} else {
			
			$data = array(
				'success' => false
			);
			
		}

		return $data;

	}

	public function operator_watch_chat($operator_id, $chat_id) {

		$parameters = array(
			'chat_id' => sanitize($chat_id, 'integer')
		);

		if ($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_id = :chat_id', $parameters) > 0) {

			$parameters = array(
				'chat_id'		=> sanitize($chat_id, 'integer'),
				'chat_active'	=> 0,
				'last_activity'	=> time(),
				'operator_id'	=> sanitize($operator_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'operator_sessions', 'chat_id = :chat_id, chat_active = :chat_active, last_activity = :last_activity', 'operator_id = :operator_id', $parameters);

			$parameters = array(
				'last_activity'	=> time(),
				'chat_id'		=> sanitize($chat_id, 'integer')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'chats', 'last_activity = :last_activity', 'chat_id = :chat_id', $parameters);

			$data = array(
				'success' => true
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;

	}

	public function operator_send_invitation($ip_address, $invitation_message) {

		$parameters = array(
			'ip_address' => sanitize($ip_address, 'string')
		);

		if ($this->db->count('SELECT ip_address FROM ' . config_item('database', 'table_prefix') . 'online_visitors WHERE ip_address = :ip_address', $parameters) > 0) {

			$parameters = array(
				'invitation_message'	=> sanitize($invitation_message, 'string'),
				'time'					=> time(),
				'ip_address'			=> sanitize($ip_address, 'string')
			);

			$this->db->update(config_item('database', 'table_prefix') . 'online_visitors', 'invitation_message = :invitation_message, time = :time', 'ip_address = :ip_address', $parameters);

			$data = array(
				'success' => true
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		return $data;

	}

	public function operator_update($operator_id, $chat_timeout, $key = null) {
		
		$this->db->delete(config_item('database', 'table_prefix') . 'operator_sessions', 'last_activity < (UNIX_TIMESTAMP() - ' . ($chat_timeout + 10) . ')');

		if (is_null($key)) {

			$parameters = array(
				'operator_id' => sanitize($operator_id, 'integer')
			);
			
			if ($this->db->count('SELECT operator_key FROM ' . config_item('database', 'table_prefix') . 'operator_sessions WHERE operator_id = :operator_id', $parameters) > 0) {

				$result = $this->db->get_field(config_item('database', 'table_prefix') . 'operator_sessions', 'operator_key', 'operator_id = :operator_id', $parameters);

				return $result['operator_key'];

			} else {
				
				return false;
				
			}
			
		} else {

			$parameters = array(
				'operator_id' => sanitize($operator_id, 'integer')
			);

			if ($this->db->count('SELECT operator_id FROM ' . config_item('database', 'table_prefix') . 'operator_sessions WHERE operator_id = :operator_id', $parameters) > 0) {

				$parameters = array(
					'chat_id'		=> $this->operator_get_chat_id($operator_id),
					'operator_key'	=> $key,
					'operator_id'	=> sanitize($operator_id, 'integer')
				);

				$this->db->update(config_item('database', 'table_prefix') . 'operator_sessions', 'chat_id = :chat_id, operator_key = :operator_key', 'operator_id = :operator_id', $parameters);

			} else {

				$data = array(
					'last_activity'	=> time(),
					'operator_key'	=> $key,
					'operator_id'	=> sanitize($operator_id, 'integer')
				);

				$this->db->insert(config_item('database', 'table_prefix') . 'operator_sessions', $data);

			}

		}

	}

	public function visitor_get_chat_status($chat_hash, $operator_timeout, $max_visitors) {
		
		$result = $this->db->select('SELECT MAX(last_activity) AS last_activity FROM ' . config_item('database', 'table_prefix') . 'operators WHERE hide_online = 0');

		$parameters = array(
			'chat_hash' => $chat_hash
		);

		if ((time() - $result[0]['last_activity']) > $operator_timeout || $this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_hash != :chat_hash AND (chat_status = 0 OR chat_status = 2)', $parameters) >= $max_visitors) {
			
			$data = array(
				'success' => false
			);
			
		} else {
			
			if ($this->db->count('SELECT chat_hash FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_hash = :chat_hash', $parameters) > 0) {
			
				$result = $this->db->get_field(config_item('database', 'table_prefix') . 'chats', 'department_name', 'chat_hash = :chat_hash', $parameters);
				
				$data = array(
					'success' 			=> true,
					'department_name' 	=> $result['department_name']
				);
				
			} else {
				
				$data = array(
					'success' => true
				);
				
			}
			
		}

		return $data;
		
	}

	public function visitor_contact_operators($chat_hash, $operator_timeout, $max_visitors) {
		
		$parameters = array(
			'ip_address' => $_SERVER['REMOTE_ADDR']
		);

		if ($this->db->count('SELECT ip_address FROM ' . config_item('database', 'table_prefix') . 'blocked_visitors WHERE ip_address = :ip_address', $parameters) > 0) {
			
			$departments = array();

			foreach ($this->db->select('SELECT d.department_id, d.department_name FROM ' . config_item('database', 'table_prefix') . 'departments d JOIN ' . config_item('database', 'table_prefix') . 'department_operators do ON d.department_id = do.department_id GROUP BY d.department_name ORDER BY d.department_name ASC') as $row) {

				$departments[] = array(
					'department_id'		=> $row['department_id'],
					'department_name'	=> $row['department_name']
				);

			}
			
			$data = array(
				'success'		=> false,
				'online'		=> false,
				'departments'	=> $departments
			);
		
		} else {
			
			$result = $this->db->select('SELECT MAX(last_activity) AS last_activity FROM ' . config_item('database', 'table_prefix') . 'operators WHERE hide_online = 0');
			
			$parameters = array(
				'chat_hash' => $chat_hash
			);

			if ($this->db->count('SELECT chat_hash FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_hash = :chat_hash', $parameters) > 0) {
				
				$result = $this->db->get_field(config_item('database', 'table_prefix') . 'chats', 'department_name', 'chat_hash = :chat_hash', $parameters);

				$parameters = array(
					'last_activity'	=> time(),
					'chat_hash'		=> $chat_hash
				);

				$this->db->update(config_item('database', 'table_prefix') . 'chats', 'last_activity = :last_activity', 'chat_hash = :chat_hash', $parameters);
			
				$data = array(
					'success' => true
				);

			} elseif ((time() - $result[0]['last_activity']) > $operator_timeout || $this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_status = 0 OR chat_status = 2') >= $max_visitors) {
				
				$departments = array();

				foreach ($this->db->select('SELECT d.department_id, d.department_name FROM ' . config_item('database', 'table_prefix') . 'departments d JOIN ' . config_item('database', 'table_prefix') . 'department_operators do ON d.department_id = do.department_id GROUP BY d.department_name ORDER BY d.department_name ASC') as $row) {

					$departments[] = array(
						'department_id'		=> $row['department_id'],
						'department_name'	=> $row['department_name']
					);

				}
				
				$data = array(
					'success' 		=> false,
					'online' 		=> false,
					'departments'	=> $departments
				);
				
			} else {
				
				$departments = array();

				foreach ($this->db->select('SELECT MAX(o.last_activity) AS last_activity, d.department_id, d.department_name FROM ' . config_item('database', 'table_prefix') . 'operators o JOIN ' . config_item('database', 'table_prefix') . 'department_operators do ON o.operator_id = do.operator_id JOIN ' . config_item('database', 'table_prefix') . 'departments d ON do.department_id = d.department_id WHERE o.hide_online = 0 GROUP BY d.department_name ORDER BY o.last_activity DESC') as $row) {
					
					$departments[] = array(
						'total'				=> (time() - $row['last_activity']),
						'operator_timeout'	=> $operator_timeout,
						'department_id'		=> $row['department_id'],
						'department_name'	=> $row['department_name']
					);

				}
				
				$data = array(
					'success' 		=> false,
					'online' 		=> true,
					'departments' 	=> $departments
				);
				
			}
			
		}
		
		return $data;
		
	}

	public function visitor_start_chat($department_id, $username, $email, $message, $chat_hash, $operator_timeout, $max_visitors, $max_connections) {

		$parameters = array(
			'department_id' => sanitize($department_id, 'integer')
		);

		$result = $this->db->select('SELECT MAX(o.last_activity) AS last_activity FROM ' . config_item('database', 'table_prefix') . 'operators o JOIN ' . config_item('database', 'table_prefix') . 'department_operators do ON do.department_id = :department_id WHERE o.hide_online = 0 AND do.operator_id = o.operator_id', $parameters);

		$parameters = array(
			'ip_address' => $_SERVER['REMOTE_ADDR']
		);

		$departments = array();

		foreach ($this->db->select('SELECT d.department_id, d.department_name FROM ' . config_item('database', 'table_prefix') . 'departments d JOIN ' . config_item('database', 'table_prefix') . 'department_operators do ON d.department_id = do.department_id GROUP BY d.department_name ORDER BY d.department_name ASC') as $row) {

			$departments[] = array(
				'department_id'		=> $row['department_id'],
				'department_name'	=> $row['department_name']
			);

		}

		if ((time() - $result[0]['last_activity']) > $operator_timeout || $this->db->count('SELECT ip_address FROM ' . config_item('database', 'table_prefix') . 'blocked_visitors WHERE ip_address = :ip_address', $parameters) > 0) {

			$data = array(
				'success'		=> false,
				'departments'	=> $departments
			);
			
		} elseif ($this->db->count('SELECT chat_id FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_status = 0 OR chat_status = 2', $parameters) >= $max_visitors || $this->db->count('SELECT ip_address FROM ' . config_item('database', 'table_prefix') . 'chats WHERE ip_address = :ip_address AND (chat_status = 0 OR chat_status = 2)', $parameters) >= $max_connections) {

			$data = array(
				'success'		=> false,
				'departments'	=> $departments
			);
			
		} else {
		
			$parameters = array(
				'chat_hash' => $chat_hash
			);
			
			if ($this->db->count('SELECT chat_hash FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_hash = :chat_hash', $parameters) > 0) {
				
				$result = $this->db->get_field(config_item('database', 'table_prefix') . 'chats', 'chat_id', 'chat_hash = :chat_hash', $parameters);

				$values = array(
					'chat_id'	=> $result['chat_id'],
					'message'	=> sanitize($message, 'string'),
					'time'		=> time()
				);

				$this->db->insert(config_item('database', 'table_prefix') . 'messages', $values);
				
			} else {
				
				$parameters = array(
					'department_id' => sanitize($department_id, 'integer')
				);
				
				$result = $this->db->get_field(config_item('database', 'table_prefix') . 'departments', 'department_name', 'department_id = :department_id', $parameters);

				$values = array(
					'chat_status'		=> 0,
					'chat_hash'			=> $chat_hash,
					'time_start'		=> time(),
					'last_activity'		=> time(),
					'ip_address'		=> $_SERVER['REMOTE_ADDR'],
					'email'				=> sanitize($email, 'email'),
					'username'			=> sanitize($username, 'string'),
					'referer'			=> $_SERVER['HTTP_REFERER'],
					'user_agent'		=> $_SERVER['HTTP_USER_AGENT'],
					'department_name'	=> $result['department_name'],
					'department_id'		=> sanitize($department_id, 'integer')
				); 			

				$this->db->insert(config_item('database', 'table_prefix') . 'chats', $values);
				
				$chat_id = $this->db->last_insert_id();
				
				$values = array(
					'chat_id'	=> $chat_id,
					'message'	=> sanitize($message, 'string'),
					'time'		=> time()
				); 			

				$this->db->insert(config_item('database', 'table_prefix') . 'messages', $values);
				
			}			

			$data = array(
				'success' => true
			);
			
		}
		
		return $data;
		
	}

	public function visitor_send_message($chat_hash, $message) {
		
		$parameters = array(
			'chat_hash' => $chat_hash
		);
		
		if ($this->db->count('SELECT chat_hash FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_hash = :chat_hash', $parameters) > 0) {

			$result = $this->db->get_field(config_item('database', 'table_prefix') . 'chats', 'chat_id', 'chat_hash = :chat_hash', $parameters);

			$values = array(
				'chat_id'	=> $result['chat_id'],
				'message'	=> sanitize($message, 'string'),
				'time'		=> time()
			); 			

			$this->db->insert(config_item('database', 'table_prefix') . 'messages', $values);

			$parameters = array(
				'new_activity'	=> 1,
				'user_typing'	=> 0,
				'last_activity'	=> time(),
				'chat_id' 		=> $result['chat_id']
			);

			$this->db->update(config_item('database', 'table_prefix') . 'chats', 'new_activity = :new_activity, user_typing = :user_typing, last_activity = :last_activity', 'chat_id = :chat_id', $parameters);
			
			$data = array(
				'success' => true
			);
			
		} else {
			
			$data = array(
				'success' => false
			);
			
		}
		
		return $data;
		
	}

	public function visitor_stop_chat($chat_hash, $message) {
		
		$parameters = array(
			'chat_hash' => $chat_hash
		);

		if ($this->db->count('SELECT chat_hash FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_hash = :chat_hash', $parameters) > 0) {

			$parameters = array(
				'chat_status'	=> 3,
				'time_end'		=> time(),
				'last_activity'	=> time(),
				'chat_hash'		=> $chat_hash
			);

			$this->db->update(config_item('database', 'table_prefix') . 'chats', 'chat_status = :chat_status, time_end = :time_end, last_activity = :last_activity', 'chat_hash = :chat_hash', $parameters);

			$parameters = array(
				'chat_hash' => $chat_hash
			);
			
			$result = $this->db->get_field(config_item('database', 'table_prefix') . 'chats', 'chat_id', 'chat_hash = :chat_hash', $parameters);
			
			$values = array(
				'chat_id'	=> $result['chat_id'],
				'message'	=> sanitize($message, 'string'),
				'time'		=> time()
			); 			

			$this->db->insert(config_item('database', 'table_prefix') . 'messages', $values);
			
			$this->db->delete(config_item('database', 'table_prefix') . 'visitor_sessions', 'chat_hash = :chat_hash', $parameters);
			
			$data = array(
				'success' => true
			);

		} else {
			
			$data = array(
				'success' => false
			);
		
		}
		
		return $data;
		
	}

	public function visitor_typing($chat_hash, $visitor_typing) {
		
		$parameters = array(
			'chat_hash' => $chat_hash
		);
		
		if ($this->db->count('SELECT chat_hash FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_hash = :chat_hash', $parameters) > 0) {
			
			$parameters = array(
				'user_typing'	=> $visitor_typing,
				'last_activity'	=> time(),
				'chat_hash'		=> $chat_hash
			);

			$this->db->update(config_item('database', 'table_prefix') . 'chats', 'user_typing = :user_typing, last_activity = :last_activity', 'chat_hash = :chat_hash', $parameters);

			$data = array(
				'success' => true
			);

		} else {

			$data = array(
				'success' => false
			);

		}
		
		return $data;
		
	}

	public function visitor_get_chat_messages($chat_hash, $chat_timeout, $date_format, $time_format) {

		$parameters = array(
			'chat_hash' => $chat_hash
		);

		if ($this->db->count('SELECT chat_hash FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_hash = :chat_hash', $parameters) > 0 && $this->db->count('SELECT chat_hash FROM ' . config_item('database', 'table_prefix') . 'visitor_sessions WHERE chat_hash = :chat_hash', $parameters) > 0) {

			$result = $this->db->select('SELECT last_activity FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_hash = :chat_hash', $parameters);

			$total = time() - $result[0]['last_activity'];

			if ($total > $chat_timeout) {

				$data = array(
					'success' => false
				);

			} else {
				
				$result = $this->db->get_field(config_item('database', 'table_prefix') . 'chats', 'chat_id', 'chat_hash = :chat_hash', $parameters);

				$parameters = array(
					'chat_id' => $result['chat_id']
				);

				$sql = 'SELECT m.message, m.time, m.operator_name, m.operator_email, c.username FROM ' . config_item('database', 'table_prefix') . 'messages m JOIN ' . config_item('database', 'table_prefix') . 'chats c ON m.chat_id = c.chat_id WHERE m.chat_id = :chat_id ORDER BY m.message_id ASC';

				if ($this->db->count($sql, $parameters) > 0) {

					$messages = array();

					foreach ($this->db->select($sql, $parameters) as $row) {

						$name = (empty($row['operator_name'])) ? $row['username'] : $row['operator_name'];

						$messages[] = array(
							'message'			=> $row['message'],
							'operator_name'		=> $row['operator_name'],
							'operator_email'	=> $row['operator_email'],
							'name'				=> $name,
							'date'				=> date($date_format, $row['time']),
							'time'				=> date($time_format, $row['time'])
						);

					}

					$data = array(
						'success'	=> true,
						'messages'	=> $messages
					);

				} else {

					$data = array(
						'success' => false
					);

				}

			}
			
			$parameters = array(
				'chat_hash' => $chat_hash
			);
			
			$results = $this->db->select('SELECT chat_id, chat_status, operator_typing, last_activity FROM ' . config_item('database', 'table_prefix') . 'chats WHERE chat_hash = :chat_hash', $parameters);
			
			$data['queue'] = ($results[0]['chat_status'] == 0) ? false : true;
			$data['operator_typing'] = ($results[0]['operator_typing']) ? true : false;
			$data['last_activity'] = $results[0]['last_activity'];
			$data['chat_expired'] = ($total > $chat_timeout) ? true : false;
			
			$parameters = array(
				'chat_id' => $results[0]['chat_id']
			);
			
			$result = $this->db->get_field(config_item('database', 'table_prefix') . 'messages', 'message_id', 'chat_id = :chat_id ORDER BY message_id DESC LIMIT 1', $parameters);

			$data['message_id'] = $result['message_id'];
			$data['new_message'] = false;
			
			$parameters = array(
				'chat_hash' => $chat_hash
			);
			
			$result = $this->db->get_field(config_item('database', 'table_prefix') . 'visitor_sessions', 'last_message_id', 'chat_hash = :chat_hash', $parameters);
			
			if ($data['message_id'] > $result['last_message_id']) {
				
				$data['new_message'] = true;

				$parameters = array(
					'last_message_id'	=> $data['message_id'],
					'chat_hash' 		=> $chat_hash
				);

				$this->db->update(config_item('database', 'table_prefix') . 'visitor_sessions', 'last_message_id = :last_message_id', 'chat_hash = :chat_hash', $parameters);
				
				
			}

		} else {
			
			$parameters = array(
				'chat_hash' => $chat_hash
			);
			
			$data = array(
				'success'			=> false,
				'operator_typing'	=> false,
				'new_message'		=> false,
				'last_activity' 	=> 0,
				'queue' 			=> false,
				'chat_expired' 		=> false
			);

		}
		
		return $data;

	}

	public function visitor_update($chat_hash, $chat_timeout, $key = null) {
		
		$this->db->delete(config_item('database', 'table_prefix') . 'visitor_sessions', 'last_activity < (UNIX_TIMESTAMP() - ' . ($chat_timeout + 10) . ')');

		if (is_null($key)) {

			$parameters = array(
				'chat_hash' => $chat_hash
			);
			
			if ($this->db->count('SELECT visitor_key FROM ' . config_item('database', 'table_prefix') . 'visitor_sessions WHERE chat_hash = :chat_hash', $parameters) > 0) {

				$result = $this->db->get_field(config_item('database', 'table_prefix') . 'visitor_sessions', 'visitor_key', 'chat_hash = :chat_hash', $parameters);

				return $result['visitor_key'];

			} else {
				
				return false;
				
			}
			
		} else {

			$parameters = array(
				'chat_hash' => $chat_hash
			);

			if ($this->db->count('SELECT chat_hash FROM ' . config_item('database', 'table_prefix') . 'visitor_sessions WHERE chat_hash = :chat_hash', $parameters) > 0) {

				$parameters = array(
					'visitor_key' 	=> $key,
					'chat_hash'		=> $chat_hash
				);

				$this->db->update(config_item('database', 'table_prefix') . 'visitor_sessions', 'chat_hash = :chat_hash, visitor_key = :visitor_key', 'chat_hash = :chat_hash', $parameters);

			} else {

				$data = array(
					'last_activity'	=> time(),
					'visitor_key'	=> $key,
					'chat_hash'		=> $chat_hash
				);

				$this->db->insert(config_item('database', 'table_prefix') . 'visitor_sessions', $data);

			}

		}

	}

	public function visitor_get_invitation_message() {
		
		$parameters = array(
			'ip_address' => $_SERVER['REMOTE_ADDR']
		);

		if ($this->db->count('SELECT invitation_message FROM ' . config_item('database', 'table_prefix') . 'online_visitors WHERE ip_address = :ip_address', $parameters) > 0) {
		
			$result = $this->db->get_field(config_item('database', 'table_prefix') . 'online_visitors', 'invitation_message', 'ip_address = :ip_address', $parameters);
			
			$data = array(
				'success'	=> (is_null($result['invitation_message'])) ? false : true, 
				'message' 	=> $result['invitation_message']
			);

			$parameters = array(
				'invitation_message'	=> null,
				'ip_address'			=> $_SERVER['REMOTE_ADDR']
			);
			
			$this->db->update(config_item('database', 'table_prefix') . 'online_visitors', 'invitation_message = :invitation_message', 'ip_address = :ip_address', $parameters);
			
		} else {
			
			$data = array(
				'success'	=> false,
				'message'	=> null
			);
			
		}
		
		return $data;

	}

	public function visitor_send_email($department_id, $username, $visitor_email, $subject, $visitor_message, $message, $charset) {

		$parameters = array(
			'department_id' => sanitize($department_id, 'integer')
		);
	
		$result = $this->db->get_field(config_item('database', 'table_prefix') . 'departments', 'department_name', 'department_id = :department_id', $parameters);
		
		$data = array(
			'chat_status'		=> 1,
			'chat_hash'			=> uniqid(),
			'time_start'		=> time(),
			'time_end'			=> time(),
			'ip_address'		=> $_SERVER['REMOTE_ADDR'],
			'email'				=> sanitize($visitor_email, 'email'),
			'username'			=> sanitize($username." - Çevrimdışı Mesaj", 'string'),
			'referer'			=> $_SERVER['HTTP_REFERER'],
			'user_agent'		=> $_SERVER['HTTP_USER_AGENT'],
			'department_name'	=> $result['department_name']
		);
		
		$this->db->insert(config_item('database', 'table_prefix') . 'chats', $data);
		
		$chat_id = $this->db->last_insert_id();
		
		$data = array(
			'chat_id'	=> $chat_id,
			'message'	=> sanitize($visitor_message, 'string'),
			'time'		=> time()
		);
		
		$this->db->insert(config_item('database', 'table_prefix') . 'messages', $data);

		$headers  = 'From: ' . sanitize($username, 'string') . '<' . sanitize($visitor_email, 'email') . '>' . "\r\n";
		$headers .=	'Reply-To: ' . sanitize($visitor_email, 'email') . "\r\n";
		$headers .=	'X-Mailer: PHP/' . phpversion() . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=' . $charset . "\r\n";
		
		$message = '<html><head></head><body> ' . sprintf($message, sanitize($visitor_message, 'string'), sanitize($username, 'string'), $_SERVER['REMOTE_ADDR']) . '</body></html>';
		
		$result = $this->db->get_field(config_item('database', 'table_prefix') . 'departments', 'department_email', 'department_id = :department_id', $parameters);
		
		$data = array(
				'success' => true
			);
		
		return $data;
	
	}

	public function online_visitors() {
		
		$parameters = array(
			'ip_address' => $_SERVER['REMOTE_ADDR'],
			'user_agent' => $_SERVER['HTTP_USER_AGENT']
		);
		
		if ($this->db->count('SELECT ip_address FROM ' . config_item('database', 'table_prefix') . 'online_visitors WHERE ip_address = :ip_address AND user_agent = :user_agent', $parameters) > 0) {
		
			$parameters = array(
				'time'			=> time(),
				'ip_address'	=> $_SERVER['REMOTE_ADDR'],
				'user_agent' 	=> $_SERVER['HTTP_USER_AGENT']
			);

			$this->db->update(config_item('database', 'table_prefix') . 'online_visitors', 'time = :time', 'ip_address = :ip_address AND user_agent = :user_agent', $parameters);
		
		} else {
			
			$data = array(
				'ip_address'	=> $_SERVER['REMOTE_ADDR'],
				'referer'		=> isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
				'user_agent'	=> $_SERVER['HTTP_USER_AGENT'],
				'time'			=> time()
			);
			
			$this->db->insert(config_item('database', 'table_prefix') . 'online_visitors', $data);

		}
		
	}

	public function get_online_visitors() {

		foreach ($this->db->select('SELECT visitor_id, time FROM ' . config_item('database', 'table_prefix') . 'online_visitors') as $row) {

			if ((time() - $row['time']) > 90) {

				$parameters = array(
					'visitor_id' => $row['visitor_id']
				);

				$this->db->delete(config_item('database', 'table_prefix') . 'online_visitors', 'visitor_id = :visitor_id', $parameters);

			}

		}

		$sql = 'SELECT DISTINCT ip_address, referer, user_agent FROM ' . config_item('database', 'table_prefix') . 'online_visitors';

		if ($this->db->count($sql) > 0) {

			$online_visitors = array();

			foreach ($this->db->select($sql) as $row) {

				$online_visitors[] = array(
					'ip_address'	=> $row['ip_address'],
					'referer'		=> $row['referer'],
					'platform'  	=> platform($row['user_agent']),
					'browser'		=> browser($row['user_agent'])
				);

			}

			$data = array(
				'success' 			=> true,
				'online_visitors'	=> $online_visitors
			);

		} else {

			$data = array(
				'success' => false
			);

		}

		$result = $this->db->select('SELECT MAX(time) AS time FROM ' . config_item('database', 'table_prefix') . 'online_visitors');

		$data['last_activity'] = (!empty($result[0]['time'])) ? $result[0]['time'] : 0;

		return $data;

	}

	public function get_canned_messages() {

		$canned_messages = array();

		foreach ($this->db->select('SELECT canned_message FROM ' . config_item('database', 'table_prefix') . 'canned_messages') as $row) {

			$canned_messages[] = array(
				'canned_message' => $row['canned_message']
			);

		}

		$data = array(
			'data' => $canned_messages
		);

		return $data;

	}

	public function get_online_departments($operator_timeout) {

		$departments = array();

		foreach ($this->db->select('SELECT MAX(o.last_activity) AS last_activity, d.department_id, d.department_name FROM ' . config_item('database', 'table_prefix') . 'operators o JOIN ' . config_item('database', 'table_prefix') . 'department_operators do ON o.operator_id = do.operator_id JOIN ' . config_item('database', 'table_prefix') . 'departments d ON do.department_id = d.department_id WHERE o.hide_online = 0 GROUP BY d.department_name ORDER BY d.department_name ASC') as $row) {

			if ((time() - $row['last_activity']) > $operator_timeout) {

				$data = array(
					'success' => false
				);

			} else {

				$departments[] = array(
					'department_id'		=> $row['department_id'],
					'department_name'	=> $row['department_name']
				);

				$data = array(
					'success' 		=> true,
					'departments'	=> $departments
				);

			}

		}

		return $data;

	}
	
}

?>
