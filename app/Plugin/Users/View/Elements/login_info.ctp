<div class="login_link">
	<?php
	if(empty($auth_user)) {
		?>
		<ul>
			<li>
				<?php
				echo $this->Html->link('Login', array(
					'controller' => 'users',
					'action' => 'login'
				));
				?>
			</li>
			<?php
			if(is_null(Configure::read('Users.allowRegistration')) OR Configure::read('Users.allowRegistration')) {
				?>
				<li>
					<?php
					echo $this->Html->link('Register', array(
						'controller' => 'users',
						'action' => 'register'
					));
					?>
				</li>
				<?php
			}
			?>
		</ul>
		<?php
	}else{
		?>
		<p>
			<?php
			$name = (empty($auth_user['name']))
				? $auth_user[Configure::read('Users.loginName')]
				: $auth_user['name'];
			echo 'Hello ' . $name . '.';
			?>
		</p>
		<?php
		if(!empty($auth_user['is_admin'])) {
			echo '<p>You are Admin.</p>';
		}
		if(isset($auth_user['role_id']) AND !empty($roles) AND !empty($roles[$auth_user['role_id']])) {
			echo '<p>Role: ' . $roles[$auth_user['role_id']] . '.</p>';
		}
		?>
		<ul>
			<li>
				<?php
				echo $this->Html->link('Dashboard', array(
					'controller' => 'users',
					'action' => 'dashboard',
					'plugin' => false
				));
				?>
			</li>
			<li>
				<?php
				echo $this->Html->link('Profile', array(
					'controller' => 'users',
					'action' => 'profile',
					'plugin' => false
				));
				?>
			</li>
			<li>
				<?php
				echo $this->Html->link('Log Out', array(
					'controller' => 'users',
					'action' => 'logout',
					'plugin' => false
				));
				?>
			</li>
		</ul>
		<?php
	}
	?>
</div>