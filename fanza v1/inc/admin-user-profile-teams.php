<?php
// Add user field
add_action( 'show_user_profile', 'fanzalive_user_profile_fields' );
add_action( 'edit_user_profile', 'fanzalive_user_profile_fields' );

function fanzalive_user_profile_fields( $user ) {
	$selected_team_ids = fanzalive_get_user_report_teams($user->ID);
	$teams = get_posts([
		'post_type' 	=> 'sp_team',
		'post_status' 	=> 'publish',
		'orderby' 		=> 'title',
		'order' 		=> 'asc',
		'posts_per_page'=> -1
	]);

    ?><style>
    .teams-choices{
        display: flex;
        flex-wrap: wrap;
    }
    @media(min-width:960px) {
        .teams-choices li{
            flex:1 auto;
            width: 20%;
        }
    }
    @media(min-width:783px) and (max-width:959px) {
        .teams-choices li{
            flex:1 auto;
            width: 25%;
        }
    }
    @media(min-width:481px) and (max-width:782px) {
        .teams-choices li{
            flex:1 auto;
            width: 50%;
        }
    }
    @media(max-width:480px) {
        .teams-choices li{
            flex:1 auto;
            width: 100%;
        }
    }
    </style>
    <h3><?php _e("Select teams this user can report for"); ?></h3>
    <ul class="teams-choices"><?php
	foreach ($teams as $team) {
        printf(
            '<li><label><input type="checkbox" name="teams[]" value="%d"%s /> %s</label></li>',
            $team->ID,
            in_array($team->ID, $selected_team_ids) ? ' checked="checked"' : '',
            $team->post_title
        );
	}
    ?></ul><?php
}
add_action( 'personal_options_update', 'fanzalive_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'fanzalive_save_user_profile_fields' );

function fanzalive_save_user_profile_fields( $user_id ) {
    if (! current_user_can('edit_user', $user_id) || ! isset($_POST['fanzalive_report_team_ids'])) {
        return false;
    }

    fanzalive_set_user_report_teams($user_id, $_POST['fanzalive_report_team_ids']);
}
