<h3><?php echo $user->displayName() ?></h3>
<h4>UserID: <strong><?php echo $user->id() ?></strong></h4>
<h4>Username: <strong><?php echo $user->username() ?></strong></h4>
<h4>Email Address: <strong><?php echo $user->email() ?></strong></h4>
<hr/>
<h4>Account Status: <?php echo $user->statusText(); ?></h4>
<h4>Role: <?php echo $user->roleTitle() ?></h3>
	<h5>Date Joined: <strong><?php echo zbase_date_human_html($user->created_at); ?></strong></h5>
	<h5>Password Updated: <strong><?php echo!empty($user->password_updated_at) ? zbase_date_human_html($user->password_updated_at) : zbase_date_human_html($user->created_at); ?></strong></h5>
	<?php if($user->emailVerificationEnabled()): ?>
		<h5>Email Address Verified: <?php echo $user->emailVerifiedText() ?></h5>
	<?php endif; ?>
	<h5>Location: <strong><?php echo $user->location; ?></strong></h5>
	<hr />