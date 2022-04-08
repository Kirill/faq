<?php
	$g_mantis_faq_table              = plugin_table('results');

	#----------------------------------
	# faq page definitions

	$g_faq_menu_page                 = plugin_page( 'faq_menu_page.php' );
	$g_faq_view_page                 = plugin_page( 'faq_view_page.php' );

	$g_faq_edit_page                 = plugin_page( 'faq_edit_page.php' );
	$g_faq_add_page                  = plugin_page( 'faq_add_page.php' );
	$g_faq_add                       = plugin_page( 'faq_add.php' );
	$g_faq_add2                       = plugin_page( 'faq_add2.php' );
	$g_faq_delete_page               = plugin_page( 'faq_delete_page.php' );
	$g_faq_delete                    = plugin_page( 'faq_delete.php' );
	$g_faq_update                    = plugin_page( 'faq_update.php' );

		#----------------------------------

	###########################################################################
	# faq API
	###########################################################################

	# function faq_add   ( $p_project_id, $p_poster_id, $p_question, $p_answere );
	# function faq_delete( $p_id );
	# function faq_update( $p_id, $p_question, $p_answere );
	# function faq_select( $p_id );

	# --------------------
	function faq_add_query( $p_project_id, $p_poster_id, $p_question, $p_answere ,$p_view_level= 10) {
		global $g_mantis_faq_table;

		# " character poses problem when editting so let's just convert them
		$p_question	= db_prepare_string( $p_question );
		$p_answere	= db_prepare_string( $p_answere );

		# Add item
		$t_query = "INSERT
			INTO $g_mantis_faq_table
	   		( id, project_id, poster_id, date_posted, last_modified, question, answere, view_access )
			VALUES
				( null, '" . db_param() . "', '" . db_param() . "', " . db_param() . ", " . db_param() . ", '" . db_param() . "', '" . db_param() . "', '" . db_param() . "' )";
	  return db_query_bound( $t_query, array( $p_project_id, $p_poster_id, db_now(), db_now(), $p_question, $p_answere, $p_view_level) );
	}
	# --------------------
	# Delete the faq entry
	function faq_delete_query( $p_id ) {
		global $g_mantis_faq_table;

		$t_query = "DELETE
			FROM $g_mantis_faq_table
	  	WHERE id=" . db_param();
	  return db_query_bound( $t_query, array( $p_id ) );
	}
	# --------------------
	# Update faq item
	function faq_update_query( $p_id, $p_question, $p_answere, $p_project_id ,$p_view_level) {
		global $g_mantis_faq_table;

		# " character poses problem when editting so let's just convert them to '
		$p_question	= db_prepare_string( $p_question );
		$p_answere	= db_prepare_string( $p_answere );

		# Update entry
		$t_query = "UPDATE $g_mantis_faq_table
			SET question='" . db_param() . "', answere='" . db_param() . "',
			project_id='" . db_param() . "', view_access='" . db_param() . "', last_modified=" . db_param() . "
	  	WHERE id=" . db_param();
	  return db_query_bound( $t_query, array( $p_question, $p_answere, $p_project_id, $p_view_level, db_now(), $p_id ) );
	}
	# --------------------
	# Selects the faq item associated with the specified id
	function faq_select_query( $p_id ) {
		global $g_mantis_faq_table;

		$t_query = "SELECT *
			FROM $g_mantis_faq_table
			WHERE id=" . db_param();
	  $result = db_query_bound( $t_query, array( $p_id ) );
		return db_fetch_array( $result );
	}
	# --------------------
	# get faq count (selected project plus sitewide posts)
	function faq_count_query( $p_project_id ) {
		global $g_mantis_faq_table;

		$t_query = "SELECT COUNT(*)
				FROM $g_mantis_faq_table
				WHERE project_id='" . db_param() . "' OR project_id='0000000'";
		$result = db_query_bound( $t_query, array( $p_project_id ) );
	  return db_result( $result, 0, 0 );
	}