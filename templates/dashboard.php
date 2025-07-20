<?php

add_action('dokan_dashboard_wrap_start', 'awesome_dokan_dashboard_wrap_start');
function awesome_dokan_dashboard_wrap_start(){
	?>
<div class="awesome-dokan-wrapper">

	<div class="modern-dokan-header">
		<div class="header-left">
			<a href="<?php echo home_url(); ?>" class="dashboard-logo">
				<img src="<?php echo get_site_icon_url(64); ?>" alt="Logo" />
				<?php
					$user_data = get_userdata( dokan_get_current_user_id() );
					$hour = (int) current_time('H');
					$greeting = ( $hour < 12 ) ? 'Good morning' : ( ( $hour < 18 ) ? 'Good afternoon' : 'Good evening' );
				?>
				<span><?php echo $greeting . ', ' . esc_html( $user_data->display_name ); ?> 👋</span>
			</a>
		</div>

		<div class="header-right">
			<a href="<?php echo dokan_get_store_url( dokan_get_current_user_id() ); ?>" class="icon-btn" title="Visit Store">
				<i class="fas fa-store"></i>
			</a>

			<a href="<?php echo esc_url( dokan_get_navigation_url( 'new-product' ) ); ?>" class="icon-btn" title="Add Product">
				<i class="fas fa-plus-circle"></i>
			</a>

			<a href="<?php echo esc_url( dokan_get_navigation_url( 'withdraw' ) ); ?>" class="icon-btn" title="Withdraw">
				<i class="fas fa-wallet"></i>
			</a>

			<?php
			$new_orders = dokan_count_orders( dokan_get_current_user_id(), 'pending' );
			?>
			<a href="<?php echo esc_url( dokan_get_navigation_url( 'orders' ) ); ?>" class="icon-btn" title="New Orders">
				<i class="fas fa-bell"></i>
				<?php if ( $new_orders ) : ?>
				<span class="badge"><?php echo isset( $new_orders->count ) ? intval( $new_orders->count ) : 0; ?></span>

				<?php endif; ?>
			</a>

			<div class="avatar-wrap">
				<button class="avatar-btn">
					<?php echo get_avatar( get_current_user_id(), 32 ); ?>
					<i class="fas fa-chevron-down"></i>
				</button>
				<ul class="avatar-dropdown">
					<li><a href="<?php echo esc_url( dokan_get_navigation_url( 'settings' ) ); ?>">Edit Account</a></li>
					<li><a href="<?php echo wp_logout_url( home_url() ); ?>">Log Out</a></li>
				</ul>
			</div>
		</div>
	</div>
	<?php
}

add_action('dokan_dashboard_wrap_end', 'awesome_dokan_dashboard_wrap_end');
function awesome_dokan_dashboard_wrap_end(){
	?>
</div>
<?php
}