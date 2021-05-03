<?php

add_action( 'personal_options_update', 'robo_save_fields'  );
add_action( 'edit_user_profile_update', 'robo_save_fields'  );

add_action( 'show_user_profile', 'robo_profile_form'  );
add_action( 'edit_user_profile', 'robo_profile_form'  );

function robo_save_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

	if ( isset( $_POST['user_age'] ) ) {
    	update_user_meta( $user_id, 'user_age', $_POST['user_age'] );
    }

	if ( isset( $_POST['user_gender'] ) ) {
    	update_user_meta( $user_id, 'user_gender', $_POST['user_gender'] );
    }

    if ( isset( $_POST['user_school'] ) ) {
    	update_user_meta( $user_id, 'user_school', $_POST['user_school'] );
    }

    if ( isset( $_POST['user_parents_first'] ) ) {
    	update_user_meta( $user_id, 'user_parents_first', $_POST['user_parents_first'] );
    }

    if ( isset( $_POST['user_parents_last'] ) ) {
    	update_user_meta( $user_id, 'user_parents_last', $_POST['user_parents_last'] );
    }

    if ( isset( $_POST['user_phone'] ) ) {
    	update_user_meta( $user_id, 'user_phone', $_POST['user_phone'] );
    }

    if ( isset( $_POST['user_experience'] ) ) {
    	update_user_meta( $user_id, 'user_experience', $_POST['user_experience'] );
    }
}

function robo_profile_form( $user ) {
	$user_gender        = get_the_author_meta( 'user_gender', $user->ID );
	$user_age           = get_the_author_meta( 'user_age', $user->ID );
	$user_school        = get_the_author_meta( 'user_school', $user->ID );
	$user_parents_first = get_the_author_meta( 'user_parents_first', $user->ID );
	$user_parents_last  = get_the_author_meta( 'user_parents_last', $user->ID );
	$user_phone         = get_the_author_meta( 'user_phone', $user->ID );
	$user_experience    = get_the_author_meta( 'user_experience', $user->ID );
?>

	<table class="form-table">
		<tr>
            <th> <label for="user_age"> Age </label> </th>
            <td>
				<input id="user_age" name="user_age" type="text" value="<?php echo esc_attr( $user_age ); ?>"/>
            </td>
        </tr>

       	<tr>
            <th> <label for="user_gender"> Gender </label> </th>
            <td>
				<input id="user_gender" name="user_gender" type="radio" value="<?php echo esc_attr( $user_gender ); ?>"
				<?php if ( $user_gender == 'male'  ) echo ' checked="checked"'; ?> /> Male
				<input id="user_gender" name="user_gender" type="radio" value="<?php echo esc_attr( $user_gender ); ?>"
				<?php if ( $user_gender == 'female'  ) echo ' checked="checked"'; ?> /> FeMale
            </td>
        </tr>

        <tr>
            <th> <label for="user_school"> School Name </label> </th>
            <td>
				<input id="user_school" name="user_school" type="text" value="<?php echo esc_attr( $user_school ); ?>"/>
            </td>
        </tr>

       	<tr>
            <th> <label for="user_parents"> Parents Name </label> </th>
            <td>
				<input id="user_parents_first" name="user_parents_first" type="text" value="<?php echo esc_attr( $user_parents_first ); ?>"/>
				<input id="user_parents_last" name="user_parents_last" type="text" value="<?php echo esc_attr( $user_parents_last ); ?>"/>
            </td>
        </tr>

        <tr>
            <th> <label for="user_phone"> TelePhone </label> </th>
            <td> <input id="user_phone" name="user_phone" type="text" value="<?php echo esc_attr( $user_phone ); ?>"/> </td>
        </tr>

		<tr>
            <th> <label for="user_experience"> Previous Coding Experience </label> </th>
            <td>
				<textarea name="user_experience"> <?php echo $user_experience; ?> </textarea>
            </td>
        </tr>

	</table>
<?php
}